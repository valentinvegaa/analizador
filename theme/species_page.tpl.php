<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 28-10-2014
* Time: 20:22
*/
$path = drupal_get_path('module', 'analizador_biodiversidad');
include($path . '/include/functions.php');//libreria que carga todas las funciones a utilizar
include($path . '/include/solrConnection.php');//libreria necesaria para la conexion a Solr
include($path . '/Apache/Solr/Service.php');//libreria necesaria para los servicios de Solr
class Especies{
    /**
     * clave de la especie
     * @var integer $speciesKey
     * @access private
     */
    private $speciesKey=0;
    /**
     *nombre cientifico de la especie
     * @var string $scientificName
     * @access private
     */
    private $scientificName='';
    /**
     *jerarquia taxonomica
     * @var sting $hierarchy
     * @access private
     */
    private $hierarchy='';
    /**
     *categorias en decadas para graficos anuales
     * @var array $categories
     * @access private
     */
    private $categories=array();
    /**
     *nombre del autor y especie
     * @var string $nameSpecieAuthor
     * @access private
     */
    private $nameSpecieAuthor='';
    /**
     *busqueda realizada
     * @var array $search
     * @access private
     */
    private $search=array();
    /**
     *nombre reuna
     * @var string $reuna
     * @access private
     */
    private $reuna='';
    /**
     *informacion de instituciones
     * @var array $institutionInfo
     * @access private
     */
    private $institutionInfo=array();
    /**
     *cuenta años en Gbif
     * @var array $yearCountGbif
     * @access private
     */
    private $yearCountGbif=array();
    /**
     *cuenta años en Reuna
     * @var array $yearCountReuna
     * @access private
     */
    private $yearCountReuna=array();
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
    /**
     *
     * @var array $regionsCoordinatesReuna
     * @access private
     */
    private $regionsCoordinatesReuna=array();
    /**
     *
     * @var aray $regionsCoordinatesGbif
     * @access private
     */
    private $regionsCoordinatesGbif=array();
    /**
     *cuenta registros por mes
     * @var array $monthCountReuna
     * @access private
     */
    private $monthCountReuna=array();
    /**
     *cuenta registros por mes
     * @var array $monthCountGbif
     * @access private
     */
    private $monthCountGbif=array();
    /**
     *graficos Gbif
     * @var array $drillDownDataGbif
     * @access private
     */
    private $drillDownDataGbif=array();
    /**
     *graficos Reuna
     * @var array $drillDownDataReuna
     * @access private
     */
    private $drillDownDataReuna=array();
    /**
     *años acumulados Gbif
     * @var array $accumulatedYearsGbif
     * @access private
     */
    private $accumulatedYearsGbif=array();
    /**
     *años acumulados reuna
     * @var array $accumulatedYearsReuna
     * @access private
     */
    private $accumulatedYearsReuna=array();
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
     *
     * @var
     * @access private
     */
    private $institutionGbif=array();
    /**
     *coordenadas por año
     * @var string
     * @access private
     */
    private $coordYearsReuna;
    /**
     *coordenadas por año
     * @var string $coordYearsGbif
     * @access private
     */
    private $coordYearsGbif;
    /**
     *total de observaciones en Gbif
     * @var integer $totalInGbif
     * @access private
     */
    private $totalInGbif=0;
    /**
     *total de observaciones en Reuna
     * @var integer $totalReuna
     * @access private
     */
    private $totalReuna=0;//
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
     *coordenadas de Reuna
     * @var array $coordinatesReunaInPhp
     * @access private
     */
    private $coordinatesReunaInPhp=array();//coordenadas de Reuna
    /**
     *coordenadas de Gbif
     * @var array $coordinatesGbifInPhp
     * @access private
     */
    private $coordinatesGbifInPhp;
    /**
     *
     * @var
     * @access private
     */
    private $categoryYears;//no va
    public function setInstitutionInfo($institutionInfo){
        $this->institutionInfo=$institutionInfo;
    }
    public function setSpecie(
        $scientificName,
        $coordinatesReunaInPhp,
        $coordinatesGbifInPhp,
        $anyo,
        $monthCountGbif,
        $institutionNamesReuna,
        $institutionNamesGbif,
        $monthCountReuna,
        $drillDownDataGbif,
        $yearCountReuna,
        $coordYearsReuna,
        $speciesKey,
        $search,
        $totalGbifWithCoordinates,
        $totalReunaWithCoordinates,
        $totalReuna,
        $drillDownDataReuna,
        $categoryYears,
        $accumulatedYearsGbif,
        $accumulatedYearsReuna,
        $reuna,
        $coordYearsGbif,
        $totalInGbif,
        $categories,
        $nameSpecieAuthor,
        $hierarchy,
        $regionsCoordinatesReuna,
        $regionsCoordinatesGbif,
        $institutionDataReuna,
        $institutionDataGbif,
        $institutionGbif
    ){

        $this->scientificName=$scientificName;
        $this->coordinatesReunaInPhp=$coordinatesReunaInPhp;
        $this->coordinatesGbifInPhp=$coordinatesGbifInPhp;
        $this->yearCountGbif=$anyo;
        $this->monthCountGbif=$monthCountGbif;
        $this->institutionNamesReuna=$institutionNamesReuna;
        $this->institutionNamesGbif=$institutionNamesGbif;
        $this->monthCountReuna=$monthCountReuna;
        $this->drillDownDataGbif=$drillDownDataGbif;
        $this->yearCountReuna=$yearCountReuna;
        $this->coordYearsReuna=$coordYearsReuna;
        $this->speciesKey=$speciesKey;
        $this->search=$search;
        $this->totalGbifWithCoordinates=$totalGbifWithCoordinates;
        $this->totalReunaWithCoordinates=$totalReunaWithCoordinates;
        $this->totalReuna=$totalReuna;
        $this->drillDownDataReuna=$drillDownDataReuna;
        $this->categoryYears=$categoryYears;
        $this->accumulatedYearsGbif=$accumulatedYearsGbif;
        $this->accumulatedYearsReuna=$accumulatedYearsReuna;
        $this->reuna=$reuna;
        $this->coordYearsGbif=$coordYearsGbif;
        $this->totalInGbif=$totalInGbif;
        $this->categories=$categories;
        $this->nameSpecieAuthor=$nameSpecieAuthor;
        $this->hierarchy=$hierarchy;
        $this->regionsCoordinatesReuna=$regionsCoordinatesReuna;
        $this->regionsCoordinatesGbif=$regionsCoordinatesGbif;
        $this->institutionDataReuna=$institutionDataReuna;
        $this->institutionDataGbif=$institutionDataGbif;
        $this->institutionGbif=$institutionGbif;
    }
    public function getCategoriesGbif(){
        $categs=array();
        foreach($this->$institutionGbif as $key=>$value){
            array_push($categs,$value[0]);
        }
        return $categs;
    }
    public function getInstitutionNamesReuna(){
        return $this->institutionNamesReuna;
    }
    public function getInstitutionNamesGbif(){
        return $this->institutionNamesGbif;
    }
    public function getCoordinatesGbifInPhp(){
        return $this->coordinatesGbifInPhp;
    }
    public function getCoordinatesReunaInPhp(){
        return $this->coordinatesReunaInPhp;
    }
    public function getMonthCountReuna(){
        return $this->monthCountReuna;
    }
    public function getMonthCountGbif(){
        return $this->monthCountGbif;
    }
    public function getYearCountGbif(){
        return $this->yearCountGbif;
    }
    public function getYearCountReuna(){
        return $this->yearCountReuna;
    }
    public function getDrillDownDataGbif(){
        return $this->drillDownDataGbif;
    }
    public function getCoordYearsReuna(){
        return $this->coordYearsReuna;
    }
    public function getSpeciesKey(){
        return $this->speciesKey;
    }
    public function getSearch(){
        return $this->search;
    }
    public function getTotalGbifWithCoordinates(){
        return $this->totalGbifWithCoordinates;
    }
    public function getTotalReunaWithCoordinates(){
        return $this->totalReunaWithCoordinates;
    }
    public function getTotalReuna(){
        return $this->totalReuna;
    }
    public function getDrillDownDataReuna(){
        return $this->drillDownDataReuna;
    }
    public function getCategoryYears(){
        return $this->categoryYears;
    }
    public function getAccumulatedYearsGbif(){
        return $this->accumulatedYearsGbif;
    }
    public function getAccumulatedYearsReuna(){
        return $this->accumulatedYearsReuna;
    }
    public function getReuna(){
        return $this->reuna;
    }
    public function getCoordYearsGbif(){
        return $this->coordYearsGbif;
    }
    public function getTotalInGbif(){
        return $this->totalInGbif;
    }
    public function getCategories(){
        return $this->categories;
    }
    public function getNameSpecieAuthor(){
    return $this->nameSpecieAuthor;
    }
    public function getHierarchy(){
        return $this->hierarchy;
    }
    public function getRegionsCoordinates($rog){
        return (strcmp('reuna',$rog))!=0?$this->regionsCoordinatesReuna:$this->regionsCoordinatesGbif;
    }
    public function getInstitutionData($rog){//Reuna Or Gbif
        return (strcmp('reuna',$rog))!=0?$this->institutionDataGbif:$this->institutionDataReuna;
    }
    public function getInstitutionInfo(){
        return $this->institutionInfo;
    }
    public function getInstitutionGbif(){
    return $this->institutionGbif;
}
}
$reuna='REUNA';
$hierarchy='';
$nameSpecieAuthor;
$limit = 10000;
$tipo = "";
$results = false;
$categoriesGbif;
$totalInGbif=0;
$totalReuna = 0;
$totalReunaWithCoordinates = 0;
$totalGbifWithCoordinates = 0;
$reunaVacios = 0;
$categories=array();
$speciesByInvestigator=array();
$categoryYears=array();
$OrganizationKey = '';
$institutionNamesGbif = array();
$institutionNamesReuna = array();
$yearsGBIFforRange = array();
$institutionGbif=array();
$coordYearsGbif = '';
$coordYearsReuna = '';
$yearCountReuna = array();
$yearCountGbif = array();
$monthCountReuna = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$monthCountGbif = "";
$coordinatesReunaInPhp=array();
$coordinatesGbifInPhp=array();
$drillDownDataReuna=array();
$drillDownDataGbif=array();
$accumulatedYearsReuna=array();
$accumulatedYearsGbif=array();
$OrganizationKeyArray = array();
$instituionsNamesOcurr = array();
$regionsCoordinatesReuna=array();
$regionsCoordinatesGbif=array();
$institutionDataReuna=array();
$institutionDataGbif=array();
$institutionInfo=array();
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
    $primero=0;
    $nulos='';
    if($results){
        $institInvest=array();
        foreach ($results->response->docs as $doc) {//recorre las instituciones en Reuna y guarda sus datos
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
                    array_push($speciesByInvestigator,array('name:'=>$field,'data:'=>array($value)));
                }
                elseif(strcmp($field,'_empty_')==0){
                    $nulos='<div class="tableFooter">*Registros sin investigador asociado ['.$value.']</div>';
                }
            }
        }
        $salida.=$nulos;
    }
    if ($cached = cache_get($specie, 'cache')){//si la busqueda esta en la cache se obtiene
        $results = $cached->data;//results tiene toda la informacion
        $institutionNamesGbif=$results->getInstitutionNamesGbif();
        uasort($institutionNamesGbif,'cmpInst');
       // $categoriesGbif=$results->getCategoriesGbif();
        $coordinatesGbifInPhp=$results->getCoordinatesGbifInPhp();
        $coordinatesReunaInPhp=$results->getCoordinatesReunaInPhp();
        $institutionNamesReuna=$results->getInstitutionNamesReuna();//getInstitutionNames EN INGLÉS
        $monthCountReuna=$results->getMonthCountReuna();
        $monthCountGbif=$results->getMonthCountGbif();
        $yearCountGbif=$results->getYearCountGbif();
        $drillDownDataGbif=$results->getDrillDownDataGbif();
        $yearCountReuna=$results->getYearCountReuna();
        $coordYearsReuna=$results->getCoordYearsReuna();
        $speciesKey=$results->getSpeciesKey();
        $search=$results->getSearch();//nombre de especie
        $totalGbifWithCoordinates=$results->getTotalGbifWithCoordinates();
        $totalReunaWithCoordinates=$results->getTotalReunaWithCoordinates();
        $totalReuna=$results->getTotalReuna();
        $drillDownDataReuna=$results->getDrillDownDataReuna();
        $categoryYears=$results->getCategoryYears();
        $accumulatedYearsGbif=$results->getAccumulatedYearsGbif();
        $accumulatedYearsReuna=$results->getAccumulatedYearsReuna();
        $reuna=$results->getReuna();
        $coordYearsGbif=$results->getCoordYearsGbif();
        $totalInGbif=$results->getTotalInGbif();
        $categories=$results->getCategories();
        $nameSpecieAuthor=$results->getNameSpecieAuthor();
        $hierarchy=$results->getHierarchy();
        $regionsCoordinatesReuna=$results->getRegionsCoordinates('reuna');
        $regionsCoordinatesGbif=$results->getRegionsCoordinates('gbif');
        $institutionDataReuna=$results->getInstitutionData('reuna');
        $institutionDataGbif=$results->getInstitutionData('gbif');
        $institutionInfo=$results->getInstitutionInfo();
        $institutionGbif=$results->getInstitutionGbif();
    }
    else{//se busca toda la informacion
        $query='*:*';
        $additionalParameters = array(
            'fq' => 'dwc.scientificName_mt:"'.$specie.'"',
            'fl' => 'dwc.month_s,
                 dwc.year_s,
                 dwc.institutionCode_s,
                 dwc.scientificName_mt,
                 dc.subject,
                 dwc.latlong_p',
        );//parametros a buscar en Solr
        try {
            $results = $solr->search($query, 0, $limit, $additionalParameters);//busqueda en Solr
        } catch (Exception $e) {
            die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
        }
        if ($results) {
            $totalReuna = $results->response->numFound;//se accede al contenido entregado
            $i = 0;
            foreach ($results->response->docs as $doc) {//se recorre el archivo
                foreach ($doc as $field => $value) {
                    switch ($field) {
                        case 'dwc.latlong_p'://[(a,b),(a,b)
                            $coord=explode(',',$value);
                            array_push($coordinatesReunaInPhp,$coord);//guarda la coordenada en el array
                            $totalReunaWithCoordinates++;
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
                            if (!array_key_exists($value, $yearCountReuna)) {
                                $yearCountReuna[$value] = 1;
                            } else {
                                $yearCountReuna[$value]++;//cuenta registros por año
                            }
                            $coordYearsReuna .= $value . ',';
                            break;
                        case 'dwc.month_s':
                            if($value>0)$monthCountReuna[$value - 1]++;
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
        $regionsCoordinatesReuna=array_count_values(getCountyName($coordinatesReunaInPhp,'reuna'));
        $url_species = 'http://api.gbif.org/v1/species/match?name=' . str_replace(' ', '+', $specie);//informacion de la especie
        $content = file_get_contents($url_species);
        $json = json_decode($content, true);
        $nameSpecieAuthor=isset($json['scientificName'])?json_encode($json['scientificName']):null;//nombre de la especie y autor
        $hierarchy=makeTaxaHierarchy($json);
        //se obtiene la llave que identifica a la especie en el repositorio de especies de GBIF
        $speciesKey = isset($json['usageKey']) ? json_encode($json['usageKey']) : null;
        //Obtenemos la cantidad de ocurrencias chilenas georeferenciadas usando la llave que encontramos antes
        $url2='http://api.gbif.org/v1/occurrence/count?taxonKey='.$speciesKey.'&country=CL';//numero especies en chile
        $url='http://api.gbif.org/v1/occurrence/search?taxonKey='.$speciesKey.'&HAS_COORDINATE=true&country=CL&isGeoreferenced=true&year=1900,2015';
        $res=json_decode(file_get_contents($url),true);
        $totalGbifWithCoordinates=$res['count'];//ocurrencias georeferenciadas desde 1900 en gbif
        $totalInGbif=isset($json['usageKey']) ? file_get_contents($url2) : null;
        $offset = 0;
        $count=$totalGbifWithCoordinates;
        //obtenemos un arreglo con los años como llave y en cada posicion la cantidad de ocurrencias para ese año(llave del arreglo)
        $yearCountGbif = getCountYears($speciesKey, $count);
        $SpeciesObject=new Especies();
        $temporaryArray = array();
        if ($count > 300) {
            while ($count > 0) {
                //obtenemos las ocurrencias chilenas georeferenciadas
                $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $speciesKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
                $content = file_get_contents($url);
                $json = json_decode($content, true);
                isset($json['publishingOrgKey']) ? $OrganizationKey = $json['publishingOrgKey'] : $OrganizationKey = 0;
                $monthCountGbif= getCountMonths($speciesKey);//cuenta registros por mes
                foreach ($json['results'] as $i) {
                    $localArray=array(strval($i['decimalLongitude']),strval($i['decimalLatitude']));
                    //array_push($temporaryArray, $i['decimalLongitude']);
                    //array_push($temporaryArray, $i['decimalLatitude']);
                    array_push($coordinatesGbifInPhp,$localArray );//arreglo con coordenadas
                    if (isset($i['year'])) {
                        if ($i['year'] != '') {
                            $coordYearsGbif .= $i['year'] . ',';//arreglo con años
                        } else {
                            $coordYearsGbif .= '-1,';
                        }
                    }
                    else {
                        $coordYearsGbif .= '-1,';
                    }
                    //arreglo con organizaciones y numero de registros
                    if (!array_key_exists($i['publishingOrgKey'], $OrganizationKeyArray)) {
                        $OrganizationKeyArray[$i['publishingOrgKey']] = 1;
                    } else {
                        $OrganizationKeyArray[$i['publishingOrgKey']]++;
                    }
                }
                $count -= 300;
                $offset += 300;
            }
        } else {//menor a 300 registros encontrados
            $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $speciesKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
            $content = file_get_contents($url);
            $json = json_decode($content, true);
            $monthCountGbif = getCountMonths($speciesKey);
            //echo "<pre>".json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
            foreach ($json['results'] as $i) {
                $localArray=array(strval($i['decimalLongitude']),strval($i['decimalLatitude']));
                array_push($coordinatesGbifInPhp,$localArray );
                if (isset($i['year'])) {
                    if ($i['year'] != '') {
                        $coordYearsGbif .= $i['year'] . ',';
                    } else {
                        $coordYearsGbif .= '-1,';
                    }
                }
                else {
                    $coordYearsGbif .= '-1,';
                }
                if (!array_key_exists($i['publishingOrgKey'], $OrganizationKeyArray)) {
                    $OrganizationKeyArray[$i['publishingOrgKey']] = 1;
                } else {
                    $OrganizationKeyArray[$i['publishingOrgKey']]++;
                }
            }
        }
        $regionsCoordinatesGbif=array_count_values(getCountyName($coordinatesGbifInPhp,'gbif'));
        $organizationNames=getOrganizationNames($OrganizationKeyArray);
        $institutionNamesGbif = $organizationNames[0];
        $institutionInfo=$organizationNames[1];
        uasort($institutionNamesGbif,'cmpInst');
        $categories=setCategoryDecades($yearCountReuna,$yearCountGbif);//categorias en decadas para grafico anual
        $drillDownDataGbif=createDrilldown($yearCountGbif,$categories);//arreglo con registros por decada para ser graficado
        $drillDownDataReuna=createDrilldown($yearCountReuna,$categories);//arreglo con registros por decada para ser graficado
        $categoryYears=setCategoryYears();
        $accumulatedYearsGbif=setAccumulatedYears($yearCountGbif);//arreglo con registros acumulados por año
        $accumulatedYearsReuna=setAccumulatedYears($yearCountReuna);//arreglo con registros acumulados por año
        $institutionDataReuna=setPieData($institutionNamesReuna);//arreglo para ser graficado en Pie
        $institutionDataGbif=setPieData($institutionNamesGbif);//arreglo para ser graficado en Pie
        $SpeciesObject->setSpecie(
            $specie,
            $coordinatesReunaInPhp,
            $coordinatesGbifInPhp,
            $yearCountGbif,
            $monthCountGbif,
            $institutionNamesReuna,
            $institutionNamesGbif,
            $monthCountReuna,
            $drillDownDataGbif,
            $yearCountReuna,
            $coordYearsReuna,
            $speciesKey,
            $search,
            $totalGbifWithCoordinates,
            $totalReunaWithCoordinates,
            $totalReuna,
            $drillDownDataReuna,
            $categoryYears,
            $accumulatedYearsGbif,
            $accumulatedYearsReuna,
            $reuna,
            $coordYearsGbif,
            $totalInGbif,
            $categories,
            $nameSpecieAuthor,
            $hierarchy,
            $regionsCoordinatesReuna,
            $regionsCoordinatesGbif,
            $institutionDataReuna,
            $institutionDataGbif
        );
        $SpeciesObject->setInstitutionInfo($institutionInfo);
        cache_set($specie, $SpeciesObject, 'cache', 60*60*30*24); //30 dias
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