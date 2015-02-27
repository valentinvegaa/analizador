<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 28-10-2014
 * Time: 20:22
 */
$queryFilterWord = isset($_REQUEST['qw']) ? $_REQUEST['qw'] : false;
$path = drupal_get_path('module', 'analizador_biodiversidad');
include($path . '/include/functions.php');//libreria que carga todas las funciones a utilizar
include($path . '/include/solrConnection.php');//libreria necesaria para la conexion a Solr
include($path . '/Apache/Solr/Service.php');//libreria necesaria para los servicios de Solr
//

class Genero{
    /**
     * clave del genero
     * @var integer $genusKey
     * @access private
     */
    private $genusKey=0;
    /**
     *jerarquia taxonomica
     * @var sting $hierarchy
     * @access private
     */
    private $hierarchy='';
    /**
     *busqueda realizada
     * @var array $search
     * @access private
     */
    private $search='';
    /**
     *categorias en decadas para graficos anuales
     * @var array $categories
     * @access private
     */
    private $categories=array();
    /**
     *total de observaciones en Reuna
     * @var integer $totalReuna
     * @access private
     */
    private $totalReuna;
    /**
     *total de observaciones con coordenadas
     * @var integer $totalReunaWithCoordinates
     * @access private
     */
    private $totalReunaWithCoordinates=0;
    /**
     *coordenadas por año
     * @var string
     * @access private
     */
    private $coordYearsREUNA;
    private $institutionNames=array();//(reuna)
    /**
     *años acumulados reuna
     * @var array $accumulatedYearsReuna
     * @access private
     */
    private $accumulatedYearsReuna=array();
    /**
     *
     * @var array $taxonChildrens
     * @access private
     */
    private $taxonChildrens=array();
    private $coordinatesReuna;
    private $dataReuna=array();
    private $yearCount=array();//reuna
    /**
     *graficos Reuna
     * @var array $drillDownDataReuna
     * @access private
     */
    private $drillDownDataReuna=array();
    /**
     *especies de un genero dado
     * @var array $stackedChildrensReuna
     * @access private
     */
    private $stackedChildrensReuna=array();
    private $totalGbif;
    /**
     *coordenadas por año
     * @var string $coordYearsGbif
     * @access private
     */
    private $coordYearsGBIF;
    /**
     *cuenta años en Gbif
     * @var array $yearCountGbif
     * @access private
     */
    private $yearCountGbif=array();
    /**
     *instituciones en Gbif
     * @var array $institutionNamesGbif
     * @access private
     */
    private $institutionNamesGBIF=array();
    /**
     *cuenta registros por mes
     * @var array $monthCountReuna
     * @access private
     */
    private $monthCount=array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    private $someVar='';
    /**
     *graficos Gbif
     * @var array $drillDownDataGbif
     * @access private
     */
    private $drillDownDataGbif=array();
    /**
     *años acumulados Gbif
     * @var array $accumulatedYearsGbif
     * @access private
     */
    private $accumulatedYearsGbif=array();
    private $categoryYears;
    /**
     *coordenadas de Gbif
     * @var array $coordinatesGbifInPhp
     * @access private
     */
    private $coordinatesGBIFInPHP=array();
    private $dataGbif=array();
    /**
     *total de observaciones en Gbif
     * @var integer $totalInGbif
     * @access private
     */
    private $totalInGbif;
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
     *informacion de instituciones
     * @var array $institutionInfo
     * @access private
     */
    private $institutionInfo=array();
    /**
     *nombre del autor y genero
     * @var string $nameSpecieAuthor
     * @access private
     */
    private $nameSpecieAuthor='';

    public function setInstitutionInfo($institutionInfo){
        $this->institutionInfo=$institutionInfo;
    }

    public function setGenero($genusKey,$search,$totalReuna,$taxonChildrens,$totalReunaWithCoordinates,$totalGbif,$coordYearsREUNA,
                                $coordYearsGBIF,$yearCountGbif,$institutionNames,$institutionNamesGBIF,$yearCount,$monthCount,$someVar,
                                $drillDownDataGbif,$drillDownDataReuna,$accumulatedYearsGbif,$accumulatedYearsReuna,$categoryYears,$coordinatesGBIFInPHP,$coordinatesReuna
                            ,$dataReuna,$dataGbif,$totalInGbif,$stackedChildrensReuna,$stackedChildrensGbif,$categories,
                                $institutionDataReuna,
                                $institutionDataGbif,$nameSpecieAuthor,$hierarchy){

        $this->genusKey=$genusKey;
        $this->search=$search;
        $this->totalReuna=$totalReuna;
        $this->taxonChildrens=$taxonChildrens;
        $this->totalReunaWithCoordinates=$totalReunaWithCoordinates;
        $this->totalGbif=$totalGbif;
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
        $this->coordinatesReuna=$coordinatesReuna;
        $this->dataReuna=$dataReuna;
        $this->dataGbif=$dataGbif;
        $this->totalInGbif=$totalInGbif;
        $this->stackedChildrensReuna=$stackedChildrensReuna;
        $this->stackedChildrensGbif=$stackedChildrensGbif;
        $this->categories=$categories;
        $this->institutionDataReuna=$institutionDataReuna;
        $this->institutionDataGbif=$institutionDataGbif;
        $this->nameSpecieAuthor=$nameSpecieAuthor;
        $this->hierarchy=$hierarchy;

    }
    public function getInstitutionData($rog){//Reuna Or Gbif
        return (strcmp('reuna',$rog))!=0?$this->institutionDataGbif:$this->institutionDataReuna;
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
    public function getTotalReunaWithCoordinates(){
        return $this->totalReunaWithCoordinates;
    }
    public function getTotalGbif(){
        return $this->totalGbif;
    }
    public function getCoordYearsReuna(){
        return $this->coordYearsREUNA;
    }
    public  function getCoordYearsGbif(){
        return $this->coordYearsGBIF;
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
    public function getCoordinatesReuna(){
        return $this->coordinatesReuna;
    }
    public function getDataReuna(){
        return $this->dataReuna;
    }
    public function getDataGbif(){
        return $this->dataGbif;
    }
    public function getTotalInGbif(){
        return $this->totalInGbif;
    }
    public function getStackedChildrensReuna(){
        return $this->stackedChildrensReuna;
    }
    public function getStackedChildrensGbif(){
        return $this->stackedChildrensGbif;
    }
    public function getCategories(){
        return $this->categories;
    }
    public function getInstitutionInfo(){
        return $this->institutionInfo;
    }
    public function getNameSpecieAuthor(){
        return $this->nameSpecieAuthor;
    }
    public function getHierarchy(){
        return $this->hierarchy;
    }

}
$hierarchy='';
$nameSpecieAuthor='';
$categories=array();
$stackedChildrensReuna=array();
$stackedChildrensGbif=array();
$reuna='REUNA';
$dataReuna=array();
$dataGbif=array();
$drillDownDataGbif=array();
$drillDownDataReuna=array();
$limit = 10000;
$tipo = "";
$mesGbif = "";
$results = false;
$coordinatesReuna=array();
$coordinatesGBIFInPHP = array();
$totalGbif = 0;
$totalReuna = 0;
$totalReunaWithCoordinates = 0;
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
$institutionNamesReuna = array();
$institutionNamesGBIF = array();
$instituionsNamesOcurr = array();
$yearCount = array();
$yearCountGbif = array();
$genusKey='';
$totalInGbif=0;
$yearsGBIFforRange = array();
$monthCount = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$coordYearsREUNA;
$coordYearsGBIF;
$additionalParameters = array();
$taxonChildrens=array();
$accumulatedYearsReuna=array();
$accumulatedYearsGbif=array();
$categoryYears=array();

$institutionInfo=array();

$solr = new Apache_Solr_Service("$USR:$PSWD@$HOST", 80, $SOLRPATH);
$search = substr(current_path(), strripos(current_path(), '/') + 1);
if ($search) {
    $parameters = array(
        'fq' => 'dwc.genus_mt:'.$search,
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
    if($cached = cache_get($search, 'cache')){
        $results = $cached->data;
        $genusKey=$results->getGenusKey();
        $search=$results->getSearch();
        $totalReuna=$results->getTotalReuna();
        $taxonChildrens=$results->getTaxonChildrens();
        $totalReunaWithCoordinates=$results->getTotalReunaWithCoordinates();
        $totalGbif=$results->getTotalGbif();
        $coordYearsREUNA=$results->getCoordYearsReuna();
        $coordYearsGBIF=$results->getCoordYearsGbif();
        $yearCountGbif=$results->getYearCountGbif();
        $institutionNamesReuna=$results->getInstitutionNames();
        $institutionNamesGBIF=$results->getInstitutionNamesGbif();
        uasort($institutionNamesGBIF,'cmpInst');
        $yearCount=$results->getYearCount();
        $monthCount=$results->getMonthCount();
        $mesGbif=$results->getSomeVar();
        $drillDownDataGbif=$results->getDrillDownDataGbif();
        $drillDownDataReuna=$results->getDrilldownDataReuna();
        $accumulatedYearsGbif=$results->getAccumulatedYearsGbif();
        $accumulatedYearsReuna=$results->getAccumulatedYearsReuna();
        $categoryYears=$results->getCategoryYears();
        $coordinatesGBIFInPHP=$results->getCoordinatesGBIFInPHP();
        $coordinatesReuna=$results->getCoordinatesReuna();
        $dataReuna=$results->getDataReuna();
        $dataGbif=$results->getDataGbif();
        $totalInGbif=$results->getTotalInGbif();
        $stackedChildrensReuna=$results->getStackedChildrensReuna();
        $stackedChildrensGbif=$results->getStackedChildrensGbif();
        $categories=$results->getCategories();
        $institutionInfo=$results->getInstitutionInfo();
        $institutionDataReuna=$results->getInstitutionData('reuna');
        $institutionDataGbif=$results->getInstitutionData('gbif');
        $nameSpecieAuthor=$results->getNameSpecieAuthor();
        $hierarchy=$results->getHierarchy();

    }
    else {
        $query = "RELS_EXT_hasModel_uri_ms:\"info:fedora/biodiversity:biodiversityCModel\"";
        if (get_magic_quotes_gpc() == 1) $query = stripslashes($query);
        $additionalParameters = array(
            'fq' => 'dwc.genus_mt:'.$search,
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
                                $value = explode(' ', $value);
                                $taxonChildrens[$value[0] . ' ' . $value[1]] = 1;
                            } else {
                                $value = explode(' ', $value);
                                $taxonChildrens[$value[0] . ' ' . $value[1]]++;
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
                }
            }
            $instNamesReuna=array();
            foreach($institutionNamesReuna as $key=>$value){
                array_push($instNamesReuna,array($key,$value));
            }
            $institutionNamesReuna=$instNamesReuna;
            ksort($yearCount);
            echo('qwe');
            var_dump($yearCount);
            echo('qwe');
            $reunaVacios = $totalReuna - $i;
        }
        $json = json_decode(file_get_contents('http://api.gbif.org/v1/species/search?q=' . $search . '&dataset_key=d7dddbf4-2cf0-4f39-9b2a-bb099caae36c&rank=GENUS&limit=1'), true);
        $genusKey = $json['results'][0]['genusKey'];//nubKey o genusKey
        if ($search) {
            echo $genusKey . 'genusKey';
            $urlHigherTaxon = 'http://api.gbif.org/v1/species/search?dataset_key=d7dddbf4-2cf0-4f39-9b2a-bb099caae36c&rank=SPECIES&highertaxon_key=' . $genusKey . '&limit=0';
            $result = json_decode(file_get_contents($urlHigherTaxon), true);
            $url='http://api.gbif.org/v1/occurrence/search?taxonKey='.$genusKey.'&HAS_COORDINATE=true&country=CL&isGeoreferenced=true';
            $url2='http://api.gbif.org/v1/occurrence/count?taxonKey='.$genusKey.'&country=CL';
            $url_genus = 'http://api.gbif.org/v1/species/match?name='.$search;
            $content = file_get_contents($url_genus);
            $json = json_decode($content, true);
            $nameSpecieAuthor=isset($json['scientificName'])?json_encode($json['scientificName']):null;
            $hierarchy=makeTaxaHierarchy($json);
            $res=json_decode(file_get_contents($url),true);
            $totalGbif=$res['count'];//ocurrencias georeferenciadas desde 1900 en gbif
            $totalInGbif=file_get_contents($url2);
            $offset = 0;
            $GenusObject=new Genero();
            $count = $totalInGbif;
            $yearCountGbif = countYears($genusKey, $count);
            $temporaryArray = array();
            if ($count > 300) {
                while ($count > 0) {
                    $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $genusKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
                    $content = file_get_contents($url);
                    $json = json_decode($content, true);
                    isset($json['publishingOrgKey']) ? $OrganizationKey = $json['publishingOrgKey'] : $OrganizationKey = 0;
                    $mesGbif = countMonths($genusKey);
                    foreach ($json['results'] as $i) {
                        $localArray=array(strval($i['decimalLongitude']),strval($i['decimalLatitude']));
                        array_push($coordinatesGBIFInPHP,$localArray );
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
                $url = 'http://api.gbif.org/v1/occurrence/search?taxonKey=' . $genusKey . '&HAS_COORDINATE=true&country=CL&limit=' . $count . '&offset=' . $offset;
                $content = file_get_contents($url);
                $json = json_decode($content, true);
                $mesGbif = countMonths($genusKey);
                foreach ($json['results'] as $i) {
                    $localArray=array(strval($i['decimalLongitude']),strval($i['decimalLatitude']));
                    array_push($coordinatesGBIFInPHP,$localArray );
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
            }
        }
        $categories=setCategoryDecades($yearCount,$yearCountGbif);
        $drillDownDataGbif=createDrilldown($yearCountGbif,$categories);
        $drillDownDataReuna=createDrilldown($yearCount,$categories);
        $categoryYears=setCategoryYears();
        $accumulatedYearsGbif=setAccumulatedYears($yearCountGbif);
        $accumulatedYearsReuna=setAccumulatedYears($yearCount);
        $organizationNames=getOrganizationNames($OrganizationKeyArray);
        $institutionNamesGBIF = $organizationNames[0];
        $institutionInfo=$organizationNames[1];
        uasort($institutionNamesGBIF,'cmpInst');
        $institutionDataReuna=setPieData($institutionNamesReuna);
        $institutionDataGbif=setPieData($institutionNamesGBIF);
        $childrenNames=getChildrenNames($genusKey);

        foreach($childrenNames as $key=>$value){
            array_push($stackedChildrensGbif,array('name'=>$key,'data'=>array($value), 'index'=>$value, 'legendIndex'=>$value,'pointWidth'=>28));
        }
        foreach($taxonChildrens as $key=>$value){
            array_push($stackedChildrensReuna,array('name'=>$key,'data'=>array($value), 'index'=>$value, 'legendIndex'=>$value,'pointWidth'=>28));
        }

        $GenusObject->setGenero($genusKey,$search,$totalReuna,$taxonChildrens,$totalReunaWithCoordinates,$totalGbif,$coordYearsREUNA,
            $coordYearsGBIF,$yearCountGbif,$institutionNamesReuna,$institutionNamesGBIF,$yearCount,$monthCount,$mesGbif,$drillDownDataGbif,$drillDownDataReuna,$accumulatedYearsGbif,$accumulatedYearsReuna,$categoryYears
            ,$coordinatesGBIFInPHP,$coordinatesReuna,$dataReuna,$dataGbif,$totalInGbif,$stackedChildrensReuna,$stackedChildrensGbif,$categories,$institutionDataReuna,
            $institutionDataGbif,$nameSpecieAuthor,$hierarchy);
        $GenusObject->setInstitutionInfo($institutionInfo);

        cache_set($search, $GenusObject, 'cache', 60*60*30*24); //30 dias
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