<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 28-10-2014
 * Time: 20:22
 */
class Especie{
    private $nombreCientifico='';
    private $autor='';
    private $genero='';
    private $familia='';
    private $orden='';

    private $posReuna=array();
    private $posGbif=array();
    private $anyo=array();
    private $mes=array();
    private $institucionReuna=array();
    private $institucion=array();
    //private $region='';
    private $categories=array();

    public function setSpecie($nombreCientifico,$posicionReuna,$posicionGbif,$anyo,$mes,$institucionReuna,$institucion){
        $this->nombreCientifico=$nombreCientifico;
        $this->posReuna=$posicionReuna;
        $this->posGbif=$posicionGbif;
        $this->anyo=$anyo;
        $this->mes=$mes;
        $this->institucionReuna=$institucionReuna;
        $this->institucion=$institucion;
    }
    public function getCategories(){
        $categs=array();
        foreach($this->institucion as $key=>$value){
            array_push($categs,$value[0]);
        }
        return $categs;
    }
    public function getInstitucionReuna(){
        return $this->institucionReuna;
    }
    public function getInstitucion(){
        return $this->institucion;
    }
    public function getPosition(){
        return $this->posGbif;
    }
    public function getReunaPosition(){
        return $this->posReuna;
    }

}

$queryFilterWord = isset($_REQUEST['qw']) ? $_REQUEST['qw'] : false;
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
        //$result[$json['title']] = $i;
        array_push($result,array($json['title'],$i));
        //$org=json_decode(file_get_contents("http://api.gbif.org/v1/organization/$i"),true);
    }
    ksort($result);
    //var_dump($result);sdaasf
    return $result;
}
$limit = 10000;
$tipo = "";
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
$autoComEspecies = array();
$autoComGenero = array();
$autoComFamilia = array();
$autoComOrden = array();
$autoComClase = array();
$autoComPhylum = array();
$autoComInstitucion = array();
$autoComInvestigador = array();
$autocompleteFieldType = array();
$speciesFound = array();
$institutionNames = array();
$institutionNamesGBIF = array();
$instituionsNamesOcurr = array();
$yearCount = array();
$yearsGBIFforRange = array();
$monthCount = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$yearCountGbif = array();
$coordYearsREUNA = '';
$coordYearsGBIF = '';
$additionalParameters = array();
$solr = new Apache_Solr_Service("$USR:$PSWD@$HOST", 80, $SOLRPATH);
$specie = substr(current_path(), strripos(current_path(), '/') + 1);
$coordinatesReuna=array();
if($specie){
    if ($cached = cache_get($specie, 'cache')){
        $results = $cached->data;
        $institutionNamesGBIF=$results->getInstitucion();
        $categoriesGBIF=$results->getCategories();
        $coordinatesGBIFInPHP=$results->getPosition();
        $coordinatesReuna=$results->getReunaPosition();
        $institutionNames=$results->getInstitucionReuna();
        echo 'cache!';
    }
    else{
        $query='*:*';
        $search=explode(' ',$specie);
        $additionalParameters = array(
            'fq' => 'dwc.genus_mt:'.$search[0].' AND dwc.specificEpithet_mt:'.$search[1],
            'fl' => 'dwc.month_s,
                 dwc.year_s,
                 dwc.institutionCode_s,
                 dwc.scientificName_mt,
                 dc.subject,
                 dwc.latlong_p',
        );
        try {
            $results = $solr->search($query, 0, $limit, $additionalParameters);
        } catch (Exception $e) {
            die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
        }
        if ($results) {
            $totalReuna = $results->response->numFound;
            $i = 0;
            foreach ($results->response->docs as $doc) {
                foreach ($doc as $field => $value) {
                    switch ($field) {
                        case 'dwc.latlong_p'://[(a,b),(a,b)
                            $coord=explode(',',$value);
                            array_push($coordinatesReuna,$coord);
                            $totalReunaConCoordenadas++;
                            $i++;
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
                    }
                }
            }
            ksort($yearCount);
            $reunaVacios = $totalReuna - $i;
        }
        $url_species = 'http://api.gbif.org/v1/species/match?name=' . str_replace(' ', '+', $specie);
        $content = file_get_contents($url_species);
        $json = json_decode($content, true);
        //se obtiene la llave que identifica a la especie en el repositorio de especies de GBIF
        $speciesKey = isset($json['speciesKey']) ? json_encode($json['speciesKey']) : null;
        //Obtenemos la cantidad de ocurrencias chilenas georeferenciadas usando la llave que encontramos antes
        $url = 'http://api.gbif.org/v1/occurrence/count?taxonKey=' . $speciesKey . '&isGeoreferenced=true&country=CL';
        $totalGBIF = isset($json['speciesKey']) ? file_get_contents($url) : null;
        $offset = 0;
        $count=$totalGBIF;
        $coordinatesGBIFInPHP = "";
        //obtenemos un arreglo con los años como llave y en cada posicion la cantidad de ocurrencias para ese año(llave del arreglo)
        $yearCountGbif = countYears($speciesKey, $count);
        var_dump($yearCountGbif);
        $SpeciesObject=new Especie();
        $temporaryArray = array();
        if ($count > 300) {
            while ($count > 0) {
                //obtenemos las ocurrencias chilenas georefernciadas
                $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $speciesKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
                $content = file_get_contents($url);
                $json = json_decode($content, true);
                isset($json['publishingOrgKey']) ? $OrganizationKey = $json['publishingOrgKey'] : $OrganizationKey = 0;
                $someVar = countMonths($speciesKey);
                foreach ($json['results'] as $i) {
                    //$coordinatesGBIFInPHP.=$i['decimalLongitude'].",".$i['decimalLatitude'].",";
                    array_push($temporaryArray, $i['decimalLongitude']);
                    array_push($temporaryArray, $i['decimalLatitude']);
                    $coordYearsGBIF .= isset($i['year']) ? $i['year'] : '-1' . ',';
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
                array_push($temporaryArray, $i['decimalLongitude']);
                array_push($temporaryArray, $i['decimalLatitude']);
                if (isset($i['year'])) {
                    if ($i['year'] != '') {
                        $coordYearsGBIF .= $i['year'] . ',';
                    } else {
                        $coordYearsGBIF .= '-1,';
                    }
                }
                else {
                    $coordYearsGBIF .= '-1,';
                }
                //echo '_año_'.$i['year'].'_año';
                if (!array_key_exists($i['publishingOrgKey'], $OrganizationKeyArray)) {
                    $OrganizationKeyArray[$i['publishingOrgKey']] = 1;
                } else {
                    $OrganizationKeyArray[$i['publishingOrgKey']]++;
                }
            }
        }
        $coordinatesGBIFInPHP = implode(', ', $temporaryArray);
        //$yearsGBIFforRange=implode(', ',$tempRange);
        $institutionNamesGBIF = getOrganizationNames($OrganizationKeyArray);
        //$results = json_decode(file_get_contents($search_url));
        $SpeciesObject->setSpecie($specie,$coordinatesReuna,$coordinatesGBIFInPHP,$yearCountGbif,$someVar,$institutionNames,$institutionNamesGBIF);

        cache_set($specie, $SpeciesObject, 'cache', 60*60*30*24); //30 dias
        echo 'NO cache!';
    }
}
var_dump($coordinatesReuna);
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
                    include(drupal_get_path('module', 'analizador_biodiversidad') . '/include/analizador_especies.php');
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