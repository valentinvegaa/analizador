<?php
function analizador_callback()
{
    header('Content-Type: text/html; charset=utf-8');
    $limit = 10000;
    //$query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
    //$queryFilterWord="";
    //$query="RELS_EXT_hasModel_uri_ms:[* TO *]";
    //$query="*:*";
    $results = false;
    $coordinatesInPHP = "";
    $queryFilterWord = isset($_REQUEST['qw']) ? $_REQUEST['qw'] : false;

    if ($queryFilterWord) {
        require_once('Apache/Solr/Service.php');
        $query = "RELS_EXT_hasModel_uri_ms:\"info:fedora/biodiversity:biodiversityCModel\"";
        $solr = new Apache_Solr_Service('biosolr:Biodiversid@d2013Reuna@bio-nl01-apl.reuna.cl', 8080, '/solr/');
        if (get_magic_quotes_gpc() == 1) {
            $query = stripslashes($query);
        }
        $additionalParameters2 = array(
            //'fq' => 'dwc.scientificName_s:"Lottia orbigny (Dall, 1909)"',
            'fq' => 'dc.subject:*' . $queryFilterWord . '*',
            'fl' => 'dwc.latlong_p',
        );
        //$query="chino";

        try {
            //$results = $solr->search($query, 0, $limit, $additionalParameters2);
            $results = $solr->search($query, 0, $limit, $additionalParameters2);
        } catch (Exception $e) {
            die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
        }
        if ($results) {
            foreach ($results->response->docs as $doc) {
                foreach ($doc as $field => $value) {
                    $coordinatesInPHP .= htmlspecialchars($value, ENT_NOQUOTES, 'utf-8') . ",";
                }
            }
        }
        $url_species = "http://api.gbif.org/v1/species/match?name=Echinolittorina+peruviana";
        //$url_species="http://api.gbif.org/v1/species/suggest?q=Echinolitto+per";//SUGGEST!!!
        $content = file_get_contents($url_species);
        $json = json_decode($content, true);
        $speciesKey = json_encode($json['speciesKey']);
        //echo "<pre>".json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";

        $url = "http://api.gbif.org/v1/occurrence/search?taxonKey=$speciesKey&HAS_COORDINATE=true&country=CL&limit=1";
        $content = file_get_contents($url);
        $json = json_decode($content, true);
        $offset = 0;
        $count = $json['count'];
        $coordinatesGBIFInPHP = "";
        if ($count > 300) {
            while ($count > 0) {
                $url = "http://api.gbif.org/v1/occurrence/search?taxonKey=$speciesKey&HAS_COORDINATE=true&country=CL&limit=$count&offset=$offset";
                $content = file_get_contents($url);
                $json = json_decode($content, true);
                //echo "<pre>".json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
                foreach ($json['results'] as $i) {
                    $coordinatesGBIFInPHP .= $i['decimalLongitude'] . "," . $i['decimalLatitude'] . ",";
                }
                $count -= 300;
                $offset += 300;
            }
        } else {
            $url = "http://api.gbif.org/v1/occurrence/search?taxonKey=$speciesKey&HAS_COORDINATE=true&country=CL&limit=$count&offset=$offset";
            $content = file_get_contents($url);
            $json = json_decode($content, true);
            //echo "<pre>".json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
            foreach ($json['results'] as $i) {
                $coordinatesGBIFInPHP .= $i['decimalLongitude'] . "," . $i['decimalLatitude'] . ",";
            }
        }
    }

    $content['overlay'] = array(
        '#type' => 'markup',
        '#markup' => '<div id="mapContainer" class="mapContainer">Reuna</div><div id="mapContainerGBIF" class="mapContainerGBIF">GBIF</div>',
        '#prefix' => '<div id="containers">',
        '#sufix' => '</div>',
        '#attached' => array(
            'css' => array(
                drupal_get_path('module', 'analizador_biodiversidad') . '/include/analizador.css',
            ),
        ),
    );
    $content['overlay']['#attached']['js'] = array(
        'http://openlayers.org/en/v3.0.0/build/ol.js' => array(
            'type' => 'external'
        ),
        drupal_get_path('module', 'analizador_biodiversidad') . '/include/script_analizador.js' => array(
            'type' => 'file',
        ),
    );

    return $content;
}