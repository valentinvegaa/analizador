<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 25-02-2015
 * Time: 11:23
 */
/**
 * Cuenta el numero de ocurrencias por mes.
 *
 * @return array con las ocurrencias
 * @param integer $taxonKey clave de la especie
 */
function countMonths($taxonKey)
{
    $returnVal = array();
    for ($i = 1; $i < 13; $i++) {
        $months = json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/search?taxonKey=' . $taxonKey . '&HAS_COORDINATE=true&country=CL&month=' . $i . '&limit=1'));
        $returnVal[$i - 1] = $months->count;
    }
    return $returnVal;
}
/**
 * Obtiene informacioón de las organizaciones.
 *
 * @return array con la informacion
 * @param array $organizations arreglo con los nombres de las organizaciones
 */
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
/**
 * Obtiene todas las especies asociadas a un genero
 *
 * @return array con las especies
 * @param integer $key clave numeria del genero
 */
function getChildrenNames($key){
    $children = json_decode(file_get_contents('http://api.gbif.org/v1/species/'.$key.'/children/?limit=300'), true);//106311492
    $result=array();
    foreach($children['results'] as $i){
        $count = json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/count?taxonKey='.$i['nubKey'].'&country=CL&isGeoreferenced=true'),true);
        if($count>0){
            $result[$i['species']]=$count;
        }
    };
    arsort($result);
    return $result;
}
/**
 * Obtiene todas las ocurrencias por decada
 *
 * @return array con las especies
 * @param array $data con numero de ocurrencias por año
 * @param integer $decada con decada especifica (decada del 50 igual 195)
 */
function getYears($data,$decada)
{
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
        if($temp!=(int)$trans){
            $years[$i]=(int)$trans;

        }
        else{
            $years[$i]=$temp;
        }
    }
    return $years;

}
/**
 * Obtiene las ocurrencias para una decada (1950 a 1959)
 *
 * @return array con las ocurrencias por año
 * @param array $data con numero de ocurrencias por año
 * @param integer $decada con decada especifica (decada del 50 igual 195)
 */
function getData($data,$decada) {
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
/**
 * Crea graficos para las decadas y su numero de registros
 *
 * @return array con los graficos y las decadas (eje x)
 * @param array $var con el numero de ocurrencias por año (1955:6)
 * @param array $categorias con las decadas para el grafico (1950,1960...)
 */
function createDrilldown($var,$categorias){
    $out=array();
    $decadas=array();
    $deca=array();
    $decadas2=$categorias;
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
                    'y' => suma($var, $deca[$i]),//error
                    'drilldown' => array(
                        'name' => $value,
                        'categories' => getYears($var, $deca[$i]),
                        'data' => getData($var, $deca[$i]),
                    )
                );//
                $i+=1;
            }
        }
        else{
            $out[$i] = array(
                'y' => suma($var, $deca[$i]),
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
/**
 * obtiene numero de ocurrencias acumuladas por año
 *
 * @return array con los graficos y las decadas correspondientes (eje x)
 * @param array $categorias con las decadas para el grafico, ejemplo (1950,1960...)
 */
function setAccumulatedYears($yearCount){
    $result=array();
    $year=date('Y');
    $n=0;
    $last=0;
    $anyo=115;
    $j=0;
    for($i=0;$i<=$anyo;$i++){

        if (array_key_exists($year - $anyo + $i, $yearCount)) {
            if ($yearCount[$year - $anyo + $i] != 0) {
                $n = $yearCount[$year - $anyo + $i];
                $j+=1;
            }
        } else {
            $n = 0;
            $j+=1;
        }
        $last += $n;
        if($j==1){
            array_push($result,$last);
            $j=0;}
    }
    return $result;
}
/**
 * establece años desde 1900 al actual
 *
 * @return array con los años
 *
 */
function setCategoryYears(){
    $result=array();
    $year=date('Y');
    $n=1900;
    while($n<=$year){

        $ene=strval($n);
        array_push($result,$ene);
        $n++;
    }
    return $result;
}
/**
 * Crea arreglo con informacion de las instituciones para ser graficadas
 *
 * @return array con datos para graficar
 * @param array $graph con la informacion de instituciones y su cantidad de registros
 */
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
/**
 * obtiene resultados de la busqueda en solr
 *
 * @return array
 * @param array $p con parametros de busqueda
 * @param object $s para conexion a Apache_Solr_Service
 * @param integer $limit numero de resultados
 */
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
/**
 * obtiene ancho
 *
 * @return integer con ancho
 * @param integer $a
 * @param integer $b
 */
function getWidth($a,$b){
    return $b*44/$a;
}
function getCountyName($coords,$r){
    $coordsWithCounty=array();
    if(false)//se arruinó la llave :(
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
/**
 * cuenta numero de ocurrencias por año
 *
 * @return array con numero de ocurrencias por año
 * @param integer $taxonKey clave para buscar
 * @param integer $count numero de resultados encontrados
 */
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

function getOrgArray($instInfo,$name){
    foreach($instInfo as $i){
        if(strcmp($i['title'],$name)==0){
            return $i;
        }
        else{}
    }
}
/**
 * suma numero de ocurrencias para una decada
 *
 * @return integer con la suma total
 * @param array $data con ocurrencias por año
 * @param integer $decada con decada a comparar
 */
function suma($data,$decada){
    $sum=0;
    foreach($data as $key=>$value){
        if(substr($key,0,3)==$decada) {
            $sum +=$value;//duda
        }
    }
    return $sum;
}
/**
 * obtiene taxonomia
 *
 * @return string con taxonomia
 * @param  object $i con informacion a filtrar
 */
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
//no sirve, solo permite 2500 por dia
function checkPosition($coords){
    for($i=0;$i<count($coords);$i++){
        $url='http://nominatim.openstreetmap.org/reverse?format=xml&lat='.$coords[$i][1].'&lon='.$coords[$i][0].'&zoom=18&addressdetails=1';
        $page=file_get_contents($url);
        $result=json_decode($page,true);
        var_dump($result);
    }
}

function cmp($a,$b){
    if ($a['data'][0] == $b['data'][0]) {
        return 0;
    }
    return ($b['data'][0] < $a['data'][0]) ? -1 : 1;
}
function cmpInst($a,$b){
    if ($a == $b) {
        return 0;
    }
    return ($b < $a) ? -1 : 1;
}
/**
 * obtiene generos y su cantidad para una familia dada
 *
 * @return array con generos y su cantidad
 * @param  integer $key con clave de familia
 */
function getFamilyGenus($key){//obtiene la cantidad de observaciones de cada genero asociado a la familia $key
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
function FamilyChildrens($key){

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
/**
 * Cambia palabra (singular o plural) para investigador
 *
 * @return string
 * @param  integer $var con numero de investigadores
 */
function setInvestigatorSingPlu($var){
    $tamano=count($var);
    if($tamano==0 or $tamano==1){
        return 'investigador ha';}
    else{
        return 'investigadores han';
    }
}
/**
 * Cambia palabra (singular o plural) para registros
 *
 * @return string
 * @param  integer $var con numero de registros
 */
function setRegistrosSingPlu($var){
    if($var==0 or $var==1)
    {return 'registro en ';}
    else{ return 'registros en ';}
}
/**
 * Cambia palabra (singular o plural) para años
 *
 * @return string
 * @param  integer $var con numero de años en Reuna
 */
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
/**
 * Cambia palabra (singular o plural) para años
 *
 * @return string
 * @param  integer $var con numero de años en Gbif
 */
function setYearSingPluGbif($var){
    $tamano=sizeof($var);
    reset($var);
    if($tamano==0 or $tamano==1){
        return 'año con registro en ';}
    else{
        return 'años con registros en ';
    }
}
/**
 * Cambia palabra (singular o plural) para organizacion
 *
 * @return string
 * @param  integer $var con numero de organizaciones
 */
function setOrganizationSingPlu($var){
    $tamano=sizeof($var);
    if($tamano==0 or $tamano==1){
        return 'organización ha';}
    else{
        return 'organizaciones han';
    }
}
/**
 * Cuenta el numero de ocurrencias por mes.
 *
 * @return array con las ocurrencias
 * @param integer $taxonKey clave de la especie
 */
function getCountMonths($taxonKey)
{
    $returnVal = array();
    for ($i = 1; $i < 13; $i++) {
        $months = json_decode(file_get_contents('http://api.gbif.org/v1/occurrence/search?taxonKey=' . $taxonKey . '&HAS_COORDINATE=true&country=CL&month=' . $i . '&limit=1'));
        $returnVal[$i - 1] = $months->count;
    }
    return $returnVal;
}
/**
 * cuenta numero de ocurrencias por año
 *
 * @return array con numero de ocurrencias por año
 * @param integer $taxonKey clave para buscar
 * @param integer $count numero de resultados encontrados
 */
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
/**
 * suma numero de ocurrencias para una decada
 *
 * @return integer con la suma total
 * @param array $data con ocurrencias por año
 * @param integer $decada con decada a comparar
 */
function getSuma($data,$decada){
    $sum=0;
    foreach($data as $key=>$value){
        if(substr($key,0,3)==$decada) {
            $sum +=$value;//duda
        }
    }
    return $sum;
}
/**
 * establece las categorias en decadas para los graficos de registros anuales
 *
 * @return array con las categorias
 * @param array $var1 con coordenadas de años
 * @param array $var2 con coordenadas de años
 */
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
