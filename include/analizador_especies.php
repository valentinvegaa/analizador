<span >
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
//echo $path . '     _path';
//var_dump($yearCountGbif);
//var_dump($categoriesGBIF);
?>
</span>

</div>
<!--<div>
     <form  accept-charset="utf-8" method="post">
<input id="qw" class="busqueda" name="qw" type="text" size="35" value="<?php //echo htmlspecialchars($specie, ENT_QUOTES, 'utf-8'); ?>"/>
<input type="submit" value="Search"/>
</form>
  </div>-->
<p></p>
<div class="summary1">
    <div class="nombre-completo"><span style="color: darkgray">ESPECIE </span>
        <br>
        <span class="titspecies"><?php if (isset($nombreEspecieAutor)) echo str_replace('"','',$nombreEspecieAutor); ?></span>
        <br>
        <span style="font-size: 0.7em"> <?php echo str_replace('"','',$jerarquia);?></span>
            <br>
        <span style="font-size: 0.7em"> <?php echo 'GBIF ID: ' . $speciesKey;?></span>
                                                         <div class="linea_hor"></div>
    </div>
    <div class="summary-left">
        <div id="boxleft">
            <span class="little">Contenido en fuentes de datos: </span> <br/>
            <b>
 <span class="Reuna">
<span style="font-size: 2.5em;"><?php echo $totalReuna; ?></span>
                                                            registros en <?php echo $REUNA; ?>
</span>
                &nbsp &nbsp &nbsp
            <span class="GBIF">
<span style="font-size: 2em;"> <?php echo $totalEnGBIF; ?></span>
                                                            registros en GBIF
            </span>
            </b>
        </div>
    </div>
    <div id="summary-right">
        <div id="acumuladas"></div>
        <span style="color:gray">*Existen datos sin fecha de registro</span>
    </div>

</div>
<p></p>

<div class="boxsec">
    <span class="species" style="margin-bottom:30px;">
        <span class="heading2">Distribución Geográfica </span>
      <span style="font-size: 1.3em;"> registros de la especie
        <span style="font-style:italic;"><?php if (isset($specie)) echo $specie; ?></span>
    en Chile </span></span>

    <!--<span class="species"> <?php if (isset($specie)) echo $specie; ?> en Chile</span>-->

    <div id="geo-left">
        <div id="mapContainer" class="mapContainer">
            <div style="font-weight: bold;text-align: center;padding-bottom:8px;"><?php echo $REUNA; ?></div>
        </div>
        <div id="mapContainerGBIF" class="mapContainerGBIF">
            <div style="font-weight: bold;text-align: center;padding-bottom:8px;">GBIF</div>
        </div>

    </div>
    <div id="right_geo">
        <p></p>
        <div class="heading3">Datos Georeferenciados</div>
        <span style="font-size:1.1em"><?php echo round($totalReunaConCoordenadas*100/$totalReuna,1);?>%
de los registros en <?php echo $REUNA; ?></span>
        <span class="suave">(n=<?php echo $totalReunaConCoordenadas; ?>)</span>
        <br>
        <span style="font-size:1.1em"><?php echo round($totalGBIF*100/$totalEnGBIF,1);?>%
de los registros en GBIF</span>
        <span class="suave">(n=<?php echo $totalGBIF; ?>)</span>
        <p></p>
        <p></p>
        <div>
            <span class="heading3">Distribución por región:</span> <p></p>
            XV<br>I<br>II<br>III<br>IV<br>V<br>RM<br>VI<br>VII<br>VIII<br>IX<br>XIV<br>X<br>XI<br>XII<br>
        </div>
        <br>
        <strong><span class="suave">Filtro temporal:</span></strong>
        <div id="slider-range" style="clear: both; bottom: -10px;"></div>
        <input type="text" id="amount" readonly
               style="border:0; position:relative; bottom: -10px; color:#000000; font-weight:bold;">
    </div>
</div>

<p>
<table style="margin:20px;width:945px;">
    <tr><td class="boxinstituc">
    <div class="heading2" >Distribución Temporal  &nbsp</div>
    <span class="species" style="font-size: 1.2em;margin:10px 150px 20px 13px ;"> A continuación se presenta la evolución temporal de los registros en Chile de
        la especie <span style="font-style:italic;"><?php if (isset($specie)) echo $specie; ?></span>
         en ambas fuentes de datos. Izquierda: la distribución de los registros por año, derecha: por el mes de registro.
    </span>

      <div id="temp-left">
            <!-- TEXTO MODIFICABLE <div class="parrafo"><?php echo $desc_chart_1['value']; ?></div> -->
            <span class="heading3">Registros por año</span>
            <div id="contribucionBarrasREUNA"></div>
            <div class="suave" style="margin-top:-5px; position:relative;">
                <?php echo sizeof($yearCount);?></span> años con registros en <?php echo $REUNA; ?>
                (<?php if(sizeof($yearCount)==1){echo key($yearCount);}else{reset($yearCount);echo key($yearCount).' - ';end($yearCount);echo key($yearCount);};?>)
            </div>

            <div id="contribucionBarrasGBIF"></div>
            <div class="suave" style="margin-top:-5px; position:relative;">
                <span class="bignumber"><?php echo sizeof($yearCountGbif)?></span> años con registros en GBIF
                (<?php if(sizeof($yearCountGbif)==1){echo key($yearCountGbif);}else{reset($yearCountGbif);echo key($yearCountGbif).' - ';end($yearCountGbif);echo key($yearCountGbif);};?>)
            </div>
        </div>


        <div id="temp-right">
            <!-- TEXTO MODIFICABLE <div class="parrafo"><?php echo $desc_chart_2['value']; ?></div> -->
            <span class="heading3">Registros por mes</span>
            <div id="reunaGbifBarras"></div>

            <div id="GbifBarrasmes"></div>
        </div>
    <p></p>&nbsp
    <div class="suave" style="text-align:center;margin:40px 0 20px 0;display:block;float:none;"> *Datos sin fecha de registro: [<?php echo $totalReuna-end($accumulatedYearsReuna); ?>] Reuna; [<?php echo ($totalEnGBIF-end($accumulatedYearsGbif)); ?>] GBIF
    </div>
        </td></tr>
</table>


<table style="margin:20px;width:945px;">
    <tr><td class="boxinstituc">
    <div class="heading2" > Organizaciones contribuyentes  &nbsp</div>
    <p></p>
    <div style="width: 49%;display:inline-block;text-align:left;margin:10px 0 0 20px;">
        <div class="heading3">Organizaciones</div>
        <div style="font-size: 1.2em;margin:10px 20px 0 0 ;">En el repositorio <?php echo $REUNA; ?>,
            <b><?php echo sizeof($institutionNamesReuna)?></b>
            Organizaciones han contribuido con registros de la especie
            <span class="species"> <span style="font-style:italic;"><?php if (isset($specie)) echo $specie; ?></span> en Chile:</span>
        </div>

        <p></p>
        <div id="REUNATable"><?php print '<div class="tableElement">
                <div style="color: #168970;width:86%;float:left;font-size:0.9em;">
                    Organización</div>
                <div style="color: #168970;width:14%;float:right;font-size:0.9em;">
                Registros</div></div>';
            foreach($institutionNamesReuna as $elemento){
                print '<div class="tableElement"><div class="key">'.$elemento[0].'</div><div class="value">'.$elemento[1].'</div></div>';
            }
            ?></div>
        <p></p>
        <div style="font-size:1.1em;margin:50px 20px 0px 20px;text-align:center;display:inline-block;width:85%;">
            Distribución relativa contribución de registros:
        </div>
        <div id="institucionPieREUNA" class="institucionPie"></div>
        <div class="suave" style="margin:20px 20px 20px 20px;text-align:center;">[ Seleccione para filtrar ]
        </div>
    </div>
    <div style="width: 45%;text-align:left;margin-top:10px;float:right;">
        <div class="heading3">Investigadores</div>
        <div style="font-size: 1.2em;margin:10px 20px 0 0 ;"><b><?php echo count($especiesPorInvestigador); ?></b> Investigadores han contribuido con registros de la especie
            <span class="species">
                <span style="font-style:italic;">
                    <?php if (isset($specie)) echo $specie; ?>
                </span> el repositorio <?php echo $REUNA; ?>:</span>
        </div>

</br>

        <div id="registrosPorInvestigador"><?print $salida;?></div>
    </div>
        </td></tr>
</table>
<table style="margin:20px;width:945px;">
    <tr><td class="boxinstituc">
            <div class="heading3">Organizaciones contribuyentes en GBIF</div>
            <div style="width: 50%;float:left;text-align:left;margin-top:10px;">
                <div style="font-size: 1.2em;margin:20px;">En GBIF,
                    <b><?php echo sizeof($institutionNamesGBIF)?></b>
                    Organizaciones han contribuido con registros de la especie
                    <span class="species"> <span style="font-style:italic;"><?php if (isset($specie)) echo $specie; ?></span> en Chile:</span>
                </div>
        <div id="GBIFTable">
            <?php print '<div class="tableElement">
            <div style="color: #168970;width:86%;float:left;font-size:0.9em;">Organización</div>
            <div style="color: #168970;font-weight: bold;width:14%;float: right;font-size:0.9em;">Registros</div></div>';
                foreach($institutionNamesGBIF as $key=>$value){
                    print '<div class="tableElement"><div class="key"><a href="http://www.google.cl/search?q='.str_replace(' ','+',$value[0]).'" target="_blank">'.$value[0].'</a></div><div class="value">'.$value[1].'</div></div>';
                }
            ?></div>
</div>
      <div style="width: 44%;text-align:left;margin-top:10px;float:right;">
          <div style="font-size:1.1em;margin:20px 20px 0px 20px;text-align:center;">Distribución relativa contribución de registros:
          </div>
          <div id="institucionPieGBIF" class="institucionPie"></div>
          <div class="suave" style="margin:20px 20px 20px 20px;text-align:center;">[ Seleccione para filtrar ]
          </div>
</div>
</td></tr>
</table>

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
                var steps = ['-1', '0', '1900', '1910', '1920', '1930', '1940', '1950', '1960', '1970', '1980', '1990', '2000', '2010', today];
                $("#slider-range").slider({
                    range: true,
                    min: 0,
                    max: 14,
                    step: 1,
                    values: [0, 14],
                    slide: function (event, ui) {
                        $("#amount").val((steps[ui.values[0]] == -1 ? 'Sin fecha' : (steps[ui.values[0]] == 0 ? 'Antes de 1900' : steps[ui.values[0]])) + " - " + (steps[ui.values[1]] == -1 ? 'Sin fecha' : (steps[ui.values[1]] == 0 ? 'Antes de 1900' : steps[ui.values[1]])));
                        changeFeatures(steps[ui.values[0]], steps[ui.values[1]]);
                    }
                });
                $("#amount").val('Sin fecha' + " - " + steps[$("#slider-range").slider("values", 1)]);
            }
        };
        var chartREUNA,chartAccumulated, colors = Highcharts.getOptions().colors;
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
            console.log(yearCount);
            console.log(yearCountGbif);
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
                        size: 170,
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
                        size: 170,
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
                legend: {
                    itemWidth: 300,
                    itemStyle: {
                        fontWeight: 'normal'
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Total',
                    data: instNames
                }]
            });

// GRAFICO TEMPORAL ANUAL GBIF

            chartGBIF = new Highcharts.Chart({
                chart: {
                    renderTo: 'contribucionBarrasGBIF',
                    type: 'column'
                },
                title: {
                    text: 'GBIF',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    categories: tempGBIF[1] ,
                    labels: {
                        rotation:-45,
                        style: {
                            color: '#000000',
                            fontWeight: 'bold'
                        }
                    },
                    lineColor: '#666'
                },
                yAxis: {
                    title: {
                        text: 'Registros',
                        style: {
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
                                        setChart(chartGBIF, name, tempGBIF[1], dataGBIF,'rgba(83, 173, 37, 0.8)');
                                    }
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            style: {
                                fontWeight: 'regular',
                                fontSize: '9px'

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
                            s += 'Click to expand to ' + point.category;
                        } else {
                            s += 'Click to return.';
                        }
                        return s;
                    }
                },
                series: [{
                    name: name,
                    data: dataGBIF
                    //color: 'white'
                }],
                exporting: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled:false
                    //layout: 'vertical',
//align: 'right',
                    /*floating: true,*/
//x:0,
//y:-150
                    /*backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                     shadow: true*/
                }
            });

// GRAFICO TEMPORAL ANUAL REUNA

            chartREUNA = new Highcharts.Chart({
                chart: {
                    renderTo: 'contribucionBarras<?php echo $REUNA; ?>',
                    type: 'column'
                },
                title: {
                    text: '<?php echo $REUNA; ?>',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    categories: tempREUNA[1] ,
                    labels: {
                        rotation:-45,
                        style: {

                            color: '#000000'
                            //font: '11px Trebuchet MS, Verdana, sans-serif'
                        }
                    },
                    lineColor: '#666'
                },
                yAxis: {
                    title: {
                        text: 'Registros',
                        style: {
                            color: '#000000',

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
                                fontWeight: 'regular',
                                fontSize: '9px'

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
                    enabled:false
                    //layout: 'vertical',
//align: 'right',
                    /*floating: true,*/
//x:0,
//y:-150
                    /*backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                     shadow: true*/
                }
            });


// GRAFICO TEMPORAL MENSUAL REUNA

            $('#reunaGbifBarras').highcharts({
                chart: {
                    type: 'column'
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false
                },
                title: {
                    text: 'REUNA',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    categories: ['E', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'],
                    labels:{
                        style:{
                            color:'#000000'
                        }
                    },
                    lineColor: '#666'
                },
                yAxis: {
                    min: 0,
                    fontWeight: 'bold',
                    title: {
                        text: '<b>Registros</b>'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
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
                        pointWidth: 10,
                        color:'rgba(0, 0, 0, 0.8)'
                    }
                },
                series: [{
                    name: '<?php echo $REUNA; ?>',
                    data: monthCountReuna//[1, 1, 2, 3, 5, 8, 5, 4, 3, 2, 1, 0]//monthCount//
                },
                ]

            });

// GRAFICO TEMPORAL MENSUAL GBIF

            $('#GbifBarrasmes').highcharts({
                chart: {
                    type: 'column'
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false
                },
                title: {
                    text: 'GBIF',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    categories: ['E', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'],
                    labels:{
                        style:{
                            color:'#000000'
                        }
                    },
                    lineColor: '#666'
                },
                yAxis: {
                    min: 0,
                    fontWeight: 'bold',
                    title: {
                        text: '<b>Registros</b>'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
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
                        pointWidth: 10,
                        color:'rgba(83, 173, 37, 0.8)'
                    }
                },
                series: [{
                    name: 'GBIF',
                    data: monthCountGBIF//[2, 2, 4, 6, 10, 16, 26, 41, 68, 110, 178, 281]

                },
                ]
            });


// GRAFICO TEMPORAL ACUMULACION

            $('#acumuladas').highcharts({
                chart: {
                    type: 'area'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Acumulación registros en el tiempo por fuente de datos',
                    style: '"fontSize": "12px"'
                },
                xAxis: {
                    allowDecimals: false,
//categories: categoryYears,
                    labels: {
//enabled: false,
                        rotation: 0,
                        formatter: function () {
                            return this.value; // clean, unformatted number for year
                        }

                    }
                },
                yAxis: {
                    min: 0,
                    fontWeight: 'bold',
                    title: {
                        text: '<b></b>' // AQUI LEYENDA EJE Y
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:25px">año {point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    area: {
                        pointStart: 1900,
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
                    },
                    series :{
                        lineColor: '#FFFFFF'
                    }
                },
                series: [{
                    name:'GBIF',
                    /*Datos de prueba*/
                    data: accumulatedDataGbif,//[1, 1, 6, 9, 10, 8, 3, 1,1, 6,1, 1, 2, 7, 5, 6, 5, 9, 9, 9]
                    color:'#53AD25'
                },{
                    name: '<?php echo $REUNA; ?>',
                    /*Datos de prueba*/
                    data:accumulatedData,//[1, 1, 2, 3, 5, 8, 5, 4, 3, 2,1, 1, 2, 3, 5, 8, 5, 4, 3, 2]//accumulatedData
                    color:'#000000'
                }]
            });


// AQUI SE PUEDE INSERTAR MAS


        });
    })(jQuery);
    var arrayCoordinatesInJS =<?php if($coordinatesInPHP!="")echo "[".$coordinatesInPHP."]";else{echo "[]";}?>;//if($coordinatesReuna!="")echo "[".$coordinatesReuna."]";else{echo "[]";}?>;
    var arrayCoordinatesGBIFInJS =<?php if($coordinatesGBIFInPHP!="")echo "[".$coordinatesGBIFInPHP."]";else{echo "[]";}?>;
    console.log(arrayCoordinatesGBIFInJS);
    console.log(arrayCoordinatesInJS);
    var coordYearsReuna =<?php if(isset($coordYearsREUNA)&&$coordYearsREUNA!="")echo "[".$coordYearsREUNA."]";else{echo "[]";}?>;
    var coordYearsGBIF =<?php if(isset($coordYearsGBIF)&&$coordYearsGBIF!="")echo "[".$coordYearsGBIF."]";else{echo "[]";}?>;
    console.log(coordYearsReuna);
    console.log(coordYearsGBIF);

    var largo = (arrayCoordinatesInJS.length) / 2;
    if (largo > 0) {
        var features = new Array(largo);
        var j = 0;
        for (var i = 0; i < arrayCoordinatesInJS.length - 1; i += 2) {
//alert(arrayCoordinatesInJS[i] + " " + arrayCoordinatesInJS[i+1]);
            var coordinate = [arrayCoordinatesInJS[i+1], arrayCoordinatesInJS[i]];
            var tempLonlat = ol.proj.transform(coordinate, 'EPSG:4326', 'EPSG:3857');
//var tempLonlat = [arrayCoordinatesInJS[i], arrayCoordinatesInJS[i+1]];
            features[j] = new ol.Feature({'visible': 'true'});
            features[j].setGeometry(new ol.geom.Point(tempLonlat));
            j++;
        }
        ;
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
        //url: '<?php //echo $path;?>/regiones/regiones.json'
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

    /*var newFeatures = [];
    var j = 0;
    var k = 0;
    for (var i = 0; i < arrayCoordinatesInJS.length - 1; i += 2) {
        if (coordYearsReuna[k] <= last && coordYearsReuna[k] >= first) {
            var tempLonlat = ol.proj.transform([arrayCoordinatesInJS[i + 1], arrayCoordinatesInJS[i]], 'EPSG:4326', 'EPSG:3857');
            newFeatures[j] = new ol.Feature(new ol.geom.Point(tempLonlat));
            j++;
        }
        k++;
    }*/

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
    /***************************************/
    mapGBIF.bindTo('view', map);
</script>
