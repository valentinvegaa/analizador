<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 25-09-2014
 * Time: 23:35
 */

$desc_chart_1 = variable_get('desc_chart_1');
$desc_chart_2 = variable_get('desc_chart_2');
$desc_chart_3 = variable_get('desc_chart_3');
$path = $GLOBALS['base_url'] . '/' . drupal_get_path('module', 'analizador_biodiversidad');
//echo $path . '_path';
echo 'specieskey: ' . $speciesKey;
//var_dump($yearCountGbif);
//var_dump($categoriesGBIF);
?>
<!--<div>
    <form  accept-charset="utf-8" method="post">
        <input id="qw" class="busqueda" name="qw" type="text" size="35" value="<?php //echo htmlspecialchars($specie, ENT_QUOTES, 'utf-8'); ?>"/>
        <input type="submit" value="Search"/>
    </form>
</div>-->
<div class="nombre-completo"><span style="color: darkgray">ESPECIE </span><?php if (isset($search[0])) echo $search[0].' '.$search[1]; ?>
</div>
<div style="font-size: 1.2em;">Se encontraron <b><?php echo $totalReuna; ?></b> observaciones asociadas en la base de datos <?php echo $REUNA; ?></div>
Explore los resultados:
<div style="margin:20px 0 20px 0;">
    <!--<div class="top-index">
        <div class="title-a">Composición Taxonómica</div>
        <div class="line"><a href="#ReunaStacked"><span><?php //echo sizeof($taxonChildrens);?> Géneros</span> en la base de datos REUNA.</a></div>
        <div class="line"><a href="#GbifStacked"><span><?php //echo sizeof($familyChildrens);?> Géneros</span> en la base de datos GBIF.</a></div>
        <div class="endline">Último género de la Familia ingresado a REUNA:  <span><?php //ultimo taxon menor?>"leptochiton"</span></div>
    </div>-->
    <div class="bottom-index">
        <div class="left">
            <div id="left-index-a">
                <div class="title-b"><a href="#temporal">Distribución Temporal</a></div>
                <div class="line"><span class="bignumber"><?php echo sizeof($yearCount);?></span> años con registros en la base de datos <?php echo $REUNA; ?></div>
                <div class="line"><span class="bignumber"><?php echo sizeof($yearCountGbif)?></span> años con registros en la base de datos Gbif</div>
                <div class="endline">Periodo de registros <?php echo $REUNA; ?>: <span class="bignumber"><?php reset($yearCount);echo key($yearCount).' - ';end($yearCount);echo key($yearCount);?></div>
                <div class="endline">Periodo de registros Gbif: <span class="bignumber"><?php reset($yearCountGbif);echo key($yearCountGbif).' - ';end($yearCountGbif);echo key($yearCountGbif);?></span></div>
            </div>
            <div id="left-index-b">
                <div class="title-b"><a href="#institucion">Instituciones</a></div>
                <div class="line"><span class="bignumber"><?php echo sizeof($institutionNamesReuna)?></span> Organismos (<?php echo $REUNA; ?>) han contribuido con registros de la Especie <?if (isset($search[0])) echo $search[0].' '.$search[1];?></div>
                <div class="line"><span class="bignumber"><?php echo sizeof($institutionNamesGBIF)?></span> Organismos (GBIF) han contribuido con registros de la Especie <?if (isset($search[0])) echo $search[0].' '.$search[1];?></div>

            </div>
        </div>
        <div id="top-rb-index">
            <div class="title-a"><a href="#geografica">Distribución Geográfica</a></div>
            <div class="line"><span class="bignumber"><?php echo $totalReunaConCoordenadas; ?></span> Ocurrencias Georeferenciadas en la base de datos <?php echo $REUNA; ?></div>
            <div class="line"><span class="bignumber"><?php echo $totalGBIF; ?></span> Ocurrencias Georeferenciadas en la base de datos Gbif</div>
            <div class="endline"><span class="bignumber"><?php //numero de regiones?></span> Regiones presentes</div>
        </div>
    </div>
</div>
<div class="wraper-container">
    <div class="left" id="temporal">
        <div class="title-a subtitulo">Distribución Temporal</div>
        <div class="parrafo"><?php echo $desc_chart_1['value']; ?></div>
        <div id="contribucionBarrasREUNA"></div>
        <div id="contribucionBarrasGBIF"></div>
        <div class="parrafo"><?php echo $desc_chart_2['value']; ?></div>
        <div id="reunaGbifBarras"></div>
    </div>
    <div id="containers" class="containers">
        <div class="title-a subtitulo" id="geografica">Distribución Geográfica</div>
        <div style="margin-left:10px;"><span style="font-size: 1.3em;"></span>De un total de <?php echo $totalReuna; ?> observaciones , existen <?php echo $totalReunaConCoordenadas; ?> ocurrencias Georeferenciadas, correspondiente al  <?php echo round($totalReunaConCoordenadas*100/$totalReuna,1);?>% de las ocurrencias.</div>
        <div id="mapContainer" class="mapContainer">
            <div style="font-weight: bold;text-align: center"><?php echo $REUNA; ?></div>
        </div>
        <div id="mapContainerGBIF" class="mapContainerGBIF">
            <div style="font-weight: bold;text-align: center">GBIF</div>
        </div>
        <div id="slider-range" style="clear: both; bottom: -30px;"></div>
        <input type="text" id="amount" readonly
               style="border:0; position:relative; bottom: -30px; color:#f6931f; font-weight:bold;">
    </div>
</div>
<div id="acumuladas" style="margin-left: 10px;"></div>
<div class="wraper-container" style="padding-top: 40px;">
    <div class="title-a subtitulo" id="institucion">Instituciones</div>
    <div class="parrafo"><?php echo $desc_chart_3['value']; ?></div>
    <div style="width: 45%;float:left"><b>Contributors</b> to <?php if (isset($specie)) echo $specie; ?> records <span
            style="color: darkgray"><?php echo $REUNA; ?> Database</span></div>
    <div style="width: 45%;float:right"><b>Contributors</b> to <?php if (isset($specie)) echo $specie; ?> records <span
            style="color: darkgray">GBIF Database</span></div>
    <!--<div id="institucionBar" class="institucionBar"></div>-->
    <div id="institucionPieREUNA" class="institucionPie"></div>

    <div id="institucionPieGBIF" class="institucionPie"></div>
    <div id="REUNATable"><?php
        print '<div class="tableElement"><div style="color: #444444;font-weight: bold;width:85%;float: left">Institution</div><div style="color: #444444;font-weight: bold;width:15%;float: right">Registros</div></div>';
        foreach($institutionNamesReuna as $elemento){
            print '<div class="tableElement"><div class="key">'.$elemento[0].'</div><div class="value">'.$elemento[1].'</div></div>';
        }
        ?></div>
    <div id="GBIFTable"><?php
        print '<div class="tableElement"><div style="color: #444444;font-weight: bold;width:85%;float: left">Institution</div><div style="color: #444444;font-weight: bold;width:15%;float: right">Registros</div></div>';
        foreach($institutionNamesGBIF as $key=>$value){
            print '<div class="tableElement"><div class="key">'.$value[0].'</div><div class="value">'.$value[1].'</div></div>';
        }
        ?></div>
</div>
<script>
function changeFeatures(first, last) {
    source.clear();
    sourceGBIF.clear();

    var newFeatures = [];
    var j = 0;
    var k = 0;
    for (var i = 0; i < arrayCoordinatesInJS.length - 1; i += 2) {
        if (coordYearsReuna[k] <= last && coordYearsReuna[k] >= first) {
            var coordinate = [parseFloat(arrayCoordinatesInJS[i+1]), parseFloat(arrayCoordinatesInJS[i])];
            var tempLonlat = ol.proj.transform(coordinate, 'EPSG:4326', 'EPSG:3857');
            newFeatures[j] = new ol.Feature(new ol.geom.Point(tempLonlat));
            j++;
        }
        k++;
    }
    ;
    var newFeaturesGBIF = [];
    var j = 0;
    var k = 0;
    for (var i = 0; i < arrayCoordinatesGBIFInJS.length - 1; i += 2) {
        if (first <= coordYearsGBIF[k] && coordYearsGBIF[k] <= last) {
            var coordinateGBIF = [parseFloat(arrayCoordinatesGBIFInJS[i]), parseFloat(arrayCoordinatesGBIFInJS[i+1])];
            var tempLonlatGBIF = ol.proj.transform(coordinateGBIF, 'EPSG:4326', 'EPSG:3857');
            newFeaturesGBIF[j] = new ol.Feature(new ol.geom.Point(tempLonlatGBIF));
            j++;
        }
        k++;
    }
    ;
    //console.log(j);
    sourceGBIF.addFeatures(newFeaturesGBIF);
    source.addFeatures(newFeatures);
}
(function ($) {
    var fecha = new Date();
    var today = fecha.getFullYear();
    Drupal.behaviors.yourThemeSlider = {
        attach: function (context, settings) {
            var steps = ['-1', '1989', '1900', '1910', '1920', '1930', '1940', '1950', '1960', '1970', '1980', '1990', '2000', '2010', today];
            $("#slider-range").slider({
                range: true,
                min: 0,
                max: 14,
                step: 1,
                values: [0, 14],
                slide: function (event, ui) {
                    $("#amount").val((steps[ui.values[0]] == -1 ? 'Sin año' : (steps[ui.values[0]] == 1989 ? 'Antes de 1900' : steps[ui.values[0]])) + " - " + (steps[ui.values[1]] == -1 ? 'Sin año' : (steps[ui.values[1]] == 1989 ? 'Antes de 1900' : steps[ui.values[1]])));
                    changeFeatures(steps[ui.values[0]], steps[ui.values[1]]);
                }
            });
            $("#amount").val('Sin año' + " - " + steps[$("#slider-range").slider("values", 1)]);
        }
    };
    var chartREUNA, chartAccumulated, colors = Highcharts.getOptions().colors;
    var chartGBIF;
    var decadas = [];

    function setChart(daChart, name, categories, data, color) {
        daChart.xAxis[0].setCategories(categories);
        daChart.series[0].remove();
        daChart.addSeries({
            name: name,
            data: data,
            color: color || 'white'
        });
    }

    function setPieData(graph) {
        var data = graph;
        var outPie = [];
        var i = 0;
        var outBar = [];
        var categorias = []
        for (var x in data) {
            outPie[i] = [x, data[x]];
            categorias[i] = x;
            outBar[i] = {y: data[x], color: colors[i]};
            i++;
        }
        var out = [outPie, categorias, outBar];
        return out;
    }

    $(document).ready(function ($) {
        var graph =<?php echo json_encode($institutionNamesReuna); ?>;
        var name = 'Decada';
        var yearCount =<?php echo json_encode($yearCount); ?>;
        var yearCountGbif=<?php echo json_encode($yearCountGbif); ?>;
        var accumulatedData=<?php echo json_encode($accumulatedYearsReuna);?>;
        var accumulatedDataGbif=<?php echo json_encode($accumulatedYearsGbif);?>;
        var tempREUNA=<?php echo json_encode($DrillDownDataReuna); ?>;
        var dataREUNA = tempREUNA[0];
        var instNames=<?php echo json_encode($institutionNamesGBIF); ?>;
        var catNames=<?php echo json_encode($categoriesGBIF); ?>;
        var tempGBIF=<?php echo json_encode($DrillDownDataGbif); ?>;
        var dataGBIF = tempGBIF[0];
        var monthCountReuna =<?php echo json_encode($monthCountReuna); ?>;
        var monthCountGBIF =<?php echo json_encode($mesGbif); ?>;
        var categoryYears=<?php echo json_encode($categoryYears)?>;

        $('#institucionPieREUNA').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
                spacingTop: 0,
                spacingLeft: 0,
                marginTop: 0,
                marginLeft: 0
            },
            title: {
                text: null,
                style: '"fontSize": "14px"'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> con <b>{point.y} Registros</b>'
            },
            plotOptions: {
                pie: {
                    size: 250,
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            credits: {
                enabled: false
            },
            legend: {},
            series: [{
                type: 'pie',
                name: 'Total',
                data: graph//data[0]
            }]
        });

        $('#institucionPieGBIF').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
                spacingTop: 0,
                spacingLeft: 0,
                marginTop: 0,
                marginLeft: 0
            },
            title: {
                text: null,
                style: '"fontSize": "14px"'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> con <b>{point.y} Registros</b>'
            },
            plotOptions: {
                pie: {
                    size: 250,
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            credits: {
                enabled: false
            },
            legend: {},
            series: [{
                type: 'pie',
                name: 'Total',
                data: instNames
            }]
        });

        chartREUNA = new Highcharts.Chart({
            chart: {
                renderTo: 'contribucionBarras<?php echo $REUNA; ?>',
                type: 'column'
            },
            title: {
                text: null
            },
            xAxis: {
                categories: tempREUNA[1]
            },
            yAxis: {
                title: {
                    text: 'Observ. <?php echo $REUNA; ?>',
                    style: {
                        color: '#000000',
                        fontSize: '12px',
                        fontWeight: 'bold'
                    }
                }
            },
            plotOptions: {
                column: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {
                                var drilldownREUNA = this.drilldown;
                                if (drilldownREUNA) { // drill down
                                    setChart(chartREUNA, drilldownREUNA.name, drilldownREUNA.categories, drilldownREUNA.data, drilldownREUNA.color);
                                } else { // restore
                                    setChart(chartREUNA, name, tempREUNA[1], dataREUNA);
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        color: '#00000',
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function () {
                            return this.y != 0 ? this.y : null;
                        }
                    }
                }
            },
            tooltip: {
                formatter: function () {
                    var point = this.point,
                        s = this.x + ':<b>' + this.y + '</b><br/>';
                    if (point.drilldown) {
                        s += 'Click to expand to ' + point.category;
                    } else {
                        s += 'Click to return.';
                    }
                    return s;
                }
            },
            series: [{
                name: name,
                data: dataREUNA,
                color: 'white'
            }],
            exporting: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            legend: {
                //layout: 'vertical',
                //align: 'right',
                /*floating: true,*/
                //x:0,
                //y:-150
                /*backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                 shadow: true*/
            }
        });

        chartGBIF = new Highcharts.Chart({
            chart: {
                renderTo: 'contribucionBarrasGBIF',
                type: 'column'
            },
            title: {
                text: null
            },
            xAxis: {
                categories: tempGBIF[1]
            },
            yAxis: {
                title: {
                    text: 'Observ. GBIF',
                    style: {
                        color: '#000000',
                        fontSize: '12px',
                        fontWeight: 'bold'
                    }
                }
            },
            plotOptions: {
                column: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {
                                var drilldown = this.drilldown;
                                if (drilldown) { // drill down
                                    setChart(chartGBIF, drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                                } else { // restore
                                    setChart(chartGBIF, name, tempGBIF[1], dataGBIF);
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        color: '#00000',
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function () {
                            return this.y != 0 ? this.y : null;
                        }
                    }
                }
            },
            tooltip: {
                formatter: function () {
                    var point = this.point,
                        s = this.x + ':<b>' + this.y + '</b><br/>';
                    if (point.drilldown) {
                        s += 'Click to expand to ' + point.category;
                    } else {
                        s += 'Click to return.';
                    }
                    return s;
                }
            },
            series: [{
                name: name,
                data: dataGBIF,
                color: 'white'
            }],
            exporting: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            legend: {
                //layout: 'vertical',
                //align: 'right',
                /*floating: true,*/
                //x:0,
                //y:-150
                /*backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                 shadow: true*/
            }
        });


        $('#reunaGbifBarras').highcharts({
            chart: {
                type: 'column'
            },
            credits: {
                enabled: false
            },
            title: {
                text: null,
                style: '"fontSize": "14px"'
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                min: 0,
                title: {
                    text: '<b>Observaciones</b>'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                series: {
                    pointWidth: 10
                }
            },
            series: [{
                name: '<?php echo $REUNA; ?>',
                data: monthCountReuna//[1, 1, 2, 3, 5, 8, 5, 4, 3, 2, 1, 0]//monthCount//
            },
                {
                    name: 'GBIF',
                    data: monthCountGBIF//[2, 2, 4, 6, 10, 16, 26, 41, 68, 110, 178, 281]
                }]
        });
        $('#acumuladas').highcharts({
            chart: {
                type: 'area'
            },
            credits: {
                enabled: false
            },
            title: {
                text: 'Observaciones Acumuladas',
                style: '"fontSize": "12px"'
            },
            xAxis: {
                categories: categoryYears,
                labels: {
                    rotation: 90
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '<b>Observaciones</b>'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:8px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                area: {
                    //pointStart: 1940,
                    marker: {
                        enabled: false,
                        symbol: 'circle',
                        radius: 2,
                        states: {
                            hover: {
                                enabled: true
                            }
                        }
                    }
                }
            },
            series: [{
                name: 'Observaciones acumuladas <?php echo $REUNA; ?>',
                /*Datos de prueba*/
                data:accumulatedData//[1, 1, 2, 3, 5, 8, 5, 4, 3, 2,1, 1, 2, 3, 5, 8, 5, 4, 3, 2]//accumulatedData
            },{
                name:'Observaciones acumuladas GBIF',
                /*Datos de prueba*/
                data: accumulatedDataGbif//[1, 1, 6, 9, 10, 8, 3, 1,1, 6,1, 1, 2, 7, 5, 6, 5, 9, 9, 9]
            }]
        });

    });
})(jQuery);
var arrayCoordinatesInJS =<?php echo json_encode($coordinatesReuna);//if($coordinatesReuna!="")echo "[".$coordinatesReuna."]";else{echo "[]";}?>;
var arrayCoordinatesGBIFInJS =<?php if($coordinatesGBIFInPHP!="")echo "[".$coordinatesGBIFInPHP."]";else{echo "[]";}?>;
console.log(arrayCoordinatesGBIFInJS);
var coordYearsReuna =<?php if(isset($coordYearsREUNA)&&$coordYearsREUNA!="")echo "[".$coordYearsREUNA."]";else{echo "[]";}?>;
var coordYearsGBIF =<?php if(isset($coordYearsGBIF)&&$coordYearsGBIF!="")echo "[".$coordYearsGBIF."]";else{echo "[]";}?>;

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
    minZoom: 2,
    maxZoom: 18
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

var geoJsonSource = new ol.source.GeoJSON({
    projection: 'EPSG:3857',
    url: '<?php echo $path;?>/regiones/regiones.json'
    //url: '<?php echo $path;?>/regiones/cuads25k_ll.geojson'
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
    target: 'mapContainer',
    layers: [capaOsm, geoJson, clusters],
    renderer: 'canvas',
    view: mapView
});
var largoGBIF = (arrayCoordinatesGBIFInJS.length) / 2;
if (largoGBIF > 0) {
    var featuresGBIF = new Array(largoGBIF);
    var j = 0;
    for (var i = 0; i < arrayCoordinatesGBIFInJS.length - 1; i += 2) {
        //alert(arrayCoordinatesInJS[i] + " " + arrayCoordinatesInJS[i+1]);
        var coordinateGBIF = [arrayCoordinatesGBIFInJS[i], arrayCoordinatesGBIFInJS[i + 1]];
        var tempLonlatGBIF = ol.proj.transform(coordinateGBIF, 'EPSG:4326', 'EPSG:3857');
        //var tempLonlat = [arrayCoordinatesInJS[i], arrayCoordinatesInJS[i+1]];
        featuresGBIF[j] = new ol.Feature({'visible': 'true'});
        featuresGBIF[j].setGeometry(new ol.geom.Point(tempLonlatGBIF));
        j++;
    }
    ;
}
var sourceGBIF = new ol.source.Vector({
    features: featuresGBIF
});
var clusterSourceGBIF = new ol.source.Cluster({
    distance: 2,
    source: sourceGBIF
});
var rawGBIF = new ol.layer.Vector({source: sourceGBIF});
var styleCacheGBIF = {};
var clustersGBIF = new ol.layer.Vector({
    source: clusterSourceGBIF,
    style: function (feature, resolution) {
        var size = feature.get('features').length;
        var style = styleCacheGBIF[size];
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
            styleCacheGBIF[size] = style;
        }
        return style;
    }
});
var mapGBIF = new ol.Map({
    target: 'mapContainerGBIF',
    layers: [capaOsm, geoJson, clustersGBIF],
    renderer: 'canvas',
    view: mapView
});
/***************************************/
var selectClick = new ol.interaction.Select({
    condition: ol.events.condition.click,
    style: new ol.style.Style({
        fill: new ol.style.Fill({
            color: 'rgba(255, 0, 0, 0.2)'
        }),
        stroke: new ol.style.Stroke({
            color: '#000000',
            width: 0.5
        })
    })
});

var collection = selectClick.getFeatures();
collection.on('add', function () {
    collection = selectClick.getFeatures();
    var newFeaturesGBIF=[];
    var index=0;
    collection.forEach(function (f) {
            var text = f.get('NOMBRE');
            //console.log(text);

            changeFeatures(-1,3000);

            sourceGBIF.forEachFeature(function (e) {
                if (ol.extent.containsCoordinate(f.getGeometry().getExtent(), e.getGeometry().getCoordinates())) {
                    //console.log('adentro');
                    newFeaturesGBIF[index]=e;
                    index++;
                }
                else {
                    //console.log('afuera');
                }
            });
        }
    );
    sourceGBIF.clear();
    sourceGBIF.addFeatures(newFeaturesGBIF);

    //console.log(featuresinBox);
    //featuresSelected.push(e);
});
collection.on('remove', function () {
    changeFeatures(-1,3000);
});
var changeInteraction = function () {
    mapGBIF.addInteraction(selectClick);
    //mapGBIF.addInteraction(selectClickGBIF);
};
changeInteraction();
/***************************************/
mapGBIF.bindTo('view', map);
</script>