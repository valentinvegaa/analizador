<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 01-12-2014
 * Time: 11:02
 */
$queryFilterWord = isset($_REQUEST['qw']) ? $_REQUEST['qw'] : false;
$limitGBIF = 20;
if ($queryFilterWord) {
    print "<script type=\"text/javascript\">location.href = 'http://www.ecoinformatica.cl/site/analizador/species/$queryFilterWord';</script>";
}
$path = drupal_get_path('module', 'analizador_biodiversidad');
include($path . '/include/solrConnection.php');
include($path . '/Apache/Solr/Service.php');

function countMonths($taxonKey)
{
    $returnVal = array();
    for ($i = 1; $i < 13; $i++) {
        $months = json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/search?taxonKey=' . $taxonKey . '&HAS_COORDINATE=true&country=CL&month=' . $i . '&limit=1'));
        $returnVal[$i - 1] = $months->count;
    }
    return $returnVal;
}

function countYears($taxonKey, $count)
{
    $returnVal = array();
    $offset = 0;
    $localCount = $count;
    if ($localCount > 300) {
        while ($localCount > 0) {
            $years = json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/search?taxonKey=' . $taxonKey . '&HAS_COORDINATE=true&country=CL&limit=' . $localCount . '&offset=' . $offset . '&year=1900,2015'), true);
            foreach ($years['results'] as $i) {
                if (!array_key_exists($i['year'], $returnVal)) {
                    $returnVal[$i['year']] = 1;
                } else {
                    $returnVal[$i['year']]++;
                }
            }
            $localCount -= 300;
            $offset += 300;
        }
    } else {
        $years = json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/search?taxonKey=' . $taxonKey . '&HAS_COORDINATE=true&country=CL&limit=' . $localCount . '&offset=' . $offset . '&year=1900,2015'), true);
        foreach ($years['results'] as $i) {
            if (!array_key_exists($i['year'], $returnVal)) {
                $returnVal[$i['year']] = 1;
            } else {
                $returnVal[$i['year']]++;
            }
        }
    }
    ksort($returnVal);
    return $returnVal;
}

function getOrganizationNames($organizations)
{
    $result = array();
    foreach ($organizations as $key => $i) {
        $json = json_decode(file_get_contents('http://api.gbif.org/v1/organization/' . $key), true);
        $result[$json['title']] = $i;
        //$org=json_decode(file_get_contents("http://api.gbif.org/v1/organization/$i"),true);
    }
    //var_dump($result);
    return $result;
}
function getFamilyGenus($key){//obtiene la cantidad de observaciones de cada genero asociado a la familia $key
    $children = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=GENUS&highertaxon_key='.$key), true);//106311492
    $result=array();
    foreach($children['results'] as $i){
        //$childrenCount=getChildrenNames($i['key']);
        //var_dump($childrenCount);
        $childrenCount = json_decode(file_get_contents('http://api.gbif.org/v1/occurrences/count?taxonKey='.$i['key']), true);//106311492
        $count = 0;
        foreach($childrenCount as $key=>$value){
            $count+=$value;
        }
        if($count>0){
            //$result.="{'name':'".$i['species']."','data':[".$count."]},";
            $result[$i['genus']]=$count;
        }
        //echo 'species '.$i['species'].' count '.$count;
    };
    //var_dump($result);
    return $result;
}
function getChildrenNames($key){
    $children = json_decode(file_get_contents('http://api.gbif.org/v1/species/'.$key.'/children/?limit=300'), true);//106311492
    $result=array();
    foreach($children['results'] as $i){
        $count = json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/count?taxonKey='.$i['nubKey'].'&country=CL'),true);
        if($count>0){
            //$result.="{'name':'".$i['species']."','data':[".$count."]},";
            $result[$i['species']]=$count;
        }
        //echo 'species '.$i['species'].' count '.$count;
    };
    //var_dump($result);
    return $result;
}
$limit = 10000;
$someVar = "";
$results = false;
$coordinatesInPHP = "";
$coordinatesGBIFInPHP = "";
$totalGBIF = 0;
$totalReuna = 0;
$totalReunaConCoordenadas = 0;
$reunaVacios = 0;
$OrganizationKey = '';
$OrganizationKeyArray = array();
$speciesFound = array();
$institutionNames = array();
$institutionNamesGBIF = array();
$yearCount = array();
$yearCountGBIF = array();
$yearsGBIFforRange = array();
$monthCount = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$coordYearsREUNA = '';
$coordYearsGBIF = '';
$additionalParameters = array();
$taxonChildrens=array();
$solr = new Apache_Solr_Service("$USR:$PSWD@$HOST", 80, $SOLRPATH);
$search = substr(current_path(), strripos(current_path(), '/') + 1);
if ($search) {
    $query = "RELS_EXT_hasModel_uri_ms:\"info:fedora/biodiversity:biodiversityCModel\"";
    if (get_magic_quotes_gpc() == 1) $query = stripslashes($query);
    $additionalParameters = array(
        'fq' => 'dwc.family_mt:*' . $search . '*',
        'fl' => 'dwc.order_s,
                 dwc.phylum_s,
                 dwc.family_s,
                 dwc.month_s,
                 dwc.year_s,
                 dwc.institutionCode_s,
                 dwc.genus_mt,
                 dwc.scientificName_mt,
                 dc.subject,
                 dwc.latlong_p',
    );
    try {
        $results = $solr->search($query, 0, $limit, $additionalParameters);
    } catch (Exception $e) {
        die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
    }
    $i = 0;
    if ($results) {
        $totalReuna = $results->response->numFound;
        $i = 0;
        foreach ($results->response->docs as $doc) {
            foreach ($doc as $field => $value) {
                switch ($field) {
                    case 'dwc.latlong_p':
                        $coordinatesInPHP .= htmlspecialchars($value, ENT_NOQUOTES, 'utf-8') . ",";
                        $totalReunaConCoordenadas++;
                        $i++;
                        break;
                    case 'dwc.scientificName_mt':
                        if (!in_array($value, $speciesFound)) {
                            array_push($speciesFound, $value);
                        }
                        break;
                    case 'dwc.institutionCode_s':
                        if (!array_key_exists($value, $institutionNames)) {
                            $institutionNames[$value] = 1;
                        } else {
                            $institutionNames[$value]++;
                        }
                        break;
                    case 'dwc.year_s':
                        if (!array_key_exists($value, $yearCount)) {
                            $yearCount[$value] = 1;
                        } else {
                            $yearCount[$value]++;
                        }
                        $coordYearsREUNA .= $value . ',';
                        break;
                    case 'dwc.month_s':
                        $monthCount[$value - 1]++;
                        break;
                    case 'dwc.genus_mt':
                        $value=explode(' ',$value);
                        if(sizeof($value)==0){
                            $value[0]='no';
                            $value[1]='asignado';//XD
                        }
                        if(!array_key_exists($value[0].' '.$value[1], $taxonChildrens)){
                            $taxonChildrens[$value[0].' '.$value[1]]=1;
                        }
                        else{
                            $value=explode(' ',$value);
                            $taxonChildrens[$value[0].' '.$value[1]]++;
                        }
                        break;
                }
                /*if($field=='dwc.latlong_p') {
                    $coordinatesInPHP .= htmlspecialchars($value, ENT_NOQUOTES, 'utf-8') . ",";
                    $totalReunaConCoordenadas++;
                    $i++;
                }
                if($field=='dwc.scientificName_mt') {
                    if (!in_array($value, $speciesFound)) {
                        array_push($speciesFound, $value);
                    }
                }
                if($field=='dwc.institutionCode_s'){
                    if(!array_key_exists($value,$institutionNames)){
                        $institutionNames[$value]=1;
                    }
                    else{
                        $institutionNames[$value]++;
                    }
                }
                if($field=='dwc.year_s'){
                    if(!array_key_exists($value,$yearCount)){
                        $yearCount[$value]=1;
                    }
                    else{$yearCount[$value]++;}
                }
                if($field=='dwc.month_s'){
                    $monthCount[$value-1]++;
                }*/
            }
            //echo $institutionNames[$value][0];
        }
        ksort($yearCount);
        var_dump($yearCount);
        $reunaVacios = $totalReuna - $i;
        //echo $coordinatesInPHP;
    }
    //print_r($institutionNames);
    $json = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $search . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=FAMILY&limit=1'), true);
    $familyKey = $json['results'][0]['key'];
    if(false)
    if ($search) {//355609060576005
        //$json = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $search . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=GENUS&limit=1'), true);
        //$orderKey = $json['results'][0]['key'];
        echo $familyKey . 'orderKey';
        //$urlHigherTaxon = 'http://api.gbif.org/v1/species/search?dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=SPECIES&highertaxon_key=106605002&limit=0';
        $urlHigherTaxon = 'http://api.gbif.org/v1/species/search?dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=SPECIES&highertaxon_key=' . $familyKey . '&limit=0';
        $result = json_decode(file_get_contents($urlHigherTaxon), true);
        $countSpecies = $result['count'];
        $url_species = 'http://api.gbif.org/v1/species/match?name='.$search;
        $content = file_get_contents($url_species);
        $json = json_decode($content, true);
        $speciesKey = isset($json['speciesKey']) ? json_encode($json['speciesKey']) : null;
        //echo "<pre>".json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
        //$url="http://api.gbif.org/v1/occurrence/search?taxonKey=$speciesKey&HAS_COORDINATE=true&country=CL&limit=1";
        //$url='http://api.gbif.org/v1/occurrence/search?taxonKey='.$speciesKey.'&HAS_COORDINATE=true&country=CL&limit='.$count.'&offset='.$offset;
        $url = 'http://api.gbif.org/v1/occurrence/count?taxonKey=' . $speciesKey . '&country=CL';
        $content = isset($json['speciesKey']) ? file_get_contents($url) : null;
        //echo 'content_'.$content;
        //$json = json_decode($content, true);
        //var_dump($json);
        $offset = 0;
        $count = $content;//$json['count'];
        $totalGBIF = $count;
        $coordinatesGBIFInPHP = "";
        $yearCountGbif = countYears($speciesKey, $count);
        $asdasd = array();
        if ($count > 300) {
            while ($count > 0) {
                //$url="http://api.gbif.org/v1/occurrence/search?taxonKey=$speciesKey&HAS_COORDINATE=true&country=CL&limit=$count&offset=$offset";
                //$content = file_get_contents($url);
                //$json = json_decode($content, true);
                //echo "<pre>".json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
                $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $speciesKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
                $content = file_get_contents($url);
                $json = json_decode($content, true);
                isset($json['publishingOrgKey']) ? $OrganizationKey = $json['publishingOrgKey'] : $OrganizationKey = 0;
                $someVar = countMonths($speciesKey);
                foreach ($json['results'] as $i) {
                    //$coordinatesGBIFInPHP.=$i['decimalLongitude'].",".$i['decimalLatitude'].",";
                    array_push($asdasd, $i['decimalLongitude']);
                    array_push($asdasd, $i['decimalLatitude']);
                    $coordYearsGBIF .= isset($i['year']) ? $i['year'] : '' . ',';
                    if (!array_key_exists($i['publishingOrgKey'], $OrganizationKeyArray)) {
                        $OrganizationKeyArray[$i['publishingOrgKey']] = 1;
                    } else {
                        $OrganizationKeyArray[$i['publishingOrgKey']]++;
                    }
                }
                $count -= 300;
                $offset += 300;
            }
        } else {
            $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $speciesKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
            $content = file_get_contents($url);
            $json = json_decode($content, true);
            $someVar = countMonths($speciesKey);
            //echo "<pre>".json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
            foreach ($json['results'] as $i) {
                array_push($asdasd, $i['decimalLongitude']);
                array_push($asdasd, $i['decimalLatitude']);
                if (isset($i['year'])) {
                    if ($i['year'] != '') {
                        $coordYearsGBIF .= $i['year'] . ',';
                    } else {
                        $coordYearsGBIF .= '0000,';
                    }
                }
                //echo '_año_'.$i['year'].'_año';
                if (!array_key_exists($i['publishingOrgKey'], $OrganizationKeyArray)) {
                    $OrganizationKeyArray[$i['publishingOrgKey']] = 1;
                } else {
                    $OrganizationKeyArray[$i['publishingOrgKey']]++;
                }
            }
        }
        $coordinatesGBIFInPHP = implode(', ', $asdasd);
        //$yearsGBIFforRange=implode(', ',$tempRange);
        $institutionNamesGBIF = getOrganizationNames($OrganizationKeyArray);
    }
}
?>
<?php // page template ?>

<?php if ($page['topbar']): ?>
    <!--start top bar-->
    <div id="topBar" class="outsidecontent">
        <div id="topBarContainer" class="<?php print $layout_width; ?>">
            <?php print render($page['topbar']); ?>
        </div>
    </div>

    <div id="topBarLink" class="outsidecontent<?php !$topRegion ? print " withoutTopRegion" : print ""; ?>">
        <?php print $topbarlink; ?>
    </div>
    <!--end top bar-->
<?php endif; ?>

<!--start framework container-->
<div class="container_12 <?php print $layout_width; ?>" id="totalContainer">
<?php if ($topRegion): ?>
    <!--start top section-->
    <div id="top" class="outsidecontent">

        <?php if ($page['utility_top']): ?>
            <!--start top utility box-->
            <div class="utility" id="topUtility">
                <?php print render($page['utility_top']); ?>
            </div>
            <!--end top utility box-->
        <?php endif; ?>

        <!--start branding-->
        <div id="branding">

            <?php if ($logo): ?>
                <div id="logo-container">
                    <?php print $imagelogo; ?>
                </div>
            <?php endif; ?>

            <?php if ($site_name || $siteslogan): ?>
                <!--start title and slogan-->
                <div id="title-slogan">
                    <?php if ($site_name): ?>
                        <?php print $sitename; ?>
                    <?php endif; ?>

                    <?php if ($site_slogan): ?>
                        <?php print $siteslogan; ?>
                    <?php endif; ?>
                </div>
                <!--end title and slogan-->
            <?php endif; ?>

        </div>
        <!--end branding-->

        <?php if ($page['search']): ?>
            <!--start search-->
            <div id="search">
                <?php print render($page['search']); ?>
            </div>
            <!--end search-->
        <?php endif; ?>

    </div>
    <!--end top section-->
<?php endif; ?>

<?php if ($mainmenu): ?>
    <!--start main menu-->
    <div id="navigation-primary" class="sitemenu">
        <?php print $mainmenu; ?>
    </div>
    <!--end main menu-->
<?php endif; ?>

<!--border start-->
<div id="pageBorder" <?php print $noborder; ?>>
    <?php if ((!$usebanner && $page['advertise']) || ($usebanner && $banner_image)): ?>
        <!--start advertise section-->
        <!--end advertise-->
    <?php endif; ?>

    <?php if ($secondary_menu): ?>
        <!--start secondary navigation-->
        <div id="navigation-secondary" class="sitemenu">
            <?php print theme('links', array('links' => $secondary_menu, 'attributes' => array('id' => 'secondary-menu', 'class' => array('links', 'clearfix', 'secondary-menu')))); ?>
        </div>
        <!--end secondary-navigation-->
    <?php endif; ?>

    <!-- start contentWrapper-->
    <div id="contentWrapper">
        <!--start breadcrumb -->
        <?php if ($breadcrumb): ?>
            <div id="breadcrumb"><?php print $breadcrumb; ?></div>
        <?php endif; ?>
        <!-- end breadcrumb -->

        <?php if ($page['overcontent']): ?>
            <!--start overcontent-->
            <div class="grid_12 outofContent" id="overContent">
                <?php print render($page['overcontent']); ?>
            </div>
            <!--end overContent-->
        <?php endif; ?>

        <!--start innercontent-->
        <div id="innerContent">

            <!--start main content-->
            <div
                class="<?php print marinelli_c_c($page['sidebar_first'], $page['sidebar_second'], $layoutType, $exception); ?>"
                id="siteContent">
                <?php if ($page['overnode']): ?>
                    <!--start overnode-->
                    <div class="outofnode" id="overNode">
                        <?php print render($page['overnode']); ?>
                    </div>
                    <!--end over node-->
                <?php endif; ?>

                <?php if ($page['highlight']): ?>
                    <div id="highlight">
                        <?php print render($page['highlight']); ?>
                    </div>
                <?php endif; ?>

                <?php print render($title_prefix); ?>

                <?php if ($title): ?>
                    <h1 id="page-title"><?php print $title; ?></h1>
                <?php endif; ?>

                <?php print render($title_suffix); ?>
                <?php print $messages; ?>

                <?php if ($tabs): ?>
                    <div class="tab-container">
                        <?php print render($tabs); ?>
                    </div>
                <?php endif; ?>

                <?php print render($page['help']); ?>

                <?php if ($action_links): ?>
                    <ul class="action-links"><?php print render($action_links); ?></ul>
                <?php endif; ?>

                <!--start drupal content-->
                <div id="content">
                    <?php print render($page['content']); ?>
                    <?php
                    include(drupal_get_path('module', 'analizador_biodiversidad') . '/include/analizador_familia.php');
                    ?>
                </div>
                <!--end drupal content-->

                <?php print $feed_icons ?>

                <?php if ($page['undernode']): ?>
                    <!--start undernode-->
                    <div class="outofnode" id="underNode">
                        <?php print render($page['undernode']); ?>
                    </div>
                    <!--end under node-->
                <?php endif; ?>

            </div>
            <!--end main content-->

            <?php if ($page['sidebar_first'] && $page['sidebar_second'] && theme_get_setting('layout_type') != 2): ?>
            <div class="<?php print marinelli_w_c($layoutType); ?>" id="sidebarWrapper">
                <!--start oversidebars-->
                <?php if ($page['oversidebars']): ?>
                    <div class="outofsidebars grid_6 alpha omega" id="overSidebars">
                        <?php print render($page['oversidebars']); ?>
                    </div>
                <?php endif; ?>
                <!--end over sidebars-->
                <?php endif; ?>

                <?php if ($page['sidebar_first']): ?>
                    <!--start first sidebar-->
                    <div
                        class="<?php print marinelli_s_c($page['sidebar_first'], $page['sidebar_second'], $layoutType, 1); ?> sidebar"
                        id="sidebar-first">
                        <?php print render($page['sidebar_first']); ?>
                    </div>
                    <!--end first sidebar-->
                <?php endif; ?>

                <?php if ($page['sidebar_second']): ?>
                    <!--start second sidebar-->
                    <div
                        class="<?php print marinelli_s_c($page['sidebar_first'], $page['sidebar_second'], $layoutType, 2); ?> sidebar"
                        id="sidebar-second"><!--start second sidebar-->
                        <?php print render($page['sidebar_second']); ?>
                    </div>
                    <!--end second sidebar-->
                <?php endif; ?>


                <?php if ($page['sidebar_first'] && $page['sidebar_second'] && theme_get_setting('layout_type') != 2): ?>
                <?php if ($page['undersidebars']): ?>
                    <!--start undersidebars-->
                    <div class="outofsidebars grid_6 alpha omega" id="underSidebars">
                        <?php print render($page['undersidebars']); ?>
                    </div>
                    <!--end under sidebars-->
                <?php endif; ?>
            </div>
            <!--end sidebarWrapper-->
        <?php endif; ?>


        </div>
        <!--end innerContent-->


        <?php if ($page['undercontent']): ?>
            <!--start underContent-->
            <div class="grid_12 outofContent" id="underContent">
                <?php print render($page['undercontent']); ?>
            </div>
            <!--end underContent-->
        <?php endif; ?>
    </div>
    <!--end contentWrapper-->

</div>
<!--close page border Wrapper-->

<?php if ($page['footer'] || $page['utility_bottom']): ?>
    <!--start footer-->
    <div id="footer" class="outsidecontent">
        <?php print render($page['footer']); ?>

        <?php if ($page['utility_bottom']): ?>
            <!--start bottom utility box-->
            <div class="utility" id="bottomUtility">
                <?php print render($page['utility_bottom']); ?>
            </div>
            <!--end bottom utility box-->
        <?php endif; ?>
    </div>
    <!--end footer-->
<?php endif; ?>

</div>
<!--end framework container-->