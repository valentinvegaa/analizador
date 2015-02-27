<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 12-11-2014
 * Time: 0:21
 */
$path = drupal_get_path('module', 'analizador_biodiversidad');
$queryFilterWord = isset($_REQUEST['qw']) ? $_REQUEST['qw'] : false;
$additionalParameters = array(
    'fq' => '',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.institutionCode_s'
);
try {
    $results = $solr->search('*:*', 0, 0, $additionalParameters);
}catch (Exception $e)
{
    die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
}
?>
<div id="start-point"></div>
<div id="final-point"></div>
<form class="form-wrapper" accept-charset="utf-8" method="post">
    <input type="text" id="qw" name="qw" width="40" placeholder="Haga una busqueda nueva aquí" required>
    <input type="submit" value="Search" id="submit">
</form>
<script>
    (function ($) {
        Drupal.behaviors.yourThemeTabs = {
            attach: function (context, settings) {
                $("#tabs").tabs();
            }
        };
        Drupal.behaviors.analizador_biodiversidad={
            attach: function(context, settings){
                $(window).load(function(){
                    var trackLoad = 0;
                    var loading = false;
                    var q='<?php echo $queryFilterWord;?>';
                    var total=0;
                    var timesAgain=0;

                    //var total_groups = 100;
                    var functionToLoadMore=function() { //detect page scroll
                        if($(window).scrollTop() + $(window).height() == $(document).height())  //user scrolled to bottom of the page?
                        {
                            if(loading==false) //there's more data to load // trackLoad <= total_groups &&
                            {
                                loading = true;
                                $('.animation_image').show();
                                $.ajax({
                                    type: "POST",
                                    url: "sites/all/modules/analizador_biodiversidad/include/loadSearchResults.php",
                                    data: {'offset': trackLoad,'queryFilterWord': q, 'total':total},
                                    dataType: "html",
                                    timeout: 30000, // in milliseconds
                                    success: function(data) {
                                        if(data=='Fin de la busqueda.'){
                                            $('.animation_image').hide();
                                            $('.result-count').html(data);
                                            $(window).unbind('scroll',functionToLoadMore);
                                        }
                                        else{
                                            $("#results").append(data);
                                            $('.animation_image').hide();
                                            trackLoad=trackLoad+5;
                                            loading = false;
                                            total = $('#results').find('div.result').length;
                                            $('#numeroDeResultados').html($('#results').find('div.result').length);
                                        }
                                    },
                                    error: function(request, status, err) {
                                        if(status == "timeout") {
                                            $('.animation_image').hide();
                                            $(window).unbind('scroll',functionToLoadMore);
                                        }
                                    }
                                });
                            }
                        }
                    };

                    function firstLoad(){
                        if(timesAgain<5){
                            $('#results').load("sites/all/modules/analizador_biodiversidad/include/loadSearchResults.php",{'offset': trackLoad,'queryFilterWord': q, 'total':total},
                                function(data){
                                    if(data.length>0){
                                        if(data=='No se encontraron registros'){
                                            $('.animation_image').hide();
                                            $('.result-count').html('No se encontraron registros en Chile :(');
                                            $(window).unbind('scroll',functionToLoadMore);
                                        }
                                        else{
                                            trackLoad=trackLoad+5;
                                            total = $('#results').find('div.result').length;
                                            $('#numeroDeResultados').html($('#results').find('div.result').length);
                                            $(window).scroll(functionToLoadMore);
                                        }
                                    }
                                    else{
                                        timesAgain++;
                                        firstLoad();
                                    }
                                }
                            );
                        }
                        else{
                            $('.animation_image').hide();
                            $('.result-count').html('No se encontraron registros en Chile :(');
                            $(window).unbind('scroll',functionToLoadMore);
                        }
                    } //load first group // end load function
                    firstLoad();
                });
            }
        }
    })(jQuery);
</script>
<div>
    <?php
    $search=array();

    //fab88965-e69d-4491-a04d-e3198b626e52 NCI Taxonomy
    //d7dddbf4-2cf0-4f39-9b2a-bb099caae36c GBIF backbone Taxonomy
    //$searchGenus = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $queryFilterWord . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=GENUS'), true);
    //$searchFamily = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $queryFilterWord . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=FAMILY'), true);
    //$searchOrder = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $queryFilterWord . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=ORDER'), true);
    //$searchInstitutions = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $queryFilterWord . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=FAMILY&limit=300'), true);
    //print
    //var_dump($_SERVER);
    print '<div id="tabs"><ul>';
    print ' <li><a href="#tabs-1"><div class="title">Resultados</div></a></li>';
    print ' <li><a href="#tabs-2"><div class="title">Instituciones</div></a></li>';
    print '</ul>';
    print '<div id="tabs-1">';
    print '<div class="subtitle">Aquí puede encontrar una lista de los resultados asociadas a su busqueda ('.$queryFilterWord.')</div>';
    print '<div class="result-count"><span>Se han encontrado <span id="numeroDeResultados">0</span> resultados</span></div>';
    print '
        <div id="results"></div>
        <div class="animation_image" align="center"><img src="sites/all/modules/analizador_biodiversidad/images/ajax-loader.gif">Recuperando resultados, por favor, espere.</div>';
    print '</div>';//div fin tabs-1
    print '<div id="tabs-2">';//inicio tabs-2
    if ($results) {
        $totalInstitutions = $results->response->numFound;
        $j=1;
        foreach ($results->facet_counts->facet_fields as $doc) {
            foreach ($doc as $field => $value) {
                print '<div class="result">';
                print '<div class="scientificName">';
                print '<span>'.$j.') </span>';
                print '<a href="http://www.ecoinformatica.cl/site/analizador/institutions/' .$field. '">' . $field . '</a>';
                print '</div></div>';
                $j++;
            }
        }
    }
    print '</div>';//div fin tabs-2
    ?>
</div>