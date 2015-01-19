<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 28-10-2014
 * Time: 20:22
 */
class Genero{
    /*__Variables__*/
        private $genusKey='';
        private $search='';
        private $totalReuna;
        private $taxonChildrens=array();
        private $totalReunaConCoordenadas;
        private $totalGBIF;
        private $coordYearsREUNA='';
        private $coordYearsGBIF='';
        private $yearCountGbif=array();
        private $institutionNames=array();//(reuna)
        private $specie=array();
        private $institutionNamesGBIF=array();
        private $yearCount=array();//reuna
        private $monthCount=array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        private $someVar='';
        private $drillDownDataGbif=array();//grafico Gbif
        private $drillDownDataReuna=array();
        private $accumulatedYearsGbif;
        private $accumulatedYearsReuna;
         private $categoryYears;
    private $coordinatesGBIFInPHP;
    private $coordinatesInPHP;
    private $dataReuna=array();
    private $dataGbif=array();


    /*-------------*/

    public function setGenero($genusKey,$search,$totalReuna,$taxonChildrens,$totalReunaConCoordenadas,$totalGBIF,$coordYearsREUNA,
                                $coordYearsGBIF,$yearCountGbif,$institutionNames,$institutionNamesGBIF,$yearCount,$monthCount,$someVar,
                                $drillDownDataGbif,$drillDownDataReuna,$accumulatedYearsGbif,$accumulatedYearsReuna,$categoryYears,$coordinatesGBIFInPHP,$coordinatesInPHP
                            ,$dataReuna,$dataGbif){

        //$this->specie=$specie;
        $this->genusKey=$genusKey;
        $this->search=$search;
        $this->totalReuna=$totalReuna;
        $this->taxonChildrens=$taxonChildrens;
        $this->totalReunaConCoordenadas=$totalReunaConCoordenadas;
        $this->totalGBIF=$totalGBIF;
        $this->coordYearsREUNA=$coordYearsREUNA;
        $this->coordYearsGBIF=$coordYearsGBIF;
        $this->yearCountGbif=$yearCountGbif;
        $this->institutionNames=$institutionNames;
        $this->institutionNamesGBIF=$institutionNamesGBIF;
        $this->yearCount=$yearCount;
        $this->monthCount=$monthCount;
        $this->someVar=$someVar;
        $this->drillDownDataGbif=$drillDownDataGbif;
        $this->drillDownDataReuna=$drillDownDataReuna;
        $this->accumulatedYearsGbif=$accumulatedYearsGbif;
        $this->accumulatedYearsReuna=$accumulatedYearsReuna;
        $this->categoryYears=$categoryYears;
        $this->coordinatesGBIFInPHP=$coordinatesGBIFInPHP;
        $this->coordinatesInPHP=$coordinatesInPHP;
        $this->dataReuna=$dataReuna;
        $this->dataGbif=$dataGbif;

    }
    public function getSpecie(){
        return $this->specie;
    }
    public function getGenusKey(){
        return $this->genusKey;
    }
    public function getSearch(){
        return $this->search;
    }
    public function getTotalReuna(){
        return $this->totalReuna;
    }
    public function getTaxonChildrens(){
        return $this->taxonChildrens;
    }
    public function getTotalReunaConCoordenadas(){
        return $this->totalReunaConCoordenadas;
    }
    public function getTotalGbif(){
        return $this->totalGBIF;
    }
    public function getCoordYearsReuna(){
        return $this->coordYearsREUNA;
    }
    public  function getCoordYearsGbif(){
        return $this->coordYearsGbif;
    }
    public function getYearCountGbif(){
        return $this->yearCountGbif;
    }
    public function getInstitutionNames(){
        return $this->institutionNames;
    }
    public function getInstitutionNamesGbif(){
        return $this->institutionNamesGBIF;
    }
    public function getYearCount(){//reuna
        return $this->yearCount;
    }
    public function getMonthCount(){
        return $this->monthCount;
    }
    public function getSomeVar(){
        return $this->someVar;
    }
    public function getDrillDownDataGbif(){
        return $this->drillDownDataGbif;
    }
    public function getDrillDownDataReuna(){
        return $this->drillDownDataReuna;
    }
    public function getAccumulatedYearsGbif(){
        return $this->accumulatedYearsGbif;
    }
    public function getAccumulatedYearsReuna(){
        return $this->accumulatedYearsReuna;
    }
    public function getCategoryYears(){
        return $this->categoryYears;
    }
    public function getCoordinatesGBIFInPHP(){
        return $this->coordinatesGBIFInPHP;
    }
    public function getCoordinatesInPHP(){
        return $this->coordinatesInPHP;
    }
    public function getDataReuna(){
        return $this->dataReuna;
    }
    public function getDataGbif(){
        return $this->dataGbif;
    }
}
$queryFilterWord = isset($_REQUEST['qw']) ? $_REQUEST['qw'] : false;
$limitGBIF = 20;
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
        $result[$json['title']] = $i;
        //$org=json_decode(file_get_contents("http://api.gbif.org/v1/organization/$i"),true);
    }
    //var_dump($result);
    return $result;
}

function getChildrenNames($key){
    $children = json_decode(file_get_contents('http://api.gbif.org/v1/species/'.$key.'/children/?limit=300'), true);//106311492
    $result=array();
    foreach($children['results'] as $i){
        $count = json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/count?taxonKey='.$i['nubKey'].'&country=CL'),true);
        if($count>0){
            //$result.="{'name':'".$i['species']."','data':[".$count."]},";
            $result[$i['species']]=$count;
        }
        //echo 'species '.$i['species'].' count '.$count;
    };
    return $result;
}

/**/
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

function createDrilldown($var){//function setYearCountData(yearCount)
    $out=array();
    $decadas=array();
    $i=0;
    $colors=array('#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9','#f15c80', '#e4d354', '#8085e8', '#8d4653', '#91e8e1','#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9','#f15c80', '#e4d354', '#8085e8', '#8d4653', '#91e8e1');
    //for($i=0;$i<sizeof($var);$i++){
    foreach($var as $key=>$valor){
        $dec=substr($key,0,3);//.'0';
        $dec2=$dec.'0';
        if(!in_array($dec2,$decadas)){
            array_push($decadas,$dec2);
            //$algo=substr($key,0,3);//error
            $out[$i]=array(
                'y'=>suma($var,$dec),//error
                'color'=>$colors[$i],
                'drilldown'=>array(
                    'name'=>$dec2,
                    'categories'=>getYears($var,$dec),//error
                    'data'=>getData($var,$dec),//error
                    'color'=>$colors[$i]
                )
            );//
            $i+=1;

        }

    }
    //return [$out,$decadas];
    //var_dump($out);
    //$ordenado=sort($decadas);
    return array($out,$decadas);//entrega las decadas pero una no sale en orden.

}
function setAccumulatedYears($yearCount){

    $result=array();
    $year=date('Y');
    $n=0;
    $last=0;
    $anyo=100;
    for($i=0;$i<=$anyo;$i++){
        if(array_key_exists($year-$anyo+$i,$yearCount)){
            if($yearCount[$year-$anyo+$i]!=0){
                $n=$yearCount[$year-$anyo+$i];
            }
        }
        else{
            $n=0;
        }
        $last+=$n;
        array_push($result,$last);
    }
    return $result;

}
function setCategoryYears(){

    $anyo=100;
    $result=array();
    $year=date('Y');
    $n=0;
    for($i=0;$i<=$anyo;$i++){
        $n=$year-$anyo+$i;
        $ene=strval($n);
        array_push($result,$ene);
    }

    return $result;

}

function setPieData($graph){
    $data=$graph;
    $colors=array('#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9','#f15c80', '#e4d354', '#8085e8', '#8d4653', '#91e8e1','#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9','#f15c80', '#e4d354', '#8085e8', '#8d4653', '#91e8e1');
    $outPie=array();
    $i=0;
    $outBar=array();
    $categorias=array();
    foreach($data as $key=>$value){
        $outPie[$i]=array($key,$value);
        $categorias[$i]=$key;
        $outBar[$i]=array(
            "y"=>$value,
            "color"=>$colors[$i]
        );
        $i+=1;
    }
    $out=array($outPie,$categorias,$outBar);
    return $out;

}
$dataReuna=array();
$dataGbif=array();
$drillDownDataGbif=array();
$drillDownDataReuna=array();
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
$genusKey='';

$yearsGBIFforRange = array();
$monthCount = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$yearCountGbif = array();
$coordYearsREUNA = '';
$coordYearsGBIF = '';
$additionalParameters = array();
$taxonChildrens=array();
$accumulatedYearsReuna=array();
$accumulatedYearsGbif=array();
$categoryYears=array();

$solr = new Apache_Solr_Service("$USR:$PSWD@$HOST", 80, $SOLRPATH);
$search = substr(current_path(), strripos(current_path(), '/') + 1);



if ($search) {
    if($cached = cache_get($search, 'cache')){
        $results = $cached->data;
        $genusKey=$results->getGenusKey();
        $search=$results->getSearch();
        $totalReuna=$results->getTotalReuna();
        $taxonChildrens=$results->getTaxonChildrens();
        $totalReunaConCoordenadas=$results->getTotalReunaConCoordenadas();
        $totalGBIF=$results->getTotalGbif();
        $coordYearsREUNA=$results->getCoordYearsReuna();
        $coordYearsGBIF=$results->getCoordYearsGbif();
        $yearCountGbif=$results->getYearCountGbif();
        $institutionNames=$results->getInstitutionNames();
        $institutionNamesGBIF=$results->getInstitutionNamesGbif();
        $yearCount=$results->getYearCount();
        $monthCount=$results->getMonthCount();
        $someVar=$results->getSomeVar();
        $drillDownDataGbif=$results->getDrillDownDataGbif();
        $drillDownDataReuna=$results->getDrilldownDataReuna();
        $accumulatedYearsGbif=$results->getAccumulatedYearsGbif();
        $accumulatedYearsReuna=$results->getAccumulatedYearsReuna();
        $categoryYears=$results->getCategoryYears();
        $coordinatesGBIFInPHP=$results->getCoordinatesGBIFInPHP();
        $coordinatesInPHP=$results->getCoordinatesInPHP();
        $dataReuna=$results->getDataReuna();
        $dataGbif=$results->getDataGbif();

        echo 'cache!';

    }
    else {
        $query = "RELS_EXT_hasModel_uri_ms:\"info:fedora/biodiversity:biodiversityCModel\"";
        if (get_magic_quotes_gpc() == 1) $query = stripslashes($query);
        $additionalParameters = array(
            'fq' => 'dwc.genus_s:'.$search,
            'fl' => 'dwc.month_s,
                 dwc.year_s,
                 dwc.institutionCode_s,
                 dwc.genus_mt,
                 dwc.scientificName_mt,
                 dc.subject,
                 dwc.latlong_p',
        );
        //if (false)
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
                            $coordinatesInPHP .= htmlspecialchars($value, ENT_NOQUOTES, 'utf-8') . ",";
                            $totalReunaConCoordenadas++;
                            $i++;
                            break;
                        case 'dwc.scientificName_mt':
                            if (!in_array($value, $speciesFound)) {
                                array_push($speciesFound, $value);
                                $value = explode(' ', $value);
                                $taxonChildrens[$value[0] . ' ' . $value[1]] = 1;
                            } else {
                                $value = explode(' ', $value);
                                $taxonChildrens[$value[0] . ' ' . $value[1]]++;
                            }
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
                    /*if($field=='dwc.latlong_p') {
                        $coordinatesInPHP .= htmlspecialchars($value, ENT_NOQUOTES, 'utf-8') . ",";
                        $totalReunaConCoordenadas++;
                        $i++;
                    }
                    if($field=='dwc.scientificName_mt') {
                        if (!in_array($value, $speciesFound)) {
                            array_push($speciesFound, $value);
                        }
                    }
                    if($field=='dwc.institutionCode_s'){
                        if(!array_key_exists($value,$institutionNames)){
                            $institutionNames[$value]=1;
                        }
                        else{
                            $institutionNames[$value]++;
                        }
                    }
                    if($field=='dwc.year_s'){
                        if(!array_key_exists($value,$yearCount)){
                            $yearCount[$value]=1;
                        }
                        else{$yearCount[$value]++;}
                    }
                    if($field=='dwc.month_s'){
                        $monthCount[$value-1]++;
                    }*/
                }
                //echo $institutionNames[$value][0];
            }
            ksort($yearCount);
            echo('qwe');
            var_dump($yearCount);
            echo('qwe');
            $reunaVacios = $totalReuna - $i;
            //echo $coordinatesInPHP;
        }
        //print_r($institutionNames);
        $json = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $search . '&rank=GENUS&limit=1'), true);
        $genusKey = $json['results'][0]['nubKey'];
        //if(false)
        if ($search) {//355609060576005
            //$json = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $search . '&dataset_key=fab88965-e69d-4491-a04d-e3198b626e52&rank=GENUS&limit=1'), true);
            //$genusKey = $json['results'][0]['key'];
            echo $genusKey . 'genusKey';
            $urlHigherTaxon = 'http://api.gbif.org/v1/species/search?rank=SPECIES&highertaxon_key=' . $genusKey . '&limit=0';
            $result = json_decode(file_get_contents($urlHigherTaxon), true);
            $countSpecies = $result['count'];
            /*$url_species = 'http://api.gbif.org/v1/species/match?name=' . str_replace(' ', '+', $search);
            $content = file_get_contents($url_species);
            $json = json_decode($content, true);
            $speciesKey = isset($json['genusKey']) ? json_encode($json['genusKey']) : null;*/
            $url = 'http://api.gbif.org/v1/occurrence/count?taxonKey=' . $genusKey . '&country=CL&isGeoreferenced=true';
            $totalGBIF = file_get_contents($url);
            //echo 'content_'.$content;
            //$json = json_decode($content, true);
            //var_dump($json);
            echo 'asdasd<br>';
            $offset = 0;
            //$count = $content;//$json['count'];
            //$totalGBIF = $count;
            $genusObject=new Genero();
            $count = $totalGBIF;
            $coordinatesGBIFInPHP = "";
            $yearCountGbif = countYears($genusKey, $count);
            $temporaryArray = array();
            if ($count > 300) {
                while ($count > 0) {
                    //$url="http://api.gbif.org/v1/occurrence/search?taxonKey=$speciesKey&HAS_COORDINATE=true&country=CL&limit=$count&offset=$offset";
                    //$content = file_get_contents($url);
                    //$json = json_decode($content, true);
                    //echo "<pre>".json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
                    $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $genusKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
                    $content = file_get_contents($url);
                    $json = json_decode($content, true);
                    isset($json['publishingOrgKey']) ? $OrganizationKey = $json['publishingOrgKey'] : $OrganizationKey = 0;
                    $someVar = countMonths($genusKey);
                    foreach ($json['results'] as $i) {
                        //$coordinatesGBIFInPHP.=$i['decimalLongitude'].",".$i['decimalLatitude'].",";
                        array_push($temporaryArray, $i['decimalLongitude']);
                        array_push($temporaryArray, $i['decimalLatitude']);
                        $coordYearsGBIF .= isset($i['year']) ? $i['year'] : '' . ',';
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
                $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $genusKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
                $content = file_get_contents($url);
                $json = json_decode($content, true);
                $someVar = countMonths($genusKey);
                //echo "<pre>".json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
                foreach ($json['results'] as $i) {
                    array_push($temporaryArray, $i['decimalLongitude']);
                    array_push($temporaryArray, $i['decimalLatitude']);
                    if (isset($i['year'])) {
                        if ($i['year'] != '') {
                            $coordYearsGBIF .= $i['year'] . ',';
                        } else {
                            $coordYearsGBIF .= '0000,';
                        }
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
            $drillDownDataGbif=createDrilldown($yearCountGbif);
            $drillDownDataReuna=createDrilldown($yearCount);
            $categoryYears=setCategoryYears();
            $accumulatedYearsGbif=setAccumulatedYears($yearCountGbif);
            $accumulatedYearsReuna=setAccumulatedYears($yearCount);
            $institutionNamesGBIF = getOrganizationNames($OrganizationKeyArray);
            $dataReuna=setPieData($institutionNames);
            $dataGbif=setPieData($institutionNamesGBIF);
            echo('qwe');
            var_dump($institutionNamesGBIF);

            $genusObject->setGenero($genusKey,$search,$totalReuna,$taxonChildrens,$totalReunaConCoordenadas,$totalGBIF,$coordYearsREUNA,
                $coordYearsGBIF,$yearCountGbif,$institutionNames,$institutionNamesGBIF,$yearCount,$monthCount,$someVar,$drillDownDataGbif,$drillDownDataReuna,$accumulatedYearsGbif,$accumulatedYearsReuna,$categoryYears
                    ,$coordinatesGBIFInPHP,$coordinatesInPHP,$dataReuna,$dataGbif);

            cache_set($search, $genusObject, 'cache', 60*60*30*24); //30 dias
            echo 'NO cache!';


        }



    }
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
                    include(drupal_get_path('module', 'analizador_biodiversidad') . '/include/analizador_genero.php');
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