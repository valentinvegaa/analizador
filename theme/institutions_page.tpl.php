<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 28-01-2015
 * Time: 11:23
 */
$path = drupal_get_path('module', 'analizador_biodiversidad');
include($path . '/include/obtenerRegion.php');
include($path . '/include/solrConnection.php');
include($path . '/Apache/Solr/Service.php');
$solr = new Apache_Solr_Service("$USR:$PSWD@$HOST", 80, $SOLRPATH);
$institucion = substr(current_path(), strripos(current_path(), '/') + 1);
$parameters = array(
    'fq' => '',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.institutionCode_s'
);
$categories=array();
$series=array();
$participacion=array();
function getResults($parameters,$solr){
    try {
        return $solr->search('*:*', 0, 0, $parameters);
    }catch (Exception $e)
    {
        return die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
    }
}
function getWidth($a,$b){
    return $b*44/$a;
}
$results=getResults($parameters,$solr);
if ($results) {
    $totalInstitutions = $results->response->numFound;
    foreach ($results->facet_counts->facet_fields as $doc) {
        foreach ($doc as $field => $value) {
            array_push($categories,$field);
            array_push($series,array('name'=>$field,'data'=>array($value), 'index'=>$value, 'legendIndex'=>-1*$value));
        }
    }
    arsort($series);
}
$parameters = array(
    'fq' => 'dwc.institutionCode_s:"'.$institucion.'"',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.year_s'
);
$results=getResults($parameters,$solr);
if($results){
    $localTemp=array();
    foreach ($results->facet_counts->facet_fields as $doc) {
        foreach ($doc as $field => $value) {
            if($value>0){
                $localTemp[$field] = $value;
            }
        }
    }
    var_dump($localTemp);
    $data=array();
    $first=false;
    $firstYear=0;
    $categoriasPart=array();
    for($i=1900;$i<=date('Y');$i++){
        if(array_key_exists($i,$localTemp)){
            array_push($data,$localTemp[$i]);
            $first=true;
        }
        else{
            if($first){
                array_push($data,0);
            }
            else{
                $firstYear=$i+1;
            }
        }
    }
    for($i=$firstYear;$i<=date('Y');$i++){
        array_push($categoriasPart,$i);
    }
    $participacion=array('name'=>$institucion,'data'=>$data);
}
$parameters = array(
    'fq' => 'dwc.institutionCode_s:"'.$institucion.'"',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.stateProvince_s'
);
$parametersSpecies = array(
    'fq' => 'dwc.institutionCode_s:"'.$institucion.'"',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.order_s'
);
$results=getResults($parametersSpecies,$solr);
$totalEspecies=0;
$totalObservaciones=0;

if($results){
    $localTemp='<div class="tableElement"><div class="key">Ordenes</div><div class="value">Obs.</div></div>';
    foreach ($results->facet_counts->facet_fields as $doc) {
        foreach ($doc as $field => $value) {
            if($value>0){
                $localTemp.='<div class="tableElement"><div class="key">'.$field.'</div><div class="value">'.$value.'</div></div>';
                $totalEspecies++;
                $totalObservaciones+=$value;
            }
        }
    }
}
$localTemp.='<div class="totales">Ordenes: '.$totalEspecies.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total Observaciones: '.$totalObservaciones.'</div>';
$tablaEspecies=$localTemp;

$parametersGenus = array(
    'fq' => 'dwc.institutionCode_s:"'.$institucion.'"',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.class_s'
);
$results=getResults($parametersGenus,$solr);
$totalGeneros=0;
$totalGenerosObservados=0;

if($results){
    $localTemp='<div class="tableElement"><div class="key">Genero</div><div class="value">Obs.</div></div>';
    foreach ($results->facet_counts->facet_fields as $doc) {
        foreach ($doc as $field => $value) {
            if($value>0){
                $localTemp.='<div class="tableElement"><div class="key">'.$field.'</div><div class="value">'.$value.'</div></div>';
                $totalGeneros++;
                $totalGenerosObservados+=$value;
            }
        }
    }
}
$localTemp.='<div class="totales">Generos: '.$totalGeneros.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total Observaciones: '.$totalGenerosObservados.'</div>';
$tablaGeneros=$localTemp;

$parameters = array(
    'fq' => 'dwc.institutionCode_s:"'.$institucion.'"',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.identifiedBy_s',
    'facet.limit' => 1000000
);
$results=getResults($parameters,$solr);
$investigadores=array();
$especiesPorInvestigador=array();
$regPorInvestig=array();
$salida='<div class="tableElement"><div class="key">Investigador</div><div class="value">Registros</div></div>';
if($results){
    foreach ($results->facet_counts->facet_fields as $doc) {
        foreach ($doc as $field => $value) {
            if($value>0){
                array_push($investigadores,$field);
            }
        }
    }
    $nulos=0;
    $j=0;
    $cantidadEspecies=array();
    foreach($investigadores as $i){
        $parameters = array(
            'fq' => 'dwc.identifiedBy_mt:"'.$i.'" AND dwc.institutionCode_s:"'.$institucion.'"',
            'fl' => '',
            'facet' => 'true',
            'facet.field' => 'dwc.scientificName_s',
            'face.limit' => 1000000
        );
        $results=getResults($parameters,$solr);
        $localTemp=0;
        //Registros por investigador para esta Institucion
        foreach ($results->facet_counts->facet_fields as $doc) {
            foreach ($doc as $field => $value) {
                if($value>0){
                    //if(strcmp($field,'González')==0)echo 'entra';
                    $regPorInvestig[$i]+=$value;
                    $localTemp++;
                }
                if(strcmp($field,'_empty_')==0){
                    $nulos+=$value;
                }
            }
        }
        if($localTemp!=0)array_push($cantidadEspecies,$localTemp);
        //$regPorInvestig[$i].=' ['.$localTemp.' Esp.]'; //Especies por investigador para esta institución.
    }
    $localTemp='';
    $primero=0;
    $salida2='';
    $j=0;
    foreach($regPorInvestig as $field=>$value){
        if($value>0){
            if($primero==0)$primero=$value;
            $salida.='<div class="tableElement"><div class="key2">'.$field.'</div><div class="tableGraphElement" style="width:'.getWidth($primero,$value).'%"></div><div class="value">'.$value.' ['.$cantidadEspecies[$j].' Esp.]</div></div>';
            $j++;
            array_push($especiesPorInvestigador,array('name:'=>$field,'data:'=>array($value)));
        }
    }
    $nulos2='<div class="tableElement"><div class="key"><b>Registros sin nombre</b></div><div class="value"><b>'.$nulos.'</b></div></div>';
    $salida.=$nulos2;
}
var_dump($cantidadEspecies);
$results=false;

if($results){
    $localTemp=array();
    foreach ($results->facet_counts->facet_fields as $doc) {
        foreach ($doc as $field => $value) {
            if($value>0){
                echo '<br>'.obtenerRegion($field);
            }
        }
    }
    /*$data=array();
    $first=false;
    $firstYear=0;
    $categoriasPart=array();
    for($i=1900;$i<=date('Y');$i++){
        if(array_key_exists($i,$localTemp)){
            array_push($data,$localTemp[$i]);
            $first=true;
        }
        else{
            if($first){
                array_push($data,0);
            }
            else{
                $firstYear=$i+1;
            }
        }
    }
    for($i=$firstYear;$i<=date('Y');$i++){
        array_push($categoriasPart,$i);
    }
    $participacion=array('name'=>$institucion,'data'=>$data);*/
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
                    include(drupal_get_path('module', 'analizador_biodiversidad') . '/include/analizador_institucion.php');
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