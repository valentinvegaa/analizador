<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 01-12-2014
 * Time: 11:02
 */
$queryFilterWord = isset($_REQUEST['qw']) ? $_REQUEST['qw'] : false;
$path = drupal_get_path('module', 'analizador_biodiversidad');
include($path . '/include/functions.php');//libreria que carga todas las funciones a utilizar
include($path . '/include/solrConnection.php');//libreria necesaria para la conexion a Solr
include($path . '/Apache/Solr/Service.php');//libreria necesaria para los servicios de Solr

class Family{
    /**
     *jerarquia taxonomica
     * @var sting $hierarchy
     * @access private
     */
    private $hierarchy='';
    /**
     *nombre del autor y genero
     * @var string $nameSpecieAuthor
     * @access private
     */
    private $nameSpecieAuthor;
    /**
     *nombre de la familia
     * @var string $familyName
     * @access private
     */
    private $familyName='';
    private $generos=array();//no
    private $especies=array();//no
    private $cantidadGeneros=0;//no
    private $cantidadEspecies=0;//no
    private $regionsCoordinatesReuna=array();
    private $regionsCoordinatesGbif=array();
    /**
     *coordenadas Reuna
     * @var array $coordinatesReuna
     * @access private
     */
    private $coordinatesReuna=array();
    /**
     *coordenadas GBif
     * @var array $coordinatesGbif
     * @access private
     */
    private $coordinatesGbif=array();
    /**
     *graficos Reuna
     * @var array $drillDownDataReuna
     * @access private
     */
    private $drillDownDataReuna=array();
    /**
     *graficos Gbif
     * @var array $drillDownDataGbif
     * @access private
     */
    private $drillDownDataGbif=array();
    /**
     *generos de una familia
     * @var array $stackedChildrensReuna
     * @access private
     */
    private $stackedChildrensReuna=array();
    /**
     *generos de una familia
     * @var array $stackedChildrensGbif
     * @access private
     */
    private $stackedChildrensGbif=array();
    /**
     *toda la informacion de institucion para ser graficado
     * @var array $institutionDataReuna
     * @access private
     */
    private $institutionDataReuna=array();
    /**
     *toda la informacion de institucion para ser graficado
     * @var array $institutionDataGbif
     * @access private
     */
    private $institutionDataGbif=array();
    /**
     *cuenta años en Reuna
     * @var array $yearCountReuna
     * @access private
     */
    private $yearCountReuna=array();
    /**
     *cuenta años en Gbif
     * @var array $yearCountGbif
     * @access private
     */
    private $yearCountGbif=array();
    /**
     *total de observaciones en Reuna
     * @var integer $totalReuna
     * @access private
     */
    private $totalReuna=0;
    /**
     *total de observaciones en Gbif
     * @var integer $totalInGbif
     * @access private
     */
    private $totalInGbif=0;
    /**
     *total de observaciones con coordenadas
     * @var integer $totalReunaWithCoordinates
     * @access private
     */
    private $totalReunaWithCoordinates=0;
    /**
     *total de observaciones con coordenadas
     * @var integer $totalGbifWithCoordinates
     * @access private
     */
    private $totalGbifWithCoordinates=0;
    /**
     *instituciones en Reuna
     * @var array $institutionNamesReuna
     * @access private
     */
    private $institutionNamesReuna=array();
    /**
     *instituciones en Gbif
     * @var array $institutionNamesGbif
     * @access private
     */
    private $institutionNamesGbif=array();
    private $countSpecies=0;
    private $speciesFound=array();
    /**
     *coordenadas por año
     * @var string $coordYearsGbif
     * @access private
     */
    private $coordYearsGbif='';
    /**
     *coordenadas por año
     * @var string $coordYearsReuna
     * @access private
     */
    private $coordYearsReuna='';
    /**
     * clave de la familia
     * @var integer $familyKey
     * @access private
     */
    private $familyKey;
    /**
     *categorias en decadas para graficos anuales
     * @var array $categories
     * @access private
     */
    private $categories=array();
    /**
     *años acumulados reuna
     * @var array $accumulatedYearsReuna
     * @access private
     */
    private $accumulatedYearsReuna=array();
    /**
     *años acumulados Gbif
     * @var array $accumulatedYearsGbif
     * @access private
     */
    private $accumulatedYearsGbif=array();
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
        $nameSpecieAuthor,
        $accumulatedYearsReuna,
        $accumulatedYearsGbif
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
        $this->accumulatedYearsReuna=$accumulatedYearsReuna;
        $this->accumulatedYearsGbif=$accumulatedYearsGbif;
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
        return $this->totalGbifWithCoordinates;
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
    public function getAccumulatedYearsReuna(){
    return $this->accumulatedYearsReuna;
    }
    public function getAccumulatedYearsGbif(){
    return $this->accumulatedYearsGbif;
    }

}
$accumulatedYearsReuna=array();
$accumulatedYearsGbif=array();
$institutionInfo=array();
$hierarchy='';
$nameSpecieAuthor;
$categories=array();
$reuna='REUNA';
$limit = 10000;
$someVar = "";
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
        uasort($institutionNamesGbif,'cmpInst');
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
        $accumulatedYearsReuna=$results->getAccumulatedYearsReuna();
        $accumulatedYearsGbif=$results->getAccumulatedYearsGbif();
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
            $instNamesReuna=array();
            foreach($institutionNamesReuna as $key=>$value){
                array_push($instNamesReuna,array($key,$value));
            }
            $institutionNamesReuna=$instNamesReuna;
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
                    $someVar = getCountMonths($speciesKey);
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
                $someVar =getCountMonths($speciesKey);
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
            array_push($stackedChildrensGbif,array('name'=>$key,'data'=>array($value), 'index'=>$value, 'legendIndex'=>$value,'pointWidth'=>28));
        }
        foreach($taxonChildrens as $key=>$value){
            array_push($stackedChildrensReuna,array('name'=>$key,'data'=>array($value), 'index'=>$value, 'legendIndex'=>$value,'pointWidth'=>28));
        }
        $categories=setCategoryDecades($yearCountReuna,$yearCountGbif);
        $drillDownDataGbif=createDrilldown($yearCountGbif,$categories);
        $drillDownDataReuna=createDrilldown($yearCountReuna,$categories);
        $accumulatedYearsGbif=setAccumulatedYears($yearCountGbif);
        $accumulatedYearsReuna=setAccumulatedYears($yearCountReuna);
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
            $nameSpecieAuthor,
            $accumulatedYearsGbif,
            $accumulatedYearsReuna
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
