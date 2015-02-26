<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 28-01-2015
 * Time: 15:24
 */
$path = $GLOBALS['base_url'] . '/' . drupal_get_path('module', 'analizador_biodiversidad');
?>
<!--<div>
    <form  accept-charset="utf-8" method="post">
        <input id="qw" class="busqueda" name="qw" type="text" size="35" value="<?php //echo htmlspecialchars($specie, ENT_QUOTES, 'utf-8'); ?>"/>
        <input type="submit" value="Search"/>
    </form>
</div>-->
<div class="nombre-completo"><span style="color: darkgray">INSTITUCIÓN </span><?php if (isset($search)) echo $search; ?>
</div>
<div style="font-size: 1.2em;">Se encontraron <b><?php echo $totalReuna; ?></b> observaciones asociadas en la base de datos REUNA</div>
Explore los resultados:
<div id="index">
    <div id="left-index">
        <div id="left-t-index">
            <div class="title-a">Composición Taxonómica</div>
            <div class="line"><a href="#ReunaStacked"><span><?php ?> Especies</span> en la base de datos REUNA.</a></div>
            <div class="line"><a href="#GbifStacked"><span><?php //sizeof($familyChildrens);?> Especies</span> en la base de datos GBIF.</a></div>
            <div class="endline">Última especie del Genero ingresado a REUNA:  <span><?php //ultimo taxon menor?>"leptochiton"</span></div>
        </div>
        <div id="left-b-index">
            <div class="title-a"><a href="#geografica">Distribución Geográfica</a></div>
            <div class="line"><span class="bignumber"><?php echo $totalReunaConCoordenadas; ?></span> Ocurrencias Georeferenciadas en la base de datos Reuna</div>
            <div class="line"><span class="bignumber"><?php echo $totalGBIF; ?></span> Ocurrencias Georeferenciadas en la base de datos Gbif</div>
            <div class="endline"><span class="bignumber"><?php //numero de regiones?></span> Regiones presentes</div>
        </div>
    </div>
    <div id="right-index">
        <div id="right-t-index">
            <div class="title-b"><a href="#temporal">Distribución Temporal</a></div>
            <div class="line"><span class="bignumber"><?php echo sizeof(array_unique(explode(', ',$coordYearsREUNA)))?></span> años con registros en la base de datos Reuna</div>
            <div class="line"><span class="bignumber"><?php echo sizeof($yearCountGbif)?></span> años con registros en la base de datos Gbif</div>
            <div class="endline">Periodo de registros Reuna: <span class="bignumber"><?php $var=explode(',',$coordYearsREUNA);ksort($var);echo $var[count($var)-2].' - '.$var[0]?></span></div>
            <div class="endline">Periodo de registros Gbif: <span class="bignumber"><?php $var=explode(',',$coordYearsGBIF);ksort($var);echo $var[count($var)-2].' - '.$var[0]?></span></div>
        </div>
        <div id="right-b-index">
            <div class="title-b"><a href="#institucion">Instituciones</a></div>
            <div class="line"><span><?php echo sizeof($institutionNamesReuna)?></span> Organismos (REUNA) han contribuido con registros de la Familia <?if (isset($search)) echo $search;?></div>
        </div>
    </div>
</div>
<div class="wraper-container" style="padding-top: 40px;">
    <div id="rank"></div>
    <div>
        <div>Años en los que participa esta entidad</div>
        <div id="participacionAnual"></div>
        <div id="anyos">Grafico de barras participacion anual, ultimos 80 años para esta entidad</div>
        <div>Años acumulados en los que participa esta entidad</div>
        <div id="anyos">Grafico de lineas participacion anual acumulada, ultimos 80 años años para esta entidad</div>
    </div>
    <div id="mapContainerHorizontal" class="mapContainerHorizontal"></div>
    <div style="margin-bottom: 50px"><div class="izq">Nombre region</div><div class="der">participacion</div></div>
    <div style="">
        <div id="tabla-especies" style="width:450px;float:left"><?php print $tablaEspecies;?></div>
        <div id="tabla-generos" style="width:450px;float: right;"><?php print $tablaGeneros;?></div>
    </div>
    <div><div id="tabla-familias" style="width:380px;float:left"><?php print $salida;?></div></div>
</div>
<script>
(function ($) {
    $(document).ready(function ($) {
        var categories;
        var sumaTotal=0;
        var rankChart=new Highcharts.Chart({
            chart: {
                renderTo: 'rank',
                type: 'bar'
            },
            title: {
                text: 'Mayores contribuyentes a Reuna'
            },
            //subtitle: {text: 'Source: Wikipedia.org'},
            xAxis: {
                categories: ['Contribución'],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Observaciones',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                formatter: function () {
                    var pcnt = (this.y / sumaTotal) * 100;
                    return '<div style="font-size: 0.8em">Contribución total</div><br> ' + this.series.name +
                    ': <b>' + this.y +' con el '+ Highcharts.numberFormat(pcnt) + '%' +'</b>';
                },
                valueSuffix: ' Observaciones'
            },
            plotOptions: {
                bar: {
                    dataLabels:{
                        enabled:true,
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 150,
                floating: true,
                borderWidth: 1,
                backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: []
        });
        var partChart=new Highcharts.Chart({
            chart: {
                renderTo: 'participacionAnual',
                type: 'areaspline'
            },
            title: {
                text: 'Participacion anual'
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 150,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },
            xAxis: {
                categories:<?php echo json_encode($categoriasPart)?>,
                labels:
                {
                    enabled: true,
                    step: 5,
                    staggerLines: 1
                }
            },
            yAxis: {
                title: {
                    text: 'Observaciones'
                }
            },
            tooltip: {
                shared: true,
                valueSuffix: ' observaciones'
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                areaspline: {
                    fillOpacity: 0.5
                },
                series:{
                    marker: {
                        enabled: false
                    }
                }
            },
            series: []
        });
        var participacion=<?php echo json_encode($participacion)?>;
        console.log(participacion);
        partChart.addSeries(participacion);
        var series=<?php echo json_encode($series);?>;
        for(var x in series){
            rankChart.addSeries(series[x]);
            sumaTotal+=parseInt(series[x]['data']);
        }
    });
})(jQuery);
    var arrayCoordinatesInJS =[];<?php //echo json_encode($coordinatesReuna);?>
    var largo = (arrayCoordinatesInJS.length);
    if (largo > 0) {
        var features = new Array(largo);
        for (var i = 0; i < arrayCoordinatesInJS.length; i ++) {
            //alert(arrayCoordinatesInJS[i] + " " + arrayCoordinatesInJS[i+1]);
            var coordinate = [parseFloat(arrayCoordinatesInJS[i][1]), parseFloat(arrayCoordinatesInJS[i][0])];
            var tempLonlat = ol.proj.transform(coordinate, 'EPSG:4326', 'EPSG:3857');
            //var tempLonlat = [arrayCoordinatesInJS[i], arrayCoordinatesInJS[i+1]];
            features[i] = new ol.Feature(new ol.geom.Point(tempLonlat));
        }
    }
    var mapView = new ol.View({
        projection: 'EPSG:3857',
        center: ol.proj.transform([-72.184306, -36.398612], 'EPSG:4326', 'EPSG:3857'),
        zoom: 4,
        rotation: -Math.PI / 2,
        minZoom: 4,
        maxZoom: 4
    });
    var source = new ol.source.Vector({
        features: features
    });

    var clusterSource = new ol.source.Cluster({
        distance: 2,
        source: source
    });
    function getColor(index) {
        var colors = ['#FFFF00', '#FFD700', '#FFA500', '#FF8C00', '#FF4500', '#FF2000'];
        return colors[index] ? colors[index] : '#FF2000';
    }
    var capaOsm = new ol.layer.Tile({source: new ol.source.MapQuest({layer: 'osm'})});
    var raw = new ol.layer.Vector({source: source});
    var styleCache = {};
    var clusters = new ol.layer.Vector({
        source: clusterSource,
        style: function (feature, resolution) {
            var size = feature.get('features').length;
            var style = styleCache[size];
            if (!style) {
                style = [new ol.style.Style({
                    image: new ol.style.Circle({
                        radius: 5,
                        stroke: new ol.style.Stroke({
                            color: '#434348'
                        }),
                        fill: new ol.style.Fill({
                            color: getColor(size)//'#f7a35c'
                        })
                    })
                })];
                styleCache[size] = style;
            }
            return style;
        }
        //projection: ol.proj.get('EPSG:4326')
    });
    console.log('<?php echo $path;?>/regiones/regiones.geojson');
    var geoJsonSource = new ol.source.GeoJSON({
        projection: 'EPSG:3857',
        url: '<?php echo $path;?>/regiones/regiones.json'//regiones.geojson
        //url: '<?php //echo $path;?>/regiones/cuads25k_ll.geojson'
    });
    var geoJson = new ol.layer.Vector({
        title: 'Regiones',
        source: geoJsonSource,
        style: function (feature) {
            var text = feature.get('NOMBRE');
            if (!styleCache[text] && text == 'Region') {//cambiar Region por nombre para dar estilo particular
                styleCache[text] = [new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: 'rgba(255, 255, 0, 0.5)'
                    }),
                    stroke: new ol.style.Stroke({
                        color: '#319FD3',
                        width: 1
                    }),
                    zIndex: 999
                })];
            }
            else {
                if (!styleCache[text]) {
                    styleCache[text] = [new ol.style.Style({
                        fill: new ol.style.Fill({
                            color: 'rgba(0, 0, 255, 0.2)'
                        }),
                        stroke: new ol.style.Stroke({
                            color: '#FFC54D',
                            width: 0.5
                        }),
                        zIndex: 999
                    })];
                }
            }
            return styleCache[text];
        }

        /*style: new ol.style.Style({
         stroke: new ol.style.Stroke({color: 'blue', width: 0.5
         }),
         fill: new ol.style.Fill({
         color: 'rgba(255, 255, 0, 0.5)'
         })
         })*/
    });
    var map = new ol.Map({
        target: 'mapContainerHorizontal',
        layers: [capaOsm, geoJson, clusters],
        renderer: 'canvas',
        view: mapView
    });
    console.log('pasa');
</script>