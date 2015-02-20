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
    private $categorias=array();
    private $coordinatesInPHP;
    private $coordinatesGBIFInPHP;
    private $anyoGbif=array();
    private $mesGbif=array();
    private $institucionReuna=array();
    private $institucionGbif=array();
    private $nombreEspecieAutor;
    private $jerarquia='';
    //private $region='';
    //private $categories=array();
    /**/
    private $monthCountReuna=array();//reuna
    private $DrillDownDataGbif=array();//grafico Gbif
    private $DrillDownDataReuna=array();
    private $yearCount=array();
    private $coordYearsREUNA;
    private $speciesKey;
    private $search=array();
    private $totalGbif;
    private $totalEnGBIF;
    private $totalReunaConCoordenadas;
    private $totalReuna;
    private $categoryYears;
    private $AccumulatedYearsGbif;
    private $AccumulatedYearsReuna;
    private $REUNA;
    private $coordYearsGBIF;
    /**/


    public function setSpecie($nombreCientifico,$coordinatesInPHP,$coordinatesGBIFInPHP,$anyo,$mesGbif,$institucionReuna,$institucionGbif,$monthCountReuna,$DrillDownDataGbif,$yearCount,$coordYearsREUNA,$speciesKey,$search,$totalGbif,$totalReunaConCoordenadas,$totalReuna,$DrillDownDataReuna,$categoryYears
    ,$AccumulatedYearsGbif,$AccumulatedYearsReuna,$REUNA,$coordYearsGBIF,$totalEnGBIF,$categorias,$nombreEspecieAutor,$jerarquia){

        $this->nombreCientifico=$nombreCientifico;
        $this->coordinatesInPHP=$coordinatesInPHP;
        $this->coordinatesGBIFInPHP=$coordinatesGBIFInPHP;
        $this->anyoGbif=$anyo;
        $this->mesGbif=$mesGbif;
        $this->institucionReuna=$institucionReuna;
        $this->institucionGbif=$institucionGbif;
        /**/
        $this->monthCountReuna=$monthCountReuna;
        $this->DrillDownDataGbif=$DrillDownDataGbif;
        $this->yearCount=$yearCount;
        $this->coordYearsREUNA=$coordYearsREUNA;
        $this->speciesKey=$speciesKey;
        $this->search=$search;
        $this->totalGbif=$totalGbif;
        $this->totalReunaConCoordenadas=$totalReunaConCoordenadas;
        $this->totalReuna=$totalReuna;
        $this->DrillDownDataReuna=$DrillDownDataReuna;
        $this->categoryYears=$categoryYears;
        $this->AccumulatedYearsGbif=$AccumulatedYearsGbif;
        $this->AccumulatedYearsReuna=$AccumulatedYearsReuna;
        $this->REUNA=$REUNA;
        $this->coordYearsGBIF=$coordYearsGBIF;
        $this->totalEnGBIF=$totalEnGBIF;
        $this->categorias=$categorias;
        $this->nombreEspecieAutor=$nombreEspecieAutor;
        $this->jerarquia=$jerarquia;
        /**/

    }
    public function getCategoriesGbif(){
        $categs=array();
        foreach($this->institucionGbif as $key=>$value){
            array_push($categs,$value[0]);
        }
        return $categs;
    }
    public function getInstitucionReuna(){
        return $this->institucionReuna;
    }
    public function getInstitucionGbif(){
        return $this->institucionGbif;
    }
    public function getCoordinatesGBIFInPHP(){
        return $this->coordinatesGBIFInPHP;
    }
    public function getCoordinatesInPHP(){
        return $this->coordinatesInPHP;
    }
    /**/
    public function getMonthCountReuna(){
        return $this->monthCountReuna;//reuna

    }
    public function getMesGbif(){//gbif
        return $this->mesGbif;
    }
    public function getAnyoGbif(){
        return $this->anyoGbif;
    }
    public function getYearCount(){
        return $this->yearCount;
    }


    public function getDrillDownDataGbif(){
        return $this->DrillDownDataGbif;
    }
    public function getCoordYearsREUNA(){
        return $this->coordYearsREUNA;
    }
    public function getSpeciesKey(){
        return $this->speciesKey;
    }
    public function getSearch(){
        return $this->search;
    }
    public function getTotalGbif(){
        return $this->totalGbif;
    }
    public function getTotalReunaConCoordenadas(){
        return $this->totalReunaConCoordenadas;
    }
    public function getTotalReuna(){
        return $this->totalReuna;
    }
    public function getDrillDownDataReuna(){
        return $this->DrillDownDataReuna;
    }
    public function getCategoryYears(){
        return $this->categoryYears;
    }
    public function getAccumulatedYearsGbif(){
        return $this->AccumulatedYearsGbif;
    }
    public function getAccumulatedYearsReuna(){
        return $this->AccumulatedYearsReuna;
    }
    public function getReuna(){
        return $this->REUNA;
    }
    public function getCoordYearsGBIF(){
        return $this->coordYearsGBIF;
    }
    public function getTotalEnGBIF(){
        return $this->totalEnGBIF;
    }
    public function getCategorias(){
        return $this->categorias;
    }
    public function getNombreEspecieAutor(){
    return $this->nombreEspecieAutor;
    }
    public function getJerarquia(){
        return $this->jerarquia;
    }
    /**/

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
/* Funciones JS a PHP */

function suma($data,$decada){
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
function CalculaEjeX($var1,$var2){
    $categorias=array();
    reset($var1);
    $anyo=date('Y');
    if(sizeof($var1)==0){
        //if($var1[0]==0){
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
        //if($var1[0]==0){
        $dos=1900;
    }
    else{
        if(key($var2)==""){
            next($var2);
            $dos=key($var2);
        }else{
            $dos=key($var2);}
    }
    var_dump($uno);
    var_dump($dos);
    if($uno<=$dos){
        $ini=substr($uno, 0, 3).'0';
        $inicio=(int)$ini;
        for($i=$inicio;$i<=$anyo;$i+=10){
            array_push($categorias,$i);
        }
    }
    else{
        $ini=substr($dos, 0, 3).'0';
        $inicio=(int)$ini;
        for($i=$inicio;$i<=$anyo;$i+=10){
            array_push($categorias,$i);
        }
    }
    return $categorias;

}
function createDrilldown($var,$categorias){//function setYearCountData(yearCount)
    $out=array();
    $decadas=array();
    $decadas2=array();
    $deca=array();
    $decadas2=$categorias;
    foreach($decadas2 as $value) {
        $dec2 = substr($value, 0, 3);
        array_push($deca,$dec2);
    }
    $i=0;
    $aux=array();
    foreach($var as $key=>$value) {
        $dec2 = substr($key, 0, 3).'0';//$dec . '0';
        array_push($aux,$dec2);
    }
    foreach($decadas2 as $value){
        if(in_array($value,$aux)){//si existe decada en arreglo que llega
            if(!in_array($value,$decadas)){//y si no esta agregado
                array_push($decadas, $value);
                $out[$i] = array(
                    'y' => suma($var, $deca[$i]),//error
                    //'color' => $colors[$i],
                    //'color'=>'#53AD25',
                    'drilldown' => array(
                        'name' => $value,
                        'categories' => getYears($var, $deca[$i]),//error
                        'data' => getData($var, $deca[$i]),//error
                        //'color' => $colors[$i]
                        //'color'=>'#53AD25'
                    )
                );//
                $i+=1;
            }
        }
        else{//Grafico vacio
            $out[$i] = array(
                'y' => suma($var, $deca[$i]),
                //'color' => $colors[$i],
                //'color'=>'#53AD25',
                'drilldown' => array(
                    'name' => $value,
                    'categories' => getYears($var, $deca[$i]),
                    'data' => getData($var, $deca[$i]),
                    //'color' => $colors[$i]
                    //'color'=>'#53AD25'
                )
            );//
            $i += 1;
        }
    }
    return array($out,$decadas2);

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
        //$result.=' > <a href="http://www.ecoinformatica.cl/site/analizador/order/'.$order.'">'.$order.'</a>';
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
function createDrilldownCategories($var){
    $decadas=array();
    for($i=0;$i<sizeof($var);$i++){
        $dec=substr($var[$i],0,3).'0';
        if(!in_array($dec,$decadas)){
            array_push($decadas,$dec);
        }
    }
    return $decadas;


}
function setAccumulatedYears($yearCount){
    $years=$yearCount;
    $result=array();
    $year=date('Y');
    $n=0;
    $last=0;
    $anyo=115;
    $j=0;
    for($i=0;$i<=$anyo;$i++){
        if($years[$year-$anyo+$i]!=0){
           $n=$years[$year-$anyo+$i];
            $j+=1;
        }
        else{
            $n=0;
            $j+=1;
        }
        $last += $n;
        if($j==1){
            array_push($result,$last);
            $j=0;}
    }
    return $result;

}
function setCategoryYears()
{
    $anyo=100;
    $result=array();
    $year=date('Y');
    $n=1900;
    /*for($i=0;$i<=$anyo;$i++){
        $n=$year-$anyo+$i;
        $ene=strval($n);
        array_push($result,$ene);
    }*/
    while($n<=$year){

        $ene=strval($n);
        array_push($result,$ene);
        //$n+=5;
        $n++;
    }

    return $result;

}
function checkPosition($coords){
    for($i=0;$i<count($coords);$i++){
        $url='http://nominatim.openstreetmap.org/reverse?format=xml&lat='.$coords[$i][1].'&lon='.$coords[$i][0].'&zoom=18&addressdetails=1';
        $page=file_get_contents($url);
        $result=json_decode($page,true);
        var_dump($result);
    }
}
function getResults($p,$s,$limit){
    $parameters=$p;
    $solr=$s;
    try {
        return $solr->search('*:*', 0, $limit, $parameters);
    }catch (Exception $e)
    {
        return die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
    }
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
/*--------------------------*/


/*__Variables Gbif__*/

$mesGbif = "";
$coordinatesGBIFInPHP;
$institutionNamesGBIF = array();
$yearsGBIFforRange = array();
$yearCountGbif = array();
$coordYearsGBIF = '';
$totalGBIF = 0;
$totalEnGBIF=0;
$drilldownData=array();
$accumulatedYearsGbif=array();
/*------------------------*/

/*__Variables Reuna__*/

$coordinatesInPHP;//
$totalReuna = 0;
$totalReunaConCoordenadas = 0;
$reunaVacios = 0;
$yearCount = array();
$monthCountReuna = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$coordYearsREUNA = '';
$coordinatesReuna=array();
$institutionNamesReuna = array();
$DrillDownDataReuna=array();
$accumulatedYearsReuna=array();
/*-------------------*/
$jerarquia='';
$nombreEspecieAutor;
$categorias=array();
$categoryYears=array();
$limit = 10000;
$tipo = "";
$results = false;
$REUNA='REUNA';
$OrganizationKey = '';
$OrganizationKeyArray = array();
$instituionsNamesOcurr = array();


$additionalParameters = array();
$solr = new Apache_Solr_Service("$USR:$PSWD@$HOST", 80, $SOLRPATH);
$specie = substr(current_path(), strripos(current_path(), '/') + 1);



if($specie){
    $parameters = array(
        'fq' => 'dwc.scientificName_mt:"'.$specie.'"',
        'fl' => 'dwc.identifiedBy_s,dwc.institutionCode_s',
        'facet' => 'true',
        'facet.field' => 'dwc.identifiedBy_s',
        'facet.limit' => 1000000
    );
    $results=getResults($parameters,$solr,10000);
    $salida='<div class="tableElement"><div class="key">Investigador</div><div class="value">Registros</div></div>';
    $especiesPorInvestigador=array();
    $primero=0;
    $nulos='';
    if($results){
        $institInvest=array();
        foreach ($results->response->docs as $doc) {
            $inst='';
            foreach ($doc as $field => $value) {
                switch ($field) {
                    case 'dwc.institutionCode_s':
                        $inst=$value;
                        break;
                    case 'dwc.identifiedBy_s':
                        $institInvest[$value]=$inst;
                        break;
                }
            }
        }
        foreach ($results->facet_counts->facet_fields as $doc) {
            foreach ($doc as $field => $value) {
                if($value>0&&strcmp($field,'_empty_')!=0){
                    if($primero==0)$primero=$value;
                    $salida.='<div class="tableElement"><div class="key2">'.$field.'<div class="miniInst">['.$institInvest[$field].']</div></div><div class="tableGraphElement" style="width:'.getWidth($primero,$value).'%"></div><div class="value">'.$value.'</div></div>';
                    array_push($especiesPorInvestigador,array('name:'=>$field,'data:'=>array($value)));
                }
                elseif(strcmp($field,'_empty_')==0){
                    $nulos='<div class="tableFooter">*Registros sin investigador asociado ['.$value.']</div>';
                }
            }
        }
        $salida.=$nulos;
    }
    if ($cached = cache_get($specie, 'cache')){
        $results = $cached->data;
        $institutionNamesGBIF=$results->getInstitucionGbif();
        uasort($institutionNamesGBIF,'cmpInst');
        $categoriesGBIF=$results->getCategoriesGbif();
        $coordinatesGBIFInPHP=$results->getCoordinatesGBIFInPHP();
        $coordinatesInPHP=$results->getCoordinatesInPHP();
        $institutionNamesReuna=$results->getInstitucionReuna();
        /*Agregados*/
        $monthCountReuna=$results->getMonthCountReuna();
        $mesGbif=$results->getMesGbif();
        $yearCountGbif=$results->getAnyoGbif();
        $DrillDownDataGbif=$results->getDrillDownDataGbif();
        $yearCount=$results->getYearCount();
        $coordYearsREUNA=$results->getCoordYearsREUNA();
        $speciesKey=$results->getSpeciesKey();
        $search=$results->getSearch();//nombre de especie
        $totalGBIF=$results->getTotalGbif();
        $totalReunaConCoordenadas=$results->getTotalReunaConCoordenadas();
        $totalReuna=$results->getTotalReuna();
        $DrillDownDataReuna=$results->getDrillDownDataReuna();
        $categoryYears=$results->getCategoryYears();
        $accumulatedYearsGbif=$results->getAccumulatedYearsGbif();
        $accumulatedYearsReuna=$results->getAccumulatedYearsReuna();
        $REUNA=$results->getReuna();
        $coordYearsGBIF=$results->getCoordYearsGBIF();
        $totalEnGBIF=$results->getTotalEnGBIF();
        $categorias=$results->getCategorias();
        $nombreEspecieAutor=$results->getNombreEspecieAutor();
        $jerarquia=$results->getJerarquia();
        /**/

        echo 'cache!';
    }
    else{
        $query='*:*';
        //$search=explode(' ',$specie);
        $additionalParameters = array(
            //'fq' => 'dwc.genus_mt:'.$search[0].' AND dwc.specificEpithet_mt:'.$search[1],
            'fq' => 'dwc.scientificName_mt:"'.$specie.'"',
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
                            //$coord=explode(',',$value);
                            //array_push($coordinatesReuna,$coord);
                            $coordinatesInPHP .= htmlspecialchars($value, ENT_NOQUOTES, 'utf-8') . ",";
                            $totalReunaConCoordenadas++;
                            $i++;
                            break;
                        case 'dwc.institutionCode_s':
                            if (!array_key_exists($value, $institutionNamesReuna)) {
                                $institutionNamesReuna[$value] = 1;
                            } else {
                                $institutionNamesReuna[$value]++;
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
                            $monthCountReuna[$value - 1]++;
                            break;
                    }
                }
            }
            $instNamesReuna=array();
            foreach($institutionNamesReuna as $key=>$value){
                array_push($instNamesReuna,array($key,$value));
            }
            $institutionNamesReuna=$instNamesReuna;
            ksort($yearCount);
            $reunaVacios = $totalReuna - $i;
        }
        $url_species = 'http://api.gbif.org/v1/species/match?name=' . str_replace(' ', '+', $specie);
        $content = file_get_contents($url_species);
        $json = json_decode($content, true);
        $nombreEspecieAutor=isset($json['scientificName'])?json_encode($json['scientificName']):null;
        $jerarquia=makeTaxaHierarchy($json);
        //se obtiene la llave que identifica a la especie en el repositorio de especies de GBIF
        $speciesKey = isset($json['usageKey']) ? json_encode($json['usageKey']) : null;
        //Obtenemos la cantidad de ocurrencias chilenas georeferenciadas usando la llave que encontramos antes
       // $url = 'http://api.gbif.org/v1/occurrence/count?taxonKey=' . $speciesKey . '&isGeoreferenced=true&country=CL';
        $url2='http://api.gbif.org/v1/occurrence/count?taxonKey='.$speciesKey.'&country=CL';
        $url='http://api.gbif.org/v1/occurrence/search?taxonKey='.$speciesKey.'&HAS_COORDINATE=true&country=CL&isGeoreferenced=true&year=1900,2015';
        //$url2='http://api.gbif.org/v1/occurrence/count?taxonKey='.$genusKey.'&country=CL';
        $res=json_decode(file_get_contents($url),true);
        $totalGBIF=$res['count'];//ocurrencias georeferenciadas desde 1900 en gbif
        //$totalGBIF = isset($json['speciesKey']) ? file_get_contents($url) : null;
        $totalEnGBIF=isset($json['usageKey']) ? file_get_contents($url2) : null;
        $offset = 0;
        $count=$totalGBIF;
        //$coordinatesGBIFInPHP = "";
        //obtenemos un arreglo con los años como llave y en cada posicion la cantidad de ocurrencias para ese año(llave del arreglo)
        $yearCountGbif = countYears($speciesKey, $count);

        $SpeciesObject=new Especie();
        $temporaryArray = array();
        if ($count > 300) {
            while ($count > 0) {
                //obtenemos las ocurrencias chilenas georefernciadas
                $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $speciesKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
                $content = file_get_contents($url);
                $json = json_decode($content, true);
                isset($json['publishingOrgKey']) ? $OrganizationKey = $json['publishingOrgKey'] : $OrganizationKey = 0;
                $mesGbif= countMonths($speciesKey);
                foreach ($json['results'] as $i) {
                    //$coordinatesGBIFInPHP.=$i['decimalLongitude'].",".$i['decimalLatitude'].",";
                    array_push($temporaryArray, $i['decimalLongitude']);
                    array_push($temporaryArray, $i['decimalLatitude']);
                    //$coordYearsGBIF .= isset($i['year']) ? $i['year'] : '-1' . ',';
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
            $mesGbif = countMonths($speciesKey);
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
        //$regionesReuna=checkPosition($coordinatesReuna);
        $coordinatesGBIFInPHP = implode(', ', $temporaryArray);
        //$yearsGBIFforRange=implode(', ',$tempRange);
        $institutionNamesGBIF = getOrganizationNames($OrganizationKeyArray);
        uasort($institutionNamesGBIF,'cmp');
        //$results = json_decode(file_get_contents($search_url));
        $categorias=CalculaEjeX($yearCount,$yearCountGbif);
        $DrillDownDataGbif=createDrilldown($yearCountGbif,$categorias);
        $DrillDownDataReuna=createDrilldown($yearCount,$categorias);
        $categoryYears=setCategoryYears();
        $accumulatedYearsGbif=setAccumulatedYears($yearCountGbif);
        $accumulatedYearsReuna=setAccumulatedYears($yearCount);
        $SpeciesObject->setSpecie($specie,$coordinatesInPHP,$coordinatesGBIFInPHP,$yearCountGbif,$mesGbif,$institutionNamesReuna,$institutionNamesGBIF,$monthCountReuna,$DrillDownDataGbif,$yearCount,$coordYearsREUNA,$speciesKey,$search,$totalGBIF,$totalReunaConCoordenadas,$totalReuna,$DrillDownDataReuna,$categoryYears
                                    ,$accumulatedYearsGbif,$accumulatedYearsReuna,$REUNA,$coordYearsGBIF,$totalEnGBIF,$categorias,$nombreEspecieAutor,$jerarquia);

        cache_set($specie, $SpeciesObject, 'cache', 60*60*30*24); //30 dias
        echo 'NO cache!';

    }
}
echo '2';
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