<?php
$path = drupal_get_path('module', 'analizador_biodiversidad');
require_once($path . '/include/solrConnection.php');
require_once($path . '/Apache/Solr/Service.php');

function countMonths($taxonKey)
{
    $returnVal = array();
    for ($i = 1; $i < 13; $i++) {
        $months = json_decode(file_get_contents("http://api.gbif.org/v1/occurrence/search?taxonKey=$taxonKey&HAS_COORDINATE=true&country=CL&month=$i"));
        $returnVal[$i - 1] = $months->count;
    }
    return $returnVal;
}

$limit = 10000;
//$query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
//$queryFilterWord="";
//$query="RELS_EXT_hasModel_uri_ms:[* TO *]";
//$query="*:*";
$tipo = "";
$someVar = "";
$results = false;
$coordinatesInPHP = "";
$coordinatesGBIFInPHP = "";
$totalGBIF = 0;
$totalReuna = 0;
$totalReunaConCoordenadas = 0;
$reunaVacios = 0;
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
$instituionsNamesOcurr = array();
$yearCount = array();
$yearCountGBIF = array();
$monthCount = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$haceAlgo = false;
$additionalParameters = array();
$queryFilterWord = isset($_REQUEST['qw']) ? $_REQUEST['qw'] : false;
$solr = new Apache_Solr_Service("$USR:$PSWD@$HOST", 80, $SOLRPATH);
/*try{
    $resultsAutocomplete=$solr->search('*:*',0,0,array('facet'=>'on','facet.field'=>'dwc.scientificName_s'));
    if($resultsAutocomplete){
        foreach($resultsAutocomplete->facet_counts->facet_fields as $scientificName){
            foreach($scientificName as $name=>$count){
                if(strcmp($name,"_empty_")!==0){
                    $explodedName=explode(' ',$name);
                    array_push($autoComEspecies,'"'.$explodedName[0].' '.$explodedName[1].'"');
                    array_push($autocompleteFieldType,'"Especie"');
                }
            }
        }
    }
    $resultsAutocomplete=null;
    $resultsAutocomplete=$solr->search('*:*',0,0,array('facet'=>'on','facet.field'=>'dwc.genus_s'));
    if($resultsAutocomplete){
        foreach($resultsAutocomplete->facet_counts->facet_fields as $scientificName){
            foreach($scientificName as $name=>$count){
                if(strcmp($name,"_empty_")!==0){
                    array_push($autoComGenero,'"'.$name.'"');
                    array_push($autocompleteFieldType,'"Género"');
                }
            }
        }
    }
    $resultsAutocomplete=null;
    $resultsAutocomplete=$solr->search('*:*',0,0,array('facet'=>'on','facet.field'=>'dwc.family_s'));
    if($resultsAutocomplete){
        foreach($resultsAutocomplete->facet_counts->facet_fields as $scientificName){
            foreach($scientificName as $name=>$count){
                if(strcmp($name,"_empty_")!==0){
                    array_push($autoComFamilia,'"'.$name.'"');
                    array_push($autocompleteFieldType,'"Familia"');
                }
            }
        }
    }
    $resultsAutocomplete=null;
    $resultsAutocomplete=$solr->search('*:*',0,0,array('facet'=>'on','facet.field'=>'dwc.order_s'));
    if($resultsAutocomplete){
        foreach($resultsAutocomplete->facet_counts->facet_fields as $scientificName){
            foreach($scientificName as $name=>$count){
                if(strcmp($name,"_empty_")!==0){
                    array_push($autoComOrden,'"'.$name.'"');
                    array_push($autocompleteFieldType,'"Orden"');
                }
            }
        }
    }
    $resultsAutocomplete=null;
    $resultsAutocomplete=$solr->search('*:*',0,0,array('facet'=>'on','facet.field'=>'dwc.phylum_s'));
    if($resultsAutocomplete){
        foreach($resultsAutocomplete->facet_counts->facet_fields as $scientificName){
            foreach($scientificName as $name=>$count){
                if(strcmp($name,"_empty_")!==0){
                    array_push($autoComPhylum,'"'.$name.'"');
                    array_push($autocompleteFieldType,'"Phylum"');
                }
            }
        }
    }
}
catch(Exception $e){echo "Error: ".$e;}*/
if ($queryFilterWord) {
    //DETERMINAR EL TIPO DE ---->$queryFilterWord<----!
    //print "<script type=\"text/javascript\">location.href = 'http://www.ecoinformatica.cl/site/analizador/species/$queryFilterWord';</script>";
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
            <!-- advertise section removed, look in original theme_page.tpl.php-->
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
                        if ($queryFilterWord) {
                            include(drupal_get_path('module', 'analizador_biodiversidad') . '/include/generalSearch.php');
                        } else {
                            if (user_is_logged_in()) {
                                include(drupal_get_path('module', 'analizador_biodiversidad') . '/include/analizador_busqueda.php');
                            }
                        }
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
<?php $_POST = array(); ?>