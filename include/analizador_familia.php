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
//echo isset($familyKey) ? $familyKey : '';
//var_dump($speciesFound);
//var_dump($yearCountGbif);
?>
<!--<div>
    <form  accept-charset="utf-8" method="post">
        <input id="qw" class="busqueda" name="qw" type="text" size="35" value="<?php //echo htmlspecialchars($specie, ENT_QUOTES, 'utf-8'); ?>"/>
        <input type="submit" value="Search"/>
    </form>
</div>-->

<div class="summary1">
    <div class="nombre-completo"><span style="color: darkgray">FAMILIA </span>
        <br>
        <span class="titspecies"><?php $i=0;if (isset($nameSpecieAuthor)) $editado=str_replace('"','',$nameSpecieAuthor);$editado2=str_replace('(','',$editado);$editado3=str_replace(')','',$editado2);$editado4=explode(' ',$editado3);
            foreach($editado4 as $value){$i+=1;if($i==2){echo '( ';}echo $value.' ';}echo ')'; ?>
        </span>
        <br>
        <div id="jerarquia"><span> <?php echo str_replace('"','',$hierarchy);?></span>
        </div><br>
        <span style="font-size: 0.7em"> <?php echo 'GBIF ID: ' . $familyKey;?></span>
        <div class="linea_hor"></div>
    </div>
    <div class="summary-left">
        <div id="boxleft">
            <span class="little">Contenido en fuentes de datos: </span> <br/>
            <b>
            <span class="Reuna">
            <span style="font-size: 2.5em;"><?php echo $totalReuna.' '; ?></span><?php echo setRegistrosSingPlu($totalReuna).$reuna; ?>
            </span>
                &nbsp &nbsp &nbsp
            <span class="GBIF">
            <span style="font-size: 2em;"> <?php echo $totalInGbif.' '; ?></span><?php echo setRegistrosSingPlu($totalInGbif).'GBIF'; ?>
            </span>
            </b>
        </div>
    </div>
    <!--<div id="summary-right">
        <div id="acumuladas"></div>
        <span style="color:gray">*Existen datos sin fecha de registro</span>
    </div>-->

</div>
<p></p>

<div class="boxsec">
    <span class="species" style="margin-bottom:30px;">
        <span class="heading2">Distribución Geográfica </span>
        <span style="font-size: 1.3em;"> registros de la especie
        <span style="font-style:italic;"><?php if (isset($family)) echo $family; ?></span>
    en Chile </span>
    </span>
    <!--<span class="species"> <?php if (isset($family)) echo $family; ?> en Chile</span>-->
    <div id="geo-left">
        <div id="mapContainer" class="mapContainer">
            <div style="font-weight: bold;text-align: center;padding-bottom:8px;"><?php echo $reuna; ?></div>
            <div id="legend"></div>
        </div>
        <div id="mapContainerGBIF" class="mapContainerGBIF">
            <div style="font-weight: bold;text-align: center;padding-bottom:8px;">GBIF</div>
        </div>
    </div>
    <div id="right_geo">
        <p></p>
        <div class="heading3">Datos Georeferenciados</div>
        <span style="font-size:1.1em"><?php echo round($totalReunaWithCoordinates*100/$totalReuna,1);?>% de los registros en <?php echo $reuna; ?></span>
        <span class="suave">(n=<?php echo $totalReunaWithCoordinates; ?>)</span>
        <br>
        <span style="font-size:1.1em"><?php echo round($totalGbifWithCoordinates*100/$totalInGbif,1);?>% de los registros en GBIF</span>
        <span class="suave">(n=<?php echo $totalGbifWithCoordinates; ?>)</span>
        <p></p>
        <p></p>
        <div>
            <span class="heading3">Distribución por región:</span> <p></p>
            XV<br>I<br>II<br>III<br>IV<br>V<br>RM<br>VI<br>VII<br>VIII<br>IX<br>XIV<br>X<br>XI<br>XII<br>
        </div>
        <br>
        <strong><span class="suave">Filtro temporal:</span></strong>
        <div id="slider-range" style="clear: both; bottom: -10px;"></div>
        <div style="widt:100%">
            <input type="text"  id="amountL" readonly style="float:left;border:0; position:relative; bottom: -10px; color:#000000;">
            <input type="text"  id="amountR" readonly style="text-align:right; float:right;border:0; position:relative; bottom: -10px; color:#000000;">
        </div>
    </div>
</div>


<script>

(function ($) {
    var fecha = new Date();
    var today = fecha.getFullYear();
    Drupal.behaviors.yourThemeSlider = {
        attach: function (context, settings) {
            var steps = ['-1', '0', '1900', '1910', '1920', '1930', '1940', '1950', '1960', '1970', '1980', '1990', '2000', '2010', today];
            $("#slider-range").slider({
                range: true,
                min: 0,
                max: 14,
                step: 1,
                values: [0, 14],
                slide: function (event, ui) {
                    $("#amountL").val(steps[ui.values[0]] == -1 ? 'Sin fecha' : (steps[ui.values[0]] == 0 ? 'Antes de 1900' : steps[ui.values[0]]));
                    $("#amountR").val(steps[ui.values[1]] == -1 ? 'Sin fecha' : (steps[ui.values[1]] == 0 ? 'Antes de 1900' : steps[ui.values[1]]));
                    changeFeatures(steps[ui.values[0]], steps[ui.values[1]]);
                }
            });
            $("#amountL").val('Sin fecha');
            $("#amountR").val(steps[$("#slider-range").slider("values", 1)]);
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
    Drupal.behaviors.yourThemeSlider = {
        attach: function (context, settings) {
            var dataReuna =<?php echo json_encode($institutionDataReuna); ?>;
            var dataGbif =<?php echo json_encode($institutionDataGbif); ?>;
            var name = 'Decada';
            var tempREUNA = <?php echo json_encode($drillDownDataReuna); ?>;
            var dataREUNA = tempREUNA[0];
            var tempGBIF = <?php echo json_encode($drillDownDataGbif); ?>;
            var stackedReunaData=<?php echo json_encode($stackedChildrensReuna);?>;
            var stackedGbifData=<?php echo json_encode($stackedChildrensGbif);?>;
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
            if(tempREUNA[1].length>0)chartREUNA = new Highcharts.Chart({
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
                    categories: tempREUNA[1],
                    labels: {
                        rotation:-45,
                        style: {

                            color: '#000000'
                            //font: '11px Trebuchet MS, Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Observ. <?php echo $reuna; ?>',
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
                                        setChart(chartREUNA, drilldownREUNA.name, drilldownREUNA.categories, drilldownREUNA.data, 'rgba(0, 0, 0, 0.8)');
                                    } else { // restore
                                        setChart(chartREUNA, name, tempREUNA[1], dataREUNA,'rgba(0, 0, 0, 0.8)');
                                    }
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            color: '#53AD25',
                            style: {
                                fontWeight: 'bold'
                            },
                            formatter: function () {
                                return this.y != 0 ? this.y : null;
                            }
                        }
                    },
                    series: {
                        color: 'rgba(0, 0, 0, 0.8)'
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
                    data: dataREUNA
                    //color: 'white'
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
            if(tempGBIF[1].length>0)chartGBIF = new Highcharts.Chart({
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
                    categories: tempGBIF[1],
                    labels: {
                        rotation:-45,
                        style: {

                            color: '#000000'
                            //font: '11px Trebuchet MS, Verdana, sans-serif'
                        }
                    }
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
                                        setChart(chartGBIF, drilldown.name, drilldown.categories, drilldown.data, 'rgba(83, 173, 37, 0.8)');
                                    } else { // restore
                                        setChart(chartGBIF, name, tempGBIF[1], tempGBIF[0],'rgba(83, 173, 37, 0.8)');
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
                    },
                    series: {
                        color: 'rgba(83, 173, 37, 0.8)'
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
                    data: tempGBIF[0]
                    //color: 'white
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
            if(Object.keys(stackedGbifData).length>0){
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
                });}
            if(Object.keys(stackedReunaData).length>0){
                ReunaStacked = new Highcharts.Chart({
                    chart: {
                        type: 'bar',
                        renderTo: 'ReunaStacked'
                    },
                    title: {
                        text: 'Distribución de Ocurrencias por Genero (Base de Datos <?php echo $reuna; ?>)'
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
            }
            var j=0;
            var cont=0;
            if(Object.keys(stackedGbifData).length>0){
                for(var x in stackedGbifData){
                    if(j<15) {
                        GbifStacked.addSeries(stackedGbifData[x]);
                        j=j+1;
                    }
                    else{
                        cont+=stackedGbifData[x].index;
                    }

                }
                var arr=[cont];
                GbifStacked.addSeries({
                    name:'Otros',
                    data:arr,
                    index:cont,
                    legendIndex:cont
                });}
            if(Object.keys(stackedReunaData).length>0){
                var k=0;
                var conta=0;
                for(var x in stackedReunaData){
                    if(k<15){
                        ReunaStacked.addSeries(stackedReunaData[x]);
                        k=k+1;
                    }
                    else{
                        conta+=stackedReunaData[x].index;
                    }
                }
                var arre=[conta];
                ReunaStacked.addSeries({
                    name:'Otros',
                    data:arre,
                    index:conta,
                    legendIndex:conta
                });}
        }
    }

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
    var newFeaturesGBIF = [];
    j = 0;
    k = 0;

    for (var i = 0; i < arrayCoordinatesGBIFInJS.length; i ++) {
        if (first <= coordYearsGbif[k] && coordYearsGbif[k] <= last) {
            var coordinateGBIF = [parseFloat(arrayCoordinatesGBIFInJS[i][0]), parseFloat(arrayCoordinatesGBIFInJS[i][1])];
            var tempLonlatGBIF = ol.proj.transform(coordinateGBIF, 'EPSG:4326', 'EPSG:3857');
            //var tempLonlatGBIF = ol.proj.transform([arrayCoordinatesGBIFInJS[i], arrayCoordinatesGBIFInJS[i + 1]], 'EPSG:4326', 'EPSG:3857');
            newFeaturesGBIF[j] = new ol.Feature(new ol.geom.Point(tempLonlatGBIF));
            j++;
        }
        k++;
    }
    ;
    console.log(j);
    sourceGBIF.addFeatures(newFeaturesGBIF);
    source.addFeatures(newFeatures);
}
var arrayCoordinatesInJS =<?php echo json_encode($coordinatesReuna);?>;
var arrayCoordinatesGBIFInJS =<?php echo json_encode($coordinatesGbif);?>;
var coordYearsReuna =<?php if(isset($coordYearsReuna)&&$coordYearsReuna!="")echo "[".$coordYearsReuna."]";else{echo "[]";}?>;
var coordYearsGbif =<?php if(isset($coordYearsGbif)&&$coordYearsGbif!="")echo "[".$coordYearsGbif."]";else{echo "[]";}?>;
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
});

var geoJsonSource = new ol.source.GeoJSON({
    projection: 'EPSG:3857',
    //url: '<?php //echo $path;?>/regiones/regiones.json'
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
    console.log(featuresinBox);
});
changeInteraction();
mapGBIF.bindTo('view', map);
</script>