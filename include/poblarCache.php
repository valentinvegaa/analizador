<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 21-01-2015
 * Time: 17:10
 */

require_once($path . '/home/ecoinfor/public_html/site/sites/all/modules/analizador_biodiversidad/Apache/Solr/Service.php');
require_once($path . '/home/ecoinfor/public_html/site/sites/all/modules/analizador_biodiversidad/include/solrConnection.php');


$solr = new Apache_Solr_Service("$USR:$PSWD@$HOST", 80, $SOLRPATH);
$query = "*:*";

/*__arrays__*/
$arraySpecies=array();
$arrayGenus=array();
$arrayFamily=array();
$arrayOrder=array();
$arrayPhylum=array();
$arrayKingdom=array();


/*__parametros para Species__*/
$additionalParametersSpecies = array(
    'fq' => '',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.scientificName_s',//dwc.scientificName_mt
    'facet.limit'=>10000
);

/*__parametros para Genus__*/
$additionalParametersGenus = array(
    'fq' => '',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.genus_s',
    'facet.limit'=>10000
);
/*------------------------*/


/*__parametros para Family__*/
$additionalParametersFamily = array(
    'fq' => '',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.family_s',
    'facet.limit'=>10000
);
/*------------------------*/

/*__parametros para Order__*/
$additionalParametersOrder = array(
    'fq' => '',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.order_s'
);
/*------------------------*/

/*__parametros para Phylum__*/
$additionalParametersPhylum = array(
    'fq' => '',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.phylum_s'
);
/*------------------------*/

/*__parametros para Kingdom__*/
$additionalParametersKingdom = array(
    'fq' => '',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.kingdom_s'
);
/*------------------------*/

/*__Try Species__*/
try {
    $resultsSpecies = $solr->search($query, 0, 0, $additionalParametersSpecies);
}
catch (Exception $e)
{
    die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
}


/*__Try Genus__*/
try {
        $resultsGenus = $solr->search($query, 0, 0, $additionalParametersGenus);
    }
    catch (Exception $e)
    {
        die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
    }


/*__Try Family__*/
try {
    $resultsFamily = $solr->search($query, 0, 0, $additionalParametersFamily);
}
catch (Exception $e)
{
    die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
}

/*__Try Order__*/
try {
    $resultsOrder = $solr->search($query, 0, 0, $additionalParametersOrder);
}
catch (Exception $e)
{
    die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
}

/*__Try Phylum__*/
try {
    $resultsPhylum = $solr->search($query, 0, 0, $additionalParametersPhylum);
}
catch (Exception $e)
{
    die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
}

/*__Try Kingdom__*/
try {
    $resultsKingdom = $solr->search($query, 0, 0, $additionalParametersKingdom);
}
catch (Exception $e)
{
    die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
}


/*__Resultados Species__*/
if($resultsSpecies){
    $arrayOrdenado=array();
    $arrayListo=array();
    $i=0;
    //foreach ($resultsSpecies->response->docs as $doc) {
    foreach($resultsSpecies->facet_counts->facet_fields as $doc){
        foreach($doc as $field => $value){
            if(!in_array($field,$arraySpecies)){
                $arraySpecies[$i]=$field;
                $arrayOrdenado=explode(' ',$arraySpecies[$i]);
                $arrayListo[$i]=$arrayOrdenado[0].' '.$arrayOrdenado[1];
                $i+=1;
            }
        }
    }
   //$url='http://www.ecoinformatica.cl/site/analizador/species/Aeneator loisae';//.$arrayListo[1];
    //$page=file_get_contents($url);
    print_r($arrayListo);
    //print_r($page);
}

/*__Resultados Genus__*/
if($resultsGenus){
    $i=0;
    foreach($resultsGenus->facet_counts->facet_fields as $doc){

        foreach($doc as $field => $value){
                    if(!in_array($field,$arrayGenus)){
                        $arrayGenus[$i]=$field;
                        $i+=1;
                    }
        }
    }
    //$url='http://www.ecoinformatica.cl/site/analizador/genus/'.$arrayGenus[10];
    //$page=file_get_contents($url);
    print_r($arrayGenus);
    //print_r($page);

}

/*__Resultados Family__*/
if($resultsFamily){
    $i=0;
    foreach($resultsFamily->facet_counts->facet_fields as $doc){
        foreach($doc as $field => $value){
            if(!in_array($field,$arrayFamily)){
                $arrayFamily[$i]=$field;
                $i+=1;
            }
        }
    }
    print_r($arrayFamily);
}

/*__Resultados Order__*/
if($resultsOrder){
    $i=0;
    foreach($resultsOrder->facet_counts->facet_fields as $doc){
        foreach($doc as $field => $value){
            if(!in_array($field,$arrayOrder)){
                $arrayOrder[$i]=$field;
                $i+=1;
            }
        }
    }
    print_r($arrayOrder);
}

/*__Resultados Phylum__*/
if($resultsPhylum){
    $i=0;
    foreach($resultsPhylum->facet_counts->facet_fields as $doc){
        foreach($doc as $field => $value){
            if(!in_array($field,$arrayPhylum)){
                $arrayPhylum[$i]=$field;
                $i+=1;
            }
        }
    }
    print_r($arrayPhylum);
}

/*__Resultados Kingdom__*/
if($resultsKingdom){
    $i=0;
    foreach($resultsKingdom->facet_counts->facet_fields as $doc){
        foreach($doc as $field => $value){
            if(!in_array($field,$arrayKingdom)){
                $arrayKingdom[$i]=$field;
                $i+=1;
            }
        }
    }
    print_r($arrayKingdom);
}
/*__carga de cache mediante file_get_contents__*/

/*foreach($arrayListo as $key => $value){
    //$url='http://www.ecoinformatica.cl/site/analizador/species/'.$value;
    //$page=file_get_contents($url);
}*/
foreach($arrayGenus as $key => $value){
    //$url='http://www.ecoinformatica.cl/site/analizador/genus/'.$value;
    //$page=file_get_contents($url);
}

foreach($arrayFamily as $key => $value){
    //$url='http://www.ecoinformatica.cl/site/analizador/family/'.$value;
    //$page=file_get_contents($url);
}
foreach($arrayOrder as $key => $value){
    //$url='http://www.ecoinformatica.cl/site/analizador/order/'.$value;
    //$page=file_get_contents($url);
}
foreach($arrayPhylum as $key => $value){
    //$url='http://www.ecoinformatica.cl/site/analizador/phylum/'.$value;
    //$page=file_get_contents($url);
}
foreach($arrayKingdom as $key => $value){
    //$url='http://www.ecoinformatica.cl/site/analizador/kingdom/'.$value;
    //$page=file_get_contents($url);
}