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
echo isset($familyKey) ? $familyKey : '';
//var_dump($speciesFound);
//var_dump($yearCountGbif);
?>
<!--<div>
    <form  accept-charset="utf-8" method="post">
        <input id="qw" class="busqueda" name="qw" type="text" size="35" value="<?php //echo htmlspecialchars($specie, ENT_QUOTES, 'utf-8'); ?>"/>
        <input type="submit" value="Search"/>
    </form>
</div>-->
<div class="nombre-completo"><span style="color: darkgray">FAMILIA </span><?php if (isset($family)) echo $family; ?>
</div>
<div style="font-size: 1.2em;">Se encontraron <b><?php echo $totalReuna; ?></b> observaciones asociadas en la base de datos <?php echo $REUNA; ?></div>
Explore los resultados:
<div id="index">
    <div id="left-index">
        <div id="left-t-index">
            <div class="title-a">Composición Taxonómica</div>
            <div class="line"><a href="#ReunaStacked"><span><?php echo sizeof($speciesFound); ?> Especies</span> en la base de datos <?php echo $REUNA; ?>.</a></div>
            <div class="line"><a href="#GbifStacked"><span><?php //sizeof($familyChildrens);?> Especies</span> en la base de datos GBIF.</a></div>
            <div class="line">
                <b><?php echo isset($countSpecies)?$countSpecies:''; ?> Especies</b> (<?php echo sizeof($speciesFound); ?> especies encontradas en CHILE)<br>
            </div>
            <div class="endline">Última especie del Genero ingresado a <?php echo $REUNA; ?>:  <span><?php //ultimo taxon menor?>"leptochiton"</span></div>
        </div>
        <div id="left-b-index">
            <div class="title-a"><a href="#geografica">Distribución Geográfica</a></div>
            <div class="line"><span class="bignumber"><?php echo $totalReunaConCoordenadas; ?></span> Ocurrencias Georeferenciadas en la base de datos <?php echo $REUNA; ?></div>
            <div class="line"><span class="bignumber"><?php echo $totalGBIF; ?></span> Ocurrencias Georeferenciadas en la base de datos GBIF</div>
            <div class="endline"><span class="bignumber"><?php //numero de regiones?></span> Regiones presentes</div>
        </div>
    </div>
    <div id="right-index">
        <div id="right-t-index">
            <div class="title-b"><a href="#temporal">Distribución Temporal</a></div>
            <div class="line"><span class="bignumber"><?php echo sizeof($yearCount)?></span> años con registros en la base de datos <?php echo $REUNA; ?></div>
            <div class="line"><span class="bignumber"><?php echo sizeof($yearCountGbif)?></span> años con registros en la base de datos GBIF</div>
            <div class="endline">Periodo de registros <?php echo $REUNA; ?>: <span class="bignumber"><?php reset($yearCount);echo key($yearCount).' - ';end($yearCount);echo key($yearCount);?></span></div>
            <div class="endline">Periodo de registros GBIF: <span class="bignumber"><?php reset($yearCountGbif);echo key($yearCountGbif).' - ';end($yearCountGbif);echo key($yearCountGbif);?></span></div>
        </div>
        <div id="right-b-index">
            <div class="title-b"><a href="#institucion">Instituciones</a></div>
            <div class="line"><span><?php echo sizeof($institutionNames)?></span> Instituciones (<?php echo $REUNA; ?>) han contribuido con registros de la Familia <?if (isset($family)) echo $family;?></div>
            <div class="line"><span><?php echo sizeof($institutionNamesGBIF)?></span> Instituciones (GBIF) han contribuido con registros de la Familia <?if (isset($family)) echo $family;?></div>

        </div>
    </div>
</div>
<div class="wraper-container" style="border: thick; border-color: black;">
    <div class="left" id="temporal">
        <div class="title-a subtitulo">Distribución Temporal</div>
        <div class="parrafo"><?php echo $desc_chart_1['value']; ?></div>
        <div id="contribucionBarrasREUNA"></div>
        <div id="contribucionBarrasGBIF"></div>
        <div class="line"><span></span> Año cero indica años posteriores a 1900.</div>
    </div>
    <div id="containers geografica" class="containers">
        <div class="title-a subtitulo">Distribución Geográfica</div>
        <div style="margin-left:10px;"><span style="font-size: 1.3em;"></span>De un total de <?php echo $totalReuna; ?> observaciones , existen <?php echo $totalReunaConCoordenadas; ?> Ocurrencias Georeferenciadas, correspondiente al <?php echo round($totalReunaConCoordenadas*100/$totalReuna,1);?>% de las ocurrencias.</div>
        <div id="mapContainer" class="mapContainer">
            <div class="mapTitle"><?php echo $REUNA; ?></div>
        </div>
        <div id="mapContainerGBIF" class="mapContainerGBIF">
            <div class="mapTitle">GBIF</div>
        </div>
        <div id="slider-range"></div>
        <input type="text" id="amount" readonly>
    </div>
</div>
<div class="wraper-container" style="padding-top: 40px;">
    <div id="taxonomica" class="title-a subtitulo">Composición Taxonómica</div>
    <div class="parrafo"><?php //echo $desc_chart_2['value']; ?></div>
    <div id="ReunaStacked"></div>
    <div id="GbifStacked"></div>
    <div class="title-a subtitulo">Instituciones</div>
    <div class="parrafo"><?php echo $desc_chart_3['value']; ?></div>
    <div style="width: 45%;float:left"><b>Contribuyentes</b> a los<?php if (isset($specie)) echo $specie; ?> registros <span
            style="color: darkgray">Base de Datos <?php echo $REUNA; ?></span></div>
    <div style="width: 45%;float:right"><b>Contribuyentes</b> a los<?php if (isset($specie)) echo $specie; ?> registros <span
            style="color: darkgray">Base de Datos GBIF</span></div>
    <!--<div id="institucionBar" class="institucionBar"></div>-->
    <div id="institucionPieREUNA" class="institucionPie"></div>

    <div id="institucionPieGBIF" class="institucionPie"></div>
    <div id="REUNATable"><?php
        print '<div class="tableElement"><div class="tableRow">Institución</div><div style="color: #444444;font-weight: bold;width:13%;float: right">Registros</div></div>';
        foreach($institutionDataReuna[0] as $key=>$value){
            print '<div class="tableElement"><div class="key">'.$value[0].'</div><div class="value">'.$value[1].'</div></div>';
        }
        ?></div>
    <div id="GBIFTable"><?php
        print '<div class="tableElement"><div class="tableRow">Institución</div><div style="color: #444444;font-weight: bold;width:13%;float: right">Registros</div></div>';
        foreach($institutionDataGbif[0] as $key=>$value){
            print '<div class="tableElement"><div class="key">'.$value[0].'</div><div class="value">'.$value[1].'</div></div>';
        }
        ?></div>
</div>
<script>

(function ($) {
    var fecha = new Date();
    var today = fecha.getFullYear();
    Drupal.behaviors.yourThemeSlider = {
        attach: function (context, settings) {
            var steps = ['-1', '1889', '1900', '1910', '1920', '1930', '1940', '1950', '1960', '1970', '1980', '1990', '2000', '2010', today];
            $("#slider-range").slider({
                range: true,
                min: 0,
                max: 14,
                step: 1,
                values: [0, 14],
                slide: function (event, ui) {
                    $("#amount").val((steps[ui.values[0]] == -1 ? 'Sin año' : (steps[ui.values[0]] == 0 ? 'Antes de 1900' : steps[ui.values[0]])) + " - " + (steps[ui.values[1]] == -1 ? 'Sin año' : (steps[ui.values[1]] == 0 ? 'Antes de 1900' : steps[ui.values[1]])));
                    changeFeatures(steps[ui.values[0]], steps[ui.values[1]]);
                }
            });
            $("#amount").val('Sin año' + " - " + steps[$("#slider-range").slider("values", 1)]);
        }
    };
    var chartREUNA, colors = Highcharts.getOptions().colors;
    var ReunaStacked, GbifStacked;
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

    $(document).ready(function ($) {
        var dataReuna =<?php echo json_encode($institutionDataReuna); ?>;
        var dataGbif =<?php echo json_encode($institutionDataGbif); ?>;
        var name = 'Decada';
        var yearCountGBIF =<?php echo json_encode($yearCountGbif); ?>;
        var tempREUNA = <?php echo json_encode($drillDownDataReuna); ?>;
        var dataREUNA = tempREUNA[0];
        var tempGBIF = <?php echo json_encode($drillDownDataGbif); ?>;
        var dataGBIF = tempGBIF[0];
        //var monthCount =<?php echo json_encode($monthCount); ?>;
        //var monthCountGBIF =<?php echo json_encode($someVar); ?>;
        var stackedReunaData=<?php echo json_encode($stackedChildrens);?>;
        var stackedGbifData=<?php echo json_encode($stackedChildrensGbif);?>;
       // console.log(tempGBIF[0]);
       // console.log(tempGBIF[1]);
        //console.log(yearCountGBIF);
        console.log(stackedGbifData);

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
                data: dataReuna[0]
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
                data: dataGbif[0]
            }]
        });
        chartREUNA = new Highcharts.Chart({
            chart: {
                renderTo: 'contribucionBarrasREUNA',
                type: 'column'
            },
            title: {
                text: null
            },
            credits: {
                enabled: false
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
                        color: '#000000',
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
            credits: {
                enabled: false
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
                        color: '#000000',
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
                        s += 'Click para expandir a ' + point.category;
                    } else {
                        s += 'Click para volver atrás.';
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
        GbifStacked = new Highcharts.Chart({
            chart: {
                type: 'bar',
                renderTo: 'GbifStacked'
            },
            title: {
                text: 'Distribución de Ocurrencias por Genero (Base de Datos GBIF)'
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: {formatter:function(){return 'Composición<br>Taxonomica';}}//['Composición Taxonomica']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Composición Taxonomica Total'
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'percent',
                    showInLegend: true//muestra o esconde la leyenda de los graficos
                }
            },
            tooltip: {
                formatter: function () {
                    var point = this.point,
                        s = this.series.name + ':<b>' + this.y + '</b><br/>';
                    return s;
                }
            },
            series: []
        });
        ReunaStacked = new Highcharts.Chart({
            chart: {
                type: 'bar',
                renderTo: 'ReunaStacked'
            },
            title: {
                text: 'Distribución de Ocurrencias por Genero (Base de Datos <?php echo $REUNA; ?>)'
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: {formatter:function(){return 'Composición<br>Taxonomica';}}//['Composición Taxonomica']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Composición Taxonomica Total'
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'percent'
                }
            }
        });
        for(var x in stackedGbifData){
            GbifStacked.addSeries(stackedGbifData[x]);
        }
        for(var x in stackedReunaData){
            ReunaStacked.addSeries(stackedReunaData[x]);
        }
    });
})(jQuery);
function changeFeatures(first, last) {
    source.clear();
    sourceGBIF.clear();

    var newFeatures = [];
    var j = 0;
    var k = 0;
    for (var i = 0; i < arrayCoordinatesInJS.length; i ++) {
        if (coordYearsReuna[k] <= last && coordYearsReuna[k] >= first) {
            var coordinate = [parseFloat(arrayCoordinatesInJS[i][1]), parseFloat(arrayCoordinatesInJS[i][0])];
            var tempLonlat = ol.proj.transform(coordinate, 'EPSG:4326', 'EPSG:3857');
            //var tempLonlat = ol.proj.transform([arrayCoordinatesInJS[i + 1], arrayCoordinatesInJS[i]], 'EPSG:4326', 'EPSG:3857');
           newFeatures[j] = new ol.Feature(new ol.geom.Point(tempLonlat));

            j++;
        }
        k++;
    }
    /*
    for (var i = 0; i < arrayCoordinatesInJS.length - 1; i += 2) {
        if (coordYearsReuna[k] <= last && coordYearsReuna[k] >= first) {
            var tempLonlat = ol.proj.transform([arrayCoordinatesInJS[i + 1], arrayCoordinatesInJS[i]], 'EPSG:4326', 'EPSG:3857');
            newFeatures[j] = new ol.Feature(new ol.geom.Point(tempLonlat));
            j++;
        }
        k++;
    }*/
    var newFeaturesGBIF = [];
    j = 0;
    k = 0;

    for (var i = 0; i < arrayCoordinatesGBIFInJS.length; i ++) {
        if (first <= coordYearsGBIF[k] && coordYearsGBIF[k] <= last) {
            var coordinateGBIF = [parseFloat(arrayCoordinatesGBIFInJS[i][0]), parseFloat(arrayCoordinatesGBIFInJS[i][1])];
            var tempLonlatGBIF = ol.proj.transform(coordinateGBIF, 'EPSG:4326', 'EPSG:3857');
            //var tempLonlatGBIF = ol.proj.transform([arrayCoordinatesGBIFInJS[i], arrayCoordinatesGBIFInJS[i + 1]], 'EPSG:4326', 'EPSG:3857');
            newFeaturesGBIF[j] = new ol.Feature(new ol.geom.Point(tempLonlatGBIF));
            j++;
        }
        k++;
    }

   /* for (var i = 0; i < arrayCoordinatesGBIFInJS.length - 1; i += 2) {
        if (first <= coordYearsGBIF[k] && coordYearsGBIF[k] <= last) {
            var tempLonlatGBIF = ol.proj.transform([arrayCoordinatesGBIFInJS[i], arrayCoordinatesGBIFInJS[i + 1]], 'EPSG:4326', 'EPSG:3857');
            newFeaturesGBIF[j] = new ol.Feature(new ol.geom.Point(tempLonlatGBIF));
            j++;
        }
        k++;
    }*/
    ;
    console.log(j);
    sourceGBIF.addFeatures(newFeaturesGBIF);
    source.addFeatures(newFeatures);
}
var arrayCoordinatesInJS =<?php echo json_encode($coordinatesReuna);?>;
var arrayCoordinatesGBIFInJS =<?php echo json_encode($coordinatesGBIFInPHP);?>;
var coordYearsReuna =<?php if(isset($coordYearsREUNA)&&$coordYearsREUNA!="")echo "[".$coordYearsREUNA."]";else{echo "[]";}?>;
var coordYearsGBIF =<?php if(isset($coordYearsGBIF)&&$coordYearsGBIF!="")echo "[".$coordYearsGBIF."]";else{echo "[]";}?>;
console.log(arrayCoordinatesInJS);
console.log(arrayCoordinatesGBIFInJS);

var largo = (arrayCoordinatesInJS.length);
if (largo > 0) {
    var features = new Array(largo);
    for (var i = 0; i < arrayCoordinatesInJS.length; i ++) {
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

var largoGBIF = (arrayCoordinatesGBIFInJS.length);
if (largoGBIF > 0) {
    var featuresGBIF = new Array(largoGBIF);
    for (var i = 0; i < arrayCoordinatesGBIFInJS.length; i ++) {
        var coordinateGBIF = [parseFloat(arrayCoordinatesGBIFInJS[i][0]), parseFloat(arrayCoordinatesGBIFInJS[i][1])];
        var tempLonlatGBIF = ol.proj.transform(coordinateGBIF, 'EPSG:4326', 'EPSG:3857');
        //var tempLonlat = [arrayCoordinatesInJS[i], arrayCoordinatesInJS[i+1]];
        featuresGBIF[i] = new ol.Feature(new ol.geom.Point(tempLonlatGBIF));
    }
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
    collection.forEach(function (f) {
            var text = f.get('NOMBRE');
            console.log(text);
            source.forEachFeature(function (e) {
                if (ol.extent.containsCoordinate(f.getGeometry().getExtent(), e.getGeometry().getCoordinates())) {
                    console.log('adentro');
                }
                else {
                    console.log('afuera');
                }
            });
        }
    );

    //console.log(featuresinBox);
    //featuresSelected.push(e);
});
collection.on('remove', function () {
    console.log('remove');
});
var selectClickGBIF = new ol.interaction.Select({
    condition: ol.events.condition.click,
    style: new ol.style.Style({
        fill: new ol.style.Fill({
            color: 'rgba(0, 255, 0, 0.2)'
        }),
        stroke: new ol.style.Stroke({
            color: '#000000',
            width: 0.5
        })
    })
});
var collectionGBIF = selectClickGBIF.getFeatures();
collectionGBIF.on('add', function () {
    collectionGBIF = selectClickGBIF.getFeatures();
    collectionGBIF.forEach(function (f) {
            var text = f.get('NOMBRE');
            console.log(text);
            source.forEachFeature(function (e) {
                if (ol.extent.containsCoordinate(f.getGeometry().getExtent(), e.getGeometry().getCoordinates())) {
                    console.log('adentro');
                }
                else {
                    console.log('afuera');
                }
            });
        }
    );
});
//collection.on('remove', function(){console.log('remove');});
var changeInteraction = function () {
    select = selectClick;
    map.addInteraction(select);
    mapGBIF.addInteraction(selectClickGBIF);
};
var boxControl = new ol.interaction.DragBox({
    condition: ol.events.condition.shiftKeyOnly,
    style: new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: '#ffcc33',
            width: 2
        })
    })
});
map.addInteraction(boxControl);
boxControl.on('boxend', function () {
    var featuresinBox = [];
    source.forEachFeature(function (e) {
        if (ol.extent.containsCoordinate(boxControl.getGeometry().extent, e.getGeometry().getCoordinates())) {
            featuresinBox.push(e);
        }
    });
    //document.getElementById("msg").innerHTML = featuresinBox.length;
    console.log(featuresinBox);
});
changeInteraction();
//mapGBIF.bindTo('layergroup', map);
mapGBIF.bindTo('view', map);
</script>