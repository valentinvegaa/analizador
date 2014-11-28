<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 12-11-2014
 * Time: 0:21
 */
?>
<script>
    (function ($) {
        Drupal.behaviors.yourThemeTabs = {
            attach: function (context, settings) {
                $("#tabs").tabs();
            }
        };
    })(jQuery);
</script>
<div>
    <?php
    function getVernaculars($key){
        $vernaculars = json_decode(file_get_contents('http://api.gbif.org/v1/species/'.$key.'/vernacularNames'), true);
        $return='';
        foreach($vernaculars['results'] as $i){
            //$return[$i['sourceTaxonKey']]=$i['vernacularName'];
            $return.=$i['vernacularName'].', ';
        }
        $return=substr($return,0,sizeof($return)-3);
        return $return;
    }
    function getSynonims(){}
    $queryFilterWord = str_replace(' ', '+', $queryFilterWord);

    $searchSpecies = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $queryFilterWord . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=SPECIES&limit=300'), true);
    //fab88965-e69d-4491-a04d-e3198b626e52 NCI Taxonomy
    //d7dddbf4-2cf0-4f39-9b2a-bb099caae36c GBIF backbone Taxonomy

    $searchGenus = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $queryFilterWord . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=GENUS&limit=300'), true);
    $searchFamily = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $queryFilterWord . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=FAMILY&limit=300'), true);
    //$searchInstitutions = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $queryFilterWord . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=FAMILY&limit=300'), true);
    print '<div id="tabs"><ul>';
    count($searchSpecies['results'])>0?print ' <li><a href="#tabs-1"><div class="title">Species</div></a></li>':print '';
    count($searchGenus['results'])>0?print ' <li><a href="#tabs-2"><div class="title">Genus</div></a></li>':print '';
    count($searchFamily['results'])>0?print ' <li><a href="#tabs-3"><div class="title">Family</div></a></li>':print '';
    //count($searchInstitutions['results'])>0?print ' <li><a href="#tabs-4"><div class="title">Instituciones</div></a></li>':print '';
    print '</ul>';
    print '<div id="tabs-1">';
    count($searchSpecies['results'])>0?print '<div class="subtitle">Aquí puede encontrar una lista de las especies asociadas a su busqueda ('.$queryFilterWord.')</div>':print '';
    foreach ($searchSpecies['results'] as $i) {
        print '<div class="result">';
        print '<div class="scientificName"><a href="http://www.ecoinformatica.cl/site/analizador/species/' . $i['scientificName'] . '">' . $i['scientificName'] . '</a></div>';
        print '<div class="moreInfo">
                    <div class="vernacular">Common names: '.getVernaculars($i['key']).'</div>
            </div>';
        print '</div>';

        //dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&highertaxon_key=106605002&rank=GENUS
        //&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=SPECIES&highertaxon_key=106605002
    }
    print '</div>';
    print '<div id="tabs-2">';
    count($searchGenus['results'])>0?print '<div class="subtitle">Aquí puede encontrar una lista de los generos asociadas a su busqueda ('.$queryFilterWord.')</div>':print '';
    foreach ($searchGenus['results'] as $i) {
        print '<div class="result">';
        print '<div class="scientificName"><a href="http://www.ecoinformatica.cl/site/analizador/genus/' . $i['scientificName'] . '">' . $i['scientificName'] . '</a></div>';
        print '</div>';

        //dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&highertaxon_key=106605002&rank=GENUS
        //&rank=SPECIES&highertaxon_key=106605002
    }
    print '</div>';
    print '<div id="tabs-3">';
    count($searchFamily['results'])>0?print '<div class="subtitle">Aquí puede encontrar una lista de las familias asociadas a su busqueda ('.$queryFilterWord.')</div>':print '';
    foreach ($searchFamily['results'] as $i) {
        print '<div class="result">';
        print '<div class="scientificName"><a href="http://www.ecoinformatica.cl/site/analizador/family/' . $i['scientificName'] . '">' . $i['scientificName'] . '</a></div>';
        print '</div>';

        //dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&highertaxon_key=106605002&rank=GENUS
        //&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=SPECIES&highertaxon_key=106605002
    }
    print '</div>';

    print '<div id="tabs-4">';
    if(count($searchFamily['results'])>10000){
        count($searchFamily['results'])>0?print '<div class="subtitle">Aquí puede encontrar una lista de las isntituciones asociadas a su busqueda ('.$queryFilterWord.')</div>':print '';
        print '<div class="result">';
        print '<div class="scientificName">En construcción</div>';
        print '</div>';
    }

    /*
     * Busqueda de instituciones en construccion
     */

    /*foreach ($searchFamily['results'] as $i) {
        print '<div class="result">';
        print '<div class="scientificName">' . $i['scientificName'] . '</div>';
        print '<div class="url"><a href="http://www.gbif.org/species/' . $i['key'] . '">' . $i['scientificName'] . '</a></div>';
        print '<div class="url"><a href="http://www.gbif.org/species/' . $i['key'] . '">' . $i['scientificName'] . '</a></div>';
        print '</div>';

        //dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&highertaxon_key=106605002&rank=GENUS
        //&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=SPECIES&highertaxon_key=106605002
    }*/
    print '</div>';
    ?>
</div>