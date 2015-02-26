<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 01-12-2014
 * Time: 11:02
 */
class Family{
    private $hierarchy='';//jerarquia taxonomica
    private $nameSpecieAuthor;
    private $familyName='';
    private $generos=array();//
    private $especies=array();//
    private $cantidadGeneros=0;//
    private $cantidadEspecies=0;//
    private $regionsCoordinatesReuna=array();
    private $regionsCoordinatesGbif=array();
    private $coordinatesReuna=array();
    private $coordinatesGbif=array();
    private $drillDownDataReuna=array();
    private $drillDownDataGbif=array();
    private $stackedChildrensReuna=array();
    private $stackedChildrensGbif=array();
    private $institutionDataReuna=array();
    private $institutionDataGbif=array();
    private $yearCountReuna=array();
    private $yearCountGbif=array();
    private $totalReuna=0;
    private $totalInGbif=0;
    private $totalReunaWithCoordinates=0;
    private $totalGbifWithCoordinates=0;
    private $institutionNamesReuna=array();
    private $institutionNamesGbif=array();
    private $countSpecies=0;
    private $speciesFound=array();
    private $coordYearsGbif='';
    private $coordYearsReuna='';
    private $familyKey;
    private $categories=array();
    public function setInstitutionInfo($institutionInfo){
        $this->institutionInfo=$institutionInfo;
    }
    public function setFamily(
        $familyName,
        $generos,
        $especies,
        $cantidadGeneros,
        $cantidadEspecies,
        $coordinatesReuna,
        $coordYearsReuna,
        $coordinatesGbif,
        $drillDownDataReuna,
        $drillDownDataGbif,
        $stackedChildrensReuna,
        $stackedChildrensGbif,
        $institutionDataReuna,
        $institutionDataGbif,
        $yearCountReuna,
        $yearCountGbif,
        $totalReuna,
        $totalReunaWithCoordinates,
        $totalGbifWithCoordinates,
        $institutionNamesReuna,
        $institutionNamesGbif,
        $countSpecies,
        $speciesFound,
        $coordYearsGbif,
        $totalInGbif,
        $familyKey,
        $categories,
        $regionsCoordinatesReuna,
        $regionsCoordinatesGbif,
        $hierarchy,
        $nameSpecieAuthor
    ){
        $this->familyName=$familyName;
        $this->generos=$generos;
        $this->especies=$especies;
        $this->cantidadGeneros=$cantidadGeneros;
        $this->cantidadEspecies=$cantidadEspecies;
        $this->coordinatesReuna=$coordinatesReuna;
        $this->coordYearsReuna=$coordYearsReuna;
        $this->coordinatesGbif=$coordinatesGbif;
        $this->drillDownDataReuna=$drillDownDataReuna;
        $this->drillDownDataGbif=$drillDownDataGbif;
        $this->stackedChildrensReuna=$stackedChildrensReuna;
        $this->stackedChildrensGbif=$stackedChildrensGbif;
        $this->institutionDataReuna=$institutionDataReuna;
        $this->institutionDataGbif=$institutionDataGbif;
        $this->yearCountReuna=$yearCountReuna;
        $this->yearCountGbif=$yearCountGbif;
        $this->totalReuna=$totalReuna;
        $this->totalReunaWithCoordinates=$totalReunaWithCoordinates;
        $this->totalGbifWithCoordinates=$totalGbifWithCoordinates;
        $this->institutionNamesReuna=$institutionNamesReuna;
        $this->institutionNamesGbif=$institutionNamesGbif;
        $this->countSpecies=$countSpecies;
        $this->speciesFound=$speciesFound;
        $this->coordYearsGbif=$coordYearsGbif;
        $this->totalInGbif=$totalInGbif;
        $this->familyKey=$familyKey;
        $this->categories=$categories;
        $this->regionsCoordinatesReuna=$regionsCoordinatesReuna;
        $this->regionsCoordinatesGbif=$regionsCoordinatesGbif;
        $this->hierarchy=$hierarchy;
        $this->nameSpecieAuthor=$nameSpecieAuthor;
    }
    public function getGeneros(){}
    public function getEspecies(){}

    public function getInstitutionData($rog){//Reuna Or Gbif
        return (strcmp('reuna',$rog))!=0?$this->institutionDataGbif:$this->institutionDataReuna;
    }
    public function getYearCount($rog){
        return (strcmp('reuna',$rog))!=0?$this->yearCountGbif:$this->yearCountReuna;
    }
    public function getCoordinatesReuna(){
        return $this->coordinatesReuna;
    }
    public function getCoordYearsReuna(){
        return $this->coordYearsReuna;
    }
    public function getCoordinatesGbif(){
        return $this->coordinatesGbif;
    }
    public function getDrillDownDataReuna(){
        return $this->drillDownDataReuna;
    }
    public function getDrillDownDataGbif(){
        return $this->drillDownDataGbif;
    }
    public function getStackedChildrens(){
        return $this->stackedChildrensReuna;
    }
    public function getStackedChildrensGbif(){
        return $this->stackedChildrensGbif;
    }
    public function getTotalReuna(){
        return $this->totalReuna;
    }
    public function getTotalReunaWithCoordinates(){
        return $this->totalReunaWithCoordinates;
    }
    public function getTotalGbifWithCoordinates(){
        return $this->totalGBIF;
    }
    public function getInstitutionNamesReuna(){
        return $this->institutionNamesReuna;
    }
    public function getInstitutionNamesGbif(){
        return $this->institutionNamesGbif;
    }
    public function getCountSpecies(){
        return $this->countSpecies;
    }
    public function getSpeciesFound(){
        return $this->speciesFound;
    }
    public function getCoordYearsGbif(){
        return $this->coordYearsGbif;
    }
    public function getTotalInGbif(){
        return $this->totalInGbif;
    }
    public function getFamilyKey(){
        return $this->familyKey;
    }
    public function getCategories(){
        return $this->categories;
    }
    public function getRegionsCoordinates($rog){
        return (strcmp('reuna',$rog))!=0?$this->regionsCoordinatesReuna:$this->regionsCoordinatesGbif;
    }
    public function getHierarchy(){
        return $this->hierarchy;
    }
    public function getNameSpecieAuthor(){
        return $this->nameSpecieAuthor;
    }
    public function getInstitutionInfo(){
        return $this->institutionInfo;
    }

}
$queryFilterWord = isset($_REQUEST['qw']) ? $_REQUEST['qw'] : false;
$path = drupal_get_path('module', 'analizador_biodiversidad');
include($path . '/include/solrConnection.php');
include($path . '/Apache/Solr/Service.php');
function getOrgArray($instInfo,$name){
    foreach($instInfo as $i){
        if(strcmp($i['title'],$name)==0){
            return $i;
        }
        else{}
    }
}
function setInvestigatorSingPlu($var){
    $tamano=count($var);
    if($tamano==0 or $tamano==1){
        return 'investigador ha';}
    else{
        return 'investigadores han';
    }
}
function setRegistrosSingPlu($var){
    if($var==0 or $var==1)
    {return 'registro en ';}
    else{ return 'registros en ';}
}
function makeTaxaHierarchy($i){
    isset($i['kingdom'])?$kingdom=json_encode($i['kingdom']):$kingdom='';
    isset($i['phylum'])?$phylum=json_encode($i['phylum']):$phylum='';
    isset($i['class'])?$class=json_encode($i['class']):$class='';
    isset($i['order'])?$order=json_encode($i['order']):$order='';
    isset($i['family'])?$family=json_encode($i['family']):$family='';
    isset($i['genus'])?$genus=json_encode($i['genus']):$genus='';
    $result='';
    if($kingdom!=''){
        $result.=''.$kingdom.' > ';
    }
    if($phylum!=''){
        $result.=''.$phylum.' > ';
    }
    if($class!=''){
        $result.=''.$class.' > ';
    }
    if($order!=''){
        $result.=''.$order.' > ';
    }
    if($family!=''){
        $result.=' '.'<a href="http://www.ecoinformatica.cl/site/analizador/family/'.$family.'" > '.$family.'</a>';
    }
    if($genus!=''){
        $result.=' >'.'<a href="http://www.ecoinformatica.cl/site/analizador/genus/'.$genus.'" > '.$genus.'</a>';
    }
    return $result;
}
function getCountyName($coords,$r){
    $coordsWithCounty=array();
    foreach($coords as $coord){
        if(strcmp($r,'reuna')==0){
            $url='http://www.mapquestapi.com/geocoding/v1/reverse?key=Fmjtd|luu8216anl%2Crw%3Do5-947wdr&location='.floatval($coord[0]).','.floatval($coord[1]);
            $response=file_get_contents($url);
            $json=json_decode($response);
            array_push($coordsWithCounty,array($coord,$json->results[0]->locations[0]->adminArea3));
        }
        else{
            $url='http://www.mapquestapi.com/geocoding/v1/reverse?key=Fmjtd|luu8216anl%2Crw%3Do5-947wdr&location='.$coord[1].','.$coord[0];
            $response=file_get_contents($url);
            $json=json_decode($response);
            array_push($coordsWithCounty,array($coord,$json->results[0]->locations[0]->adminArea3));
        }
    }
    return $coordsWithCounty;
}
function setYearSingPluReuna($var){
    $tamano=sizeof($var);
    reset($var);
    if($tamano==2 and key($var)=="" ){
        $tamano-=1;
    }
    else{
        if(key($var)==""){
            $tamano-=1;
        }
    }
    if($tamano==0 or $tamano==1){
        return 'año con registro en ';}
    else{
        return 'años con registros en ';
    }
}
function setYearSingPluGbif($var){
    $tamano=sizeof($var);
    reset($var);
    if($tamano==0 or $tamano==1){
        return 'año con registro en ';}
    else{
        return 'años con registros en ';
    }
}
function setOrganizationSingPlu($var){
    $tamano=sizeof($var);
    if($tamano==0 or $tamano==1){
        return 'organización ha';}
    else{
        return 'organizaciones han';
    }
}
function getCountYears($taxonKey, $count)
{
    $returnVal = array();
    $offset = 0;
    $localCount = $count;
    if ($localCount > 300) {
        while ($localCount > 0) {
            $years = json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/search?taxonKey=' . $taxonKey . '&HAS_COORDINATE=true&country=CL&limit=' . $localCount . '&offset=' . $offset. '&year=1900,2015'), true);
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
        $years = json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/search?taxonKey=' . $taxonKey . '&HAS_COORDINATE=true&country=CL&limit=' . $localCount . '&offset=' . $offset. '&year=1900,2015'), true);
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
    $orgData=array();
    foreach ($organizations as $key => $i) {
        $json = json_decode(file_get_contents('http://api.gbif.org/v1/organization/' . $key), true);
        $result[$json['title']] = $i;
        $localTemp=array(
            'title'=>$json['title'],
            'page'=>$json['homepage'],
            'addr'=>$json['address']
        );
        $contacts=array();
        foreach($json['contacts'] as $j){
            array_push($contacts,array(
                'name'=>$j['lastName'].' '.$j['firstName'],
                'email'=>$j['email'],
                'phone'=>$j['phone']
            ));
        }
        array_push($localTemp,$contacts);
        array_push($orgData,$localTemp);
    }
    $finalResult=array($result,$orgData);
    return $finalResult;
}
function getFamilyGenus($key){//obtiene la cantidad de observaciones de cada genero asociado a la familia $key
    //d7dddbf4-2cf0-4f39-9b2a-bb099caae36c
    //fab88965-e69d-4491-a04d-e3198b626e52
    $children = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?dataset_key=d7dddbf4-2cf0-4f39-9b2a-bb099caae36c&rank=GENUS&limit=300&highertaxon_key='.$key), true);//106311492
    $result=array();
    $genusCount=$children['count'];
    $offset=0;
    $genus=array();
    if($genusCount>300){
        while($genusCount>0){
            foreach($children['results'] as $i){
                array_push($genus,array($i['genus']=>$i['key']));
            };
            $genusCount-=300;
            $offset+=300;
            $children = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?dataset_key=d7dddbf4-2cf0-4f39-9b2a-bb099caae36c&rank=GENUS&limit=300&offset='.$offset.'&highertaxon_key='.$key), true);
        }
    }
    else{
        foreach($children['results'] as $i){
            array_push($genus,array($i['genus'],$i['key']));
        };
    }
    $count=array();
    $i=0;
    foreach($genus as $value){
        foreach($value as $key=>$value){
            $localTemp=json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/search?scientificName='.$key.'&hasCoordinate=true&limit=0&country=CL'), true);//106311492
            if(array_key_exists('count',$localTemp)&&$localTemp['count']>0){
                $count[$key]=$localTemp['count'];
            }
        }
    }
    arsort($count);
    return $count;
}
function getFamilyChildrens($key){

    $children = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?dataset_key=d7dddbf4-2cf0-4f39-9b2a-bb099caae36c&rank=GENUS&limit=300&highertaxon_key='.$key), true);//106311492
    $result=array();
    $genusCount=$children['count'];
    $offset=0;
    $genus=array();
    if($genusCount>300){
        while($genusCount>0){
            foreach($children['results'] as $i){
                array_push($genus,array($i['genus']=>$i['key']));
            };
            $genusCount-=300;
            $offset+=300;
            $children = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?dataset_key=d7dddbf4-2cf0-4f39-9b2a-bb099caae36c&rank=GENUS&limit=300&offset='.$offset.'&highertaxon_key='.$key), true);
        }
    }
    else{
        foreach($children['results'] as $i){
            array_push($genus,array($i['genus'],$i['key']));
        };
    }
    $result=array();
    foreach($genus as $key=>$value) {
        $children = json_decode(file_get_contents('http://api.gbif.org/v1/species/' . $value . '/children/?limit=300'), true);//106311492

        foreach ($children['results'] as $i) {
            $count = json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/count?taxonKey=' . $i['nubKey'] . '&country=CL&isGeoreferenced=true'), true);
            if ($count > 0) {
                //$result.="{'name':'".$i['species']."','data':[".$count."]},";
                $result[$i['species']] = $count;
            }
            //echo 'species '.$i['species'].' count '.$count;
        };
    }
    return $result;
}
function getSuma($data,$decada){
    $sum=0;
    foreach($data as $key=>$value){
        if(substr($key,0,3)==$decada) {
            $sum +=$value;//duda
        }
    }
    return $sum;
}
function getYears($data,$decada){
    $years=array();
    $i=0;
    foreach($data as $key=>$value){
        if(substr($key,0,3)==$decada){
            $years[$i]=$key;
            $i+=1;
        }
    }
    $temp=$years;
    for($i=0;$i<10;$i++){
        $cambio=strval($i);
        $trans=$decada.$cambio;
        if($temp!=(int)$trans){//error
            $years[$i]=(int)$trans;

        }
        else{
            $years[$i]=$temp;
        }
    }
    return $years;
}
function getData($data,$decada){
    $out=array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $i=0;
    foreach($data as $key=>$value){
        if(substr($key,0,3)==$decada){
            $out[substr($key,3,4)]=$value;
            $i=$i+1;
        }
    }
    return $out;
}
function setCategoryDecades($var1,$var2){
    $categories=array();
    reset($var1);
    $anyo=date('Y');
    if(sizeof($var1)==0){
        $uno=1900;
    }
    else{
        if(key($var1)==''){
            $uno=1900;
        }else{
            $uno=key($var1);}
    }
    reset($var2);
    if(sizeof($var2)==0){
        $dos=1900;
    }
    else{
        if(key($var2)==""){
            next($var2);
            $dos=key($var2);
        }else{
            $dos=key($var2);}
    }
    if($uno<=$dos){
        $ini=substr($uno, 0, 3).'0';
        $inicio=(int)$ini;
        for($i=$inicio;$i<=$anyo;$i+=10){
            array_push($categories,$i);
        }
    }
    else{
        $ini=substr($dos, 0, 3).'0';
        $inicio=(int)$ini;
        for($i=$inicio;$i<=$anyo;$i+=10){
            array_push($categories,$i);
        }
    }
    return $categories;

}
function createDrilldown($var,$categories){//function setYearCountData(yearCount)
    $out=array();
    $decadas=array();
    $decadas2=array();
    $deca=array();
    $decadas2=$categories;
    foreach($decadas2 as $value) {
        $dec2 = substr($value, 0, 3);
        array_push($deca,$dec2);
    }
    $i=0;
    $aux=array();
    foreach($var as $key=>$value) {
        $dec2 = substr($key, 0, 3).'0';
        array_push($aux,$dec2);
    }
    foreach($decadas2 as $value){
        if(in_array($value,$aux)){//si existe decada en arreglo que llega
            if(!in_array($value,$decadas)){//y si no esta agregado
                array_push($decadas, $value);
                $out[$i] = array(
                    'y' => getSuma($var, $deca[$i]),//error
                    'drilldown' => array(
                        'name' => $value,
                        'categories' => getYears($var, $deca[$i]),
                        'data' => getData($var, $deca[$i]),
                    )
                );
                $i+=1;
            }
        }
        else{//Grafico vacio
            $out[$i] = array(
                'y' => getSuma($var, $deca[$i]),
                'drilldown' => array(
                    'name' => $value,
                    'categories' => getYears($var, $deca[$i]),
                    'data' => getData($var, $deca[$i]),
                )
            );
            $i += 1;
        }
    }
    return array($out,$decadas2);

}
function setPieData($graph){
    $data=$graph;
    $colors=array('#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9','#f15c80', '#e4d354', '#8085e8', '#8d4653', '#91e8e1','#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9','#f15c80', '#e4d354', '#8085e8', '#8d4653', '#91e8e1');
    $outPie=array();
    $i=0;
    $outBar=array();
    $categories=array();
    foreach($data as $key=>$value){
        $outPie[$i]=array($key,$value);
        $categories[$i]=$key;
        $outBar[$i]=array(
            "y"=>$value,
            "color"=>$colors[$i]
        );
        $i+=1;
    }
    $out=array($outPie,$categories,$outBar);
    return $out;

}
function getWidth($a,$b){
    return $b*44/$a;
}
function cmp($a,$b){
    if ($a['data'][0] == $b['data'][0]) {
        return 0;
    }
    return ($b['data'][0] < $a['data'][0]) ? -1 : 1;
}
function cmpInst($a,$b){
    if ($a[1] == $b[1]) {
        return 0;
    }
    return ($b[1] < $a[1]) ? -1 : 1;
}
function getResults($p,$s){
    $parameters=$p;
    $solr=$s;
    try {
        return $solr->search('*:*', 0, 0, $parameters);
    }catch (Exception $e)
    {
        return die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
    }
}
$institutionInfo=array();
$hierarchy='';
$nameSpecieAuthor;
$categories=array();
$reuna='REUNA';
$limit = 10000;
$results = false;
$coordinatesReuna = array();
$coordinatesGbif = array();
$totalGbifWithCoordinates = 0;
$totalReuna = 0;
$totalReunaWithCoordinates = 0;
$reunaVacios = 0;
$OrganizationKey = '';
$OrganizationKeyArray = array();
$speciesFound = array();
$genusFound = array();
$institutionNamesReuna = array();
$institutionNamesGbif = array();
$totalInGbif=0;
$monthCount = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$coordYearsReuna = '';
$coordYearsGbif = '';
$additionalParameters = array();
$taxonChildrens=array();
$solr = new Apache_Solr_Service("$USR:$PSWD@$HOST", 80, $SOLRPATH);
$family = substr(current_path(), strripos(current_path(), '/') + 1);
$familyKey;
$FamilyObject=new Family();
$drillDownDataGbif=array();
$drillDownDataReuna=array();
$stackedChildrensReuna=array();
$stackedChildrensGbif=array();
$institutionDataReuna=array();
$institutionDataGbif=array();
$yearCountReuna = array();
$yearCountGbif = array();

$regionsCoordinatesReuna=array();
$regionsCoordinatesGbif=array();

if ($family) {
    $parameters = array(
        'fq' => 'dwc.family_mt:'.$family,
        'fl' => '',
        'facet' => 'true',
        'facet.field' => 'dwc.identifiedBy_s',
        'facet.limit' => 1000000
    );
    $results=getResults($parameters,$solr);
    $salida='<div class="tableElement"><div class="key">Investigador</div><div class="value">Registros</div></div>';
    $familiasPorInvestigador=array();
    $primero=0;
    $nulos='';
    if($results){
        foreach ($results->facet_counts->facet_fields as $doc) {
            foreach ($doc as $field => $value) {
                if($value>0&&strcmp($field,'_empty_')!=0){
                    if($primero==0)$primero=$value;
                    $salida.='<div class="tableElement"><div class="key2">'.$field.'</div><div class="tableGraphElement" style="width:'.getWidth($primero,$value).'%"></div><div class="value">'.$value.'</div></div>';
                    array_push($familiasPorInvestigador,array('name:'=>$field,'data:'=>array($value)));
                }
                elseif(strcmp($field,'_empty_')==0){
                    $nulos='<div class="tableElement"><div class="key"><b>Registros sin nombre</b></div><div class="value"><b>'.$value.'</b></div></div>';
                }
            }
        }
        $salida.=$nulos;
    }
    if($cached=cache_get($family,'cache')){
        $results = $cached->data;
        $coordinatesReuna=$results->getCoordinatesReuna();
        $coordinatesGbif=$results->getCoordinatesGbif();
        $coordYearsReuna=$results->getCoordYearsReuna();
        $coordYearsGbif=$results->getCoordYearsGbif();
        $drillDownDataReuna=$results->getDrillDownDataReuna();
        $drillDownDataGbif=$results->getDrillDownDataGbif();
        $stackedChildrensReuna=$results->getStackedChildrens();
        $stackedChildrensGbif=$results->getStackedChildrensGbif();
        uasort($stackedChildrensGbif,'cmp');
        uasort($stackedChildrensReuna,'cmp');
        //var_dump($stackedChildrensGbif);
        uasort($stackedChildrens,'cmp');
        $institutionDataReuna=$results->getInstitutionData('reuna');
        $institutionDataGbif=$results->getInstitutionData('gbif');
        $yearCountReuna=$results->getYearCount('reuna');
        $yearCountGbif=$results->getYearCount('gbif');
        $totalReuna=$results->getTotalReuna();
        $totalReunaWithCoordinates=$results->getTotalReunaWithCoordinates();
        $totalGbifWithCoordinates=$results->getTotalGbifWithCoordinates();
        $institutionNamesReuna=$results->getInstitutionNamesReuna();
        $institutionNamesGbif=$results->getInstitutionNamesGbif();
        //uasort($institutionDataGbif[0],'cmpInst');
        $countSpecies=$results->getCountSpecies();
        $speciesFound=$results->getSpeciesFound();
        $totalInGbif=$results->getTotalInGbif();
        $familyKey=$results->getFamilyKey();
        $categories=$results->getCategories();
        $regionsCoordinatesReuna=$results->getRegionsCoordinates('reuna');
        $regionsCoordinatesGbif=$results->getRegionsCoordinates('gbif');
        $hierarchy=$results->getHierarchy();
        $nameSpecieAuthor=$results->getNameSpecieAuthor();
        $institutionInfo=$results->getInstitutionInfo();
    }
    else{
        //$query = "RELS_EXT_hasModel_uri_ms:\"info:fedora/biodiversity:biodiversityCModel\"";
        $query = "*:*";
        $additionalParameters = array(
            'fq' => 'dwc.family_mt:' . $family,
            'fl' => 'dwc.month_s,
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
                            $coord=explode(',',$value);
                            array_push($coordinatesReuna,$coord);
                            $totalReunaWithCoordinates++;
                            $i++;
                            break;
                        case 'dwc.scientificName_mt':
                            if (!in_array($value, $speciesFound)) {
                                array_push($speciesFound, $value);
                            }
                            break;
                        case 'dwc.institutionCode_s':
                            if (!array_key_exists($value, $institutionNamesReuna)) {
                                $institutionNamesReuna[$value] = 1;
                            } else {
                                $institutionNamesReuna[$value]++;
                            }
                            break;
                        case 'dwc.year_s':
                            if (!array_key_exists($value, $yearCountReuna)) {
                                $yearCountReuna[$value] = 1;
                            } else {
                                $yearCountReuna[$value]++;
                            }
                            $coordYearsReuna .= $value . ',';
                            break;
                        case 'dwc.month_s':
                            $monthCount[$value - 1]++;
                            break;
                        case 'dwc.genus_mt':
                            if (!in_array($value, $genusFound)) {
                                array_push($genusFound, $value);
                                $value=explode(' ',$value);
                                $taxonChildrens[$value[0]]=1;
                            }
                            else{
                                $value=explode(' ',$value);
                                $taxonChildrens[$value[0]]++;
                            }
                            break;
                    }
                }
            }
            ksort($yearCountReuna);
            $reunaVacios = $totalReuna - $i;
        }
        $regionsCoordinatesReuna=array_count_values(getCountyName($coordinatesReuna,'reuna'));
        $json = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $family . '&dataset_key=d7dddbf4-2cf0-4f39-9b2a-bb099caae36c&rank=FAMILY&limit=1'), true);
        $familyKey = $json['results'][0]['key'];
        echo('family key:' .$familyKey);
        //if(false)
        if ($family) {//355609060576005
            //$json = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $family . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=GENUS&limit=1'), true);
            //$orderKey = $json['results'][0]['key'];
            //$urlHigherTaxon = 'http://api.gbif.org/v1/species/search?dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=SPECIES&highertaxon_key=106605002&limit=0';
            $urlHigherTaxon = 'http://api.gbif.org/v1/species/search?dataset_key=d7dddbf4-2cf0-4f39-9b2a-bb099caae36c&rank=SPECIES&highertaxon_key=' . $familyKey . '&limit=0';
            $result = json_decode(file_get_contents($urlHigherTaxon), true);
            $countSpecies = $result['count'];
            $url_species = 'http://api.gbif.org/v1/species/match?name='.$family;
            $content = file_get_contents($url_species);
            $json = json_decode($content, true);
            $nameSpecieAuthor=isset($json['scientificName'])?json_encode($json['scientificName']):null;
            $hierarchy=makeTaxaHierarchy($json);
            $offset = 0;
            //$speciesKey = isset($json['speciesKey']) ? json_encode($json['speciesKey']) : null;//*
            $speciesKey = isset($json['familyKey']) ? json_encode($json['familyKey']) : null;
            //echo "<pre>".json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
            //$url="http://api.gbif.org/v1/occurrence/search?taxonKey=$speciesKey&HAS_COORDINATE=true&country=CL&limit=1";
            //$url='http://api.gbif.org/v1/occurrence/search?taxonKey='.$speciesKey.'&HAS_COORDINATE=true&country=CL&limit='.$count.'&offset='.$offset;
            $url='http://api.gbif.org/v1/occurrence/search?taxonKey='.$speciesKey.'&HAS_COORDINATE=true&country=CL&isGeoreferenced=true&year=1900,2015';

            //$url = 'http://api.gbif.org/v1/occurrence/count?taxonKey=' . $speciesKey . '&country=CL&isGeoreferenced=true';//*
            $content = isset($json['familyKey']) ? file_get_contents($url) : null;//*
            //$content=file_get_contents($url);*
            //echo 'content_'.$content;
            $json = json_decode($content, true);
            //var_dump($json);
            $url2='http://api.gbif.org/v1/occurrence/count?taxonKey='.$speciesKey.'&country=CL';
            $totalInGbif=file_get_contents($url2);//total de ocurrencias en chile
            $count = $json['count'];//$content;//
            $totalGbifWithCoordinates = $count;
            $yearCountGbif = getCountYears($speciesKey, $count);
            $temporaryArray = array();
            if ($count > 300) {
                while ($count > 0) {
                    $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $speciesKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
                    $content = file_get_contents($url);
                    $json = json_decode($content, true);
                    isset($json['publishingOrgKey']) ? $OrganizationKey = $json['publishingOrgKey'] : $OrganizationKey = 0;
                    foreach ($json['results'] as $i) {
                        $localArray=array($i['decimalLongitude'],$i['decimalLatitude']);
                        array_push($coordinatesGbif,$localArray );
                        if (isset($i['year'])) {
                            if ($i['year'] != '') {
                                $coordYearsGbif .= $i['year'] . ',';
                            } else {
                                $coordYearsGbif .= '0000,';
                            }
                        }
                        if (!array_key_exists($i['publishingOrgKey'], $OrganizationKeyArray)) {
                            $OrganizationKeyArray[$i['publishingOrgKey']] = 1;
                        } else {
                            $OrganizationKeyArray[$i['publishingOrgKey']]++;
                        }
                    }
                    $count -= 300;
                    $offset += 300;
                }
            }
            else {
                $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $speciesKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
                $content = file_get_contents($url);
                $json = json_decode($content, true);
                //echo "<pre>".json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
                foreach ($json['results'] as $i) {
                    $localArray=array(strval($i['decimalLongitude']),strval($i['decimalLatitude']));
                    //array_push($temporaryArray, $i['decimalLongitude']);
                    //array_push($temporaryArray, $i['decimalLatitude']);
                    array_push($coordinatesGbif,$localArray );
                    if (isset($i['year'])) {
                        if ($i['year'] != '') {
                            $coordYearsGbif .= $i['year'] . ',';
                        } else {
                            $coordYearsGbif .= '0000,';
                        }
                    }
                    if (!array_key_exists($i['publishingOrgKey'], $OrganizationKeyArray)) {
                        $OrganizationKeyArray[$i['publishingOrgKey']] = 1;
                    } else {
                        $OrganizationKeyArray[$i['publishingOrgKey']]++;
                    }
                }
            }
            $regionsCoordinatesGbif=array_count_values(getCountyName($coordinatesGbif,'gbif'));
            $organizationNames=getOrganizationNames($OrganizationKeyArray);
            $institutionNamesGbif = $organizationNames[0];
            $institutionInfo=$organizationNames[1];
            uasort($institutionNamesGbif,'cmpInst');
        }
        $familyChildrens=getFamilyGenus($familyKey);
        foreach($familyChildrens as $key=>$value){
            array_push($stackedChildrensGbif,array('name'=>$key,'data'=>array($value), 'index'=>$value, 'legendIndex'=>$value));
        }
        foreach($taxonChildrens as $key=>$value){
            array_push($stackedChildrensReuna,array('name'=>$key,'data'=>array($value), 'index'=>$value, 'legendIndex'=>$value));
        }
        $categories=setCategoryDecades($yearCountReuna,$yearCountGbif);
        $drillDownDataGbif=createDrilldown($yearCountGbif,$categories);
        $drillDownDataReuna=createDrilldown($yearCountReuna,$categories);

        $institutionDataReuna=setPieData($institutionNamesReuna);
        $institutionDataGbif=setPieData($institutionNamesGbif);

        $FamilyObject->setFamily(
            $family,
            array(),
            array(),
            0,
            0,
            $coordinatesReuna,
            $coordYearsReuna,
            $coordinatesGbif,
            $drillDownDataReuna,
            $drillDownDataGbif,
            $stackedChildrensReuna,
            $stackedChildrensGbif,
            $institutionDataReuna,
            $institutionDataGbif,
            $yearCountReuna,
            $yearCountGbif,
            $totalReuna,
            $totalReunaWithCoordinates,
            $totalGbifWithCoordinates,
            $institutionNamesReuna,
            $institutionNamesGbif,
            $countSpecies,
            $speciesFound,
            $coordYearsGbif,
            $totalInGbif,
            $familyKey,
            $categories,
            $regionsCoordinatesReuna,
            $regionsCoordinatesGbif,
            $hierarchy,
            $nameSpecieAuthor
        );
        $FamilyObject->setInstitutionInfo($institutionInfo);
        cache_set($family, $FamilyObject, 'cache', 60*60*30*24); //30 dias
    }

    /*Regiones de Chile
    echo getCountyName(-69.550753,-18.679683).'<br>';
    echo getCountyName(-69.528780,-19.437716).'<br>';
    echo getCountyName(-69.045382,-22.697275).'<br>';
    echo getCountyName(-69.656266,-26.492331).'<br>';
    echo getCountyName(-70.776871,-30.441215).'<br>';
    echo getCountyName(-70.667008,-32.428311).'<br>';
    echo getCountyName(-70.194596,-33.607415).'<br>';
    echo getCountyName(-71.501969,-34.517534).'<br>';
    echo getCountyName(-71.018570,-35.417818).'<br>';
    echo getCountyName(-71.589859,-36.881489).'<br>';
    echo getCountyName(-72.754410,-38.377946).'<br>';
    echo getCountyName(-72.446793,-39.894672).'<br>';
    echo getCountyName(-72.875260,-40.782310).'<br>';
    echo getCountyName(-74.358414,-47.332960).'<br>';
    echo getCountyName(-70.952652,-53.029405).'<br>';*/
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
