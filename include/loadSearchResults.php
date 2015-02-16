<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 04-02-2015
 * Time: 23:20
 */
include('solrConnection.php');
include('../Apache/Solr/Service.php');
$solr = new Apache_Solr_Service("$USR:$PSWD@$HOST", 80, $SOLRPATH);
function countReunaOccurences($name, $taxa, $solr){
    $additionalParameters = array(
        'fq' => $taxa.':"'.$name.'"',
        'fl' => ''
    );
    try {
        $results = $solr->search('*:*', 0, 0, $additionalParameters);
    }catch (Exception $e)
    {
        die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
    }
    return $results->response->numFound;
}
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
function makeTaxaHierarchy($i){
    $phylum=isset($i['phylum'])?$i['phylum']:'';
    $order=isset($i['order'])?$i['order']:'';
    $family=isset($i['family'])?$i['family']:'';
    $genus=isset($i['genus'])?$i['genus']:'';
    $result='';
    if($phylum!=''){
        $result.=''.$phylum.'';
    }
    if($order!=''){
        //$result.=' > <a href="http://www.ecoinformatica.cl/site/analizador/order/'.$order.'">'.$order.'</a>';
        $result.=''.$order.'';
    }
    if($family!=''){
        $result.=' > <a href="http://www.ecoinformatica.cl/site/analizador/family/'.$family.'">'.$family.'</a>';
    }
    if($genus!=''){
        $result.=' > <a href="http://www.ecoinformatica.cl/site/analizador/genus/'.$genus.'">'.$genus.'</a>';
    }
    return $result;
};
function traducir($word){
    $palabra='';
    switch($word){
        case 'SPECIES':
            $palabra='Especie';
            break;
        case 'GENUS':
            $palabra='Genero';
            break;
        case 'FAMILY':
            $palabra='Familia';
            break;
        case 'ORDER':
            $palabra='Orden';
            break;
        case 'PHYLUM':
            $palabra='Filo';
            break;
        case 'VARIETY':
            $palabra='Variedad';
            break;
        case 'SUBSPECIES':
            $palabra='Subespecie';
            break;
    }
    return $palabra;
}
function suma($i,$j){return $i+$j;}
function getSolrField($rank){
    $field='';
    switch($rank){
        case 'SPECIES':
            $field='dwc.scientificName_mt';
            break;
        case 'GENUS':
            $field='dwc.genus_mt';
            break;
        case 'FAMILY':
            $field='dwc.family_mt';
            break;
        case 'ORDER':
            $field='"dwc.family_mt"';
            break;
        case 'PHYLUM':
            $field='"dwc.family_mt"';
            break;
        case 'VARIETY':
            $field='"dwc.family_mt"';
            break;
        case 'SUBSPECIES':
            $field='"dwc.family_mt"';
            break;
    }
    return $field;
}
function constructResult($searchResults,$solr,$number){
    $cleanResults='';
    $field='';
    $taxaUrl='';
    $rank='';
    $autor='';
    $jerarquia='';
    $vernaculares='';
    //if($searchResults['endOfRecords']!=true)
    if(!$searchResults['endOfRecords'])
    foreach ($searchResults['results'] as $i) {
        if(strcmp($i['parent'],'Unclassified')!=0&&strcmp($i['rank'],'ORDER')!=0&&strcmp($i['rank'],'PHYLUM')!=0&&strcmp($i['rank'],'VARIETY')!=0&&strcmp($i['rank'],'SUBSPECIES')!=0){
            $field=getSolrField($i['rank']);
            $taxaUrl=strtolower($i['rank']);
            $rank=traducir($i['rank']);
            $autor=$i['authorship'];
            $jerarquia=makeTaxaHierarchy($i);
            $vernaculares=getVernaculars($i['key']);

            $reunaCount=countReunaOccurences($i['canonicalName'],$field,$solr);
            $url='http://api.gbif.org/v1/occurrence/count?taxonKey='.$i['key'].'&isGeoreferenced=true&country=CL';
            $count=json_decode(file_get_contents($url),true);

            if($reunaCount>0||$count>0){
                $number++;

                $cleanResults.= '<div class="result" id="'.($reunaCount+$count).'">';
                $cleanResults.= '<div class="scientificName">';
                $cleanResults.= '<span>'.$number.') </span>'; // aqui va el numero de la aparicion
                $cleanResults.= '<a href="http://www.ecoinformatica.cl/site/analizador/'.$taxaUrl.'/' .$i['canonicalName']. '">' . $i['canonicalName'].'</a> '.$autor .'<div class="resultCount">[Registros] Reuna: '.$reunaCount.' Gbif: '.$count.'</div>';//URL
                $cleanResults.= '</div>';//div fin scientificName
                $cleanResults.= '<div class="moreInfo">
                    <div class="rank">Tipo: '.$rank.'</div>
                    <div class="autor">Autor: '.$autor.'</div>
                    <div class="taxa-hierarchy">Jerarquia taxonómica: '.$jerarquia.'</div>
                    <div class="vernacular">Nombres comunes: '.$vernaculares.'</div>
            </div>';
                $cleanResults.= '</div>';//div fin result
            }
            else{}
        }
    }
    else $cleanResults='fin de los registros';
    return $cleanResults;
}
function searchAgain($queryFilterWord,$offset,$solr,$total){
    $offset+=5;
    $search = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $queryFilterWord . '&dataset_key=d7dddbf4-2cf0-4f39-9b2a-bb099caae36c&nameType=WELLFORMED&nameType=SCINAME&limit=5&offset='.$offset), true);
    return constructResult($search,$solr,$total);
}
if($_POST){
    $queryFilterWord = str_replace(' ', '+', $_POST['queryFilterWord']);
    $offset = filter_var($_POST["offset"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    if(!is_numeric($offset)){
        header('HTTP/1.1 500 Número invalido!');
        exit();
    }
    $timesAgain=0;
    $search = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $queryFilterWord . '&dataset_key=d7dddbf4-2cf0-4f39-9b2a-bb099caae36c&nameType=WELLFORMED&nameType=SCINAME&limit=5&offset='.$offset), true);
    //Llamar funcion para generar salida
    $result=constructResult($search,$solr,$_POST['total']);

    while(strlen($result)==0){
        $timesAgain++;
        $result=searchAgain($queryFilterWord,$offset,$solr,$_POST['total']);
        if($timesAgain==5)$result='No se encontraron registros';
    }
    if(strcmp($result,'fin de los registros')==0)$result='Fin de la busqueda.';
    print $result;
}
?>