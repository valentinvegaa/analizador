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
        <span class="titspecies"><?php $i=0;if (isset($nameSpecieAuthor)) $editado=str_replace('"','',$nameSpecieAuthor);$editado2=str_replace('(','',$editado);$editado3=str_replace(')','',$editado2);$editado4=explode(' ',$editado3);
           foreach($editado4 as $value){$i+=1;if($i==3){echo '( ';}echo $value.' ';}echo ')'; ?>
        </span>
        <br>
        <div id="jerarquia"><span> <?php echo str_replace('"','',$hierarchy);?></span>
        </div><br>
        <span style="font-size: 0.7em"> <?php echo 'GBIF ID: ' . $speciesKey;?></span>
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
    en Chile </span>
    </span>
    <!--<span class="species"> <?php if (isset($specie)) echo $specie; ?> en Chile</span>-->
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
                  <?php reset($yearCountReuna);if(sizeof($yearCountReuna)==0){echo 0 .' ';}else{if(sizeof($yearCountReuna)==2 and key($yearCountReuna)==""){echo sizeof($yearCountReuna)-1 .' ';}else{if(key($yearCountReuna)==""){echo sizeof($yearCountReuna)-1 .' ';}else {echo sizeof($yearCountReuna).' ';}}};?></span><?php echo setYearSingPluReuna($yearCountReuna).$reuna; ?>
                  (<?php if(sizeof($yearCountReuna)==1){echo key($yearCountReuna);}else{reset($yearCountReuna);if(key($yearCountReuna)==""){next($yearCountReuna);echo key($yearCountReuna).' - ';end($yearCountReuna);echo key($yearCountReuna);} else{echo key($yearCountReuna).' - ';end($yearCountReuna);echo key($yearCountReuna);}};?>)
                </div>
                <div id="contribucionBarrasGBIF"></div>
                <div class="suave" style="margin-top:-5px; position:relative;">
                <span class="bignumber"><?php echo sizeof($yearCountGbif).' '?></span><?php echo setYearSingPluGbif($yearCountGbif).'GBIF'; ?>
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
    <div class="suave" style="text-align:center;margin:40px 0 20px 0;display:block;float:none;"> *Datos sin fecha de registro: [<?php echo $totalReuna-end($accumulatedYearsReuna); ?>] Reuna; [<?php echo ($totalInGbif-end($accumulatedYearsGbif)); ?>] GBIF
    </div>
        </td></tr>
</table>
<table style="margin:20px;width:945px;">
    <tr><td class="boxinstituc">
    <div class="heading2" > Organizaciones contribuyentes en <?php echo $reuna; ?></div>
    <p></p>
            <?php if(count($institutionNamesReuna)>0):?>
    <div style="width: 49%;display:inline-block;text-align:left;margin:10px 0 0 20px;">
        <div class="heading3">Organizaciones</div>
        <div style="font-size: 1.2em;margin:10px 20px 0 0 ;">En el repositorio <?php echo $reuna; ?>,
            <b><?php echo sizeof($institutionNamesReuna).' '?></b>
            <?php echo setOrganizationSingPlu($institutionNamesReuna).' contribuido con registros de la especie'?>
            <span class="species"> <span style="font-style:italic;"><?php if (isset($specie)) echo $specie; ?></span> en Chile:</span>
        </div>
            <div id="REUNATable"><?php print '
                <div class="tableElement">
                    <div style="color: #168970;width:86%;float:left;font-size:0.9em;">Organización</div>
                    <div style="color: #168970;width:14%;float:right;font-size:0.9em;">Registros</div>
                </div>';
                foreach($institutionNamesReuna as $elemento){
                    print '<div class="tableElement"><div class="key"><a href="http://www.google.cl/search?q='.str_replace(' ','+',$elemento[0]).'" target="_blank">'.$elemento[0].'</a></div><div class="value">'.$elemento[1].'</div></div>';
                }
                ?>
            </div>
        <p></p>
        <div style="font-size:1.1em;margin:50px 20px 0px 20px;text-align:left;display:inline-block;width:85%;">
            Distribución relativa contribución de registros:
            <div id="institucionPieREUNA" class="institucionPie"></div>
            <div class="suave" style="margin:20px 20px 20px 20px;text-align:center;">[ Click en una organización para quitarla del gráfico ]
            </div>
        </div>
    </div>
    <div style="width: 45%;text-align:left;margin-top:10px;float:right;">
        <div class="heading3">Investigadores</div>
        <div style="font-size: 1.2em;margin:10px 20px 0 0 ;"><b><?php echo count($speciesByInvestigator).' '; ?></b><?php echo setInvestigatorSingPlu($speciesByInvestigator).' contribuido con registros de la especie'?>
            <span class="species">
                <span style="font-style:italic;">
                    <?php if (isset($specie)) echo $specie; ?>
                </span> en el repositorio <?php echo $reuna; ?>:</span>
        </div>
        <br>
        <div id="registrosPorInvestigador"><?print $salida;?></div>
    </div>
            <?php else:?>
                <div class="sinGrafico">
                    <span>No se encontraron registros asociados en esta plataforma</span>
                </div>
            <?php endif;?>
        </td></tr>
</table>
<table style="margin:20px;width:945px;">
    <tr><td class="boxinstituc">
            <div class="heading2">Organizaciones contribuyentes en GBIF</div>
            <?php if(count($institutionNamesGbif)>0):?>
            <div style="width: 50%;float:left;text-align:left;margin-top:10px;">
                <div style="font-size: 1.2em;margin:20px;">En GBIF,
                    <b><?php echo sizeof($institutionNamesGbif).' '?></b>
                    <?php echo setOrganizationSingPlu($institutionNamesGbif).' contribuido con registros de la especie'?>
                    <span class="species"> <span style="font-style:italic;"><?php if (isset($specie)) echo $specie; ?></span> en Chile:</span>
                </div>
                <div id="GBIFTable"><?php
                    print '
                        <div class="tableElement">
                            <div style="color: #168970;width:86%;float:left;font-size:0.9em;">Organización</div>
                            <div style="color: #168970;font-weight: bold;width:14%;float: right;font-size:0.9em;">Registros</div>
                        </div>';
                    foreach($institutionDataGbif[0] as $key=>$value){
                        $tooltip=getOrgArray($institutionInfo,$value[0]);
                        print '<div class="tableElement" tooltip="Sitio Web: '.$tooltip['page'][0].'&#xa;Direccion: '.$tooltip['addr'][0].'&#xa;Contacto: '.$tooltip[0][0]['name'].'&#xa;Correo: '.$tooltip[0][0]['email'][0].'">
                                    <div class="key">
                                        <a href="'.$tooltip['page'][0].'" target="_blank">'.$value[0].'</a>
                                    </div>
                                    <div class="value">'.$value[1].'</div>
                                </div>';
                    }
                    ?></div>
                </div>
      <div style="width: 44%;text-align:left;margin-top:10px;float:right;">
          <div style="font-size:1.1em;margin:20px 20px 0px 20px;text-align:center;">Distribución relativa contribución de registros:</div>
          <div id="institucionPieGBIF" class="institucionPie"></div>
          <div class="suave" style="margin:20px 20px 20px 20px;text-align:center;">[ Click en una organización para quitarla del gráfico ]</div>
      </div>
            <?php else:?>
                <div class="sinGrafico">
                    <span>No se encontraron registros asociados en esta plataforma</span>
                </div>
            <?php endif;?>
</td></tr>
</table>
<script>

    function changeFeatures(first, last) {
        source.clear();
        sourceGBIF.clear();

        var newFeatures = [];
        var j = 0;
        var k = 0;
        for (var i = 0; i < arrayCoordinatesReunaInJS.length; i++) {
            if (coordYearsReuna[k] <= last && coordYearsReuna[k] >= first) {
                var coordinate = [parseFloat(arrayCoordinatesReunaInJS[i][1]), parseFloat(arrayCoordinatesReunaInJS[i][0])];
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
        for (var i = 0; i < arrayCoordinatesGbifInJS.length; i++) {
            if (first <= coordYearsGbif[k] && coordYearsGbif[k] <= last) {
                var coordinateGBIF = [parseFloat(arrayCoordinatesGbifInJS[i][0]), parseFloat(arrayCoordinatesGbifInJS[i][1])];
                var tempLonlatGBIF = ol.proj.transform(coordinateGBIF, 'EPSG:4326', 'EPSG:3857');
                newFeaturesGBIF[j] = new ol.Feature(new ol.geom.Point(tempLonlatGBIF));
                j++;
            }
            k++;
        }
        ;
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
                        $("#amountL").val(steps[ui.values[0]] == -1 ? 'Sin fecha' : (steps[ui.values[0]] == 0 ? 'Antes de 1900' : steps[ui.values[0]]));
                        $("#amountR").val(steps[ui.values[1]] == -1 ? 'Sin fecha' : (steps[ui.values[1]] == 0 ? 'Antes de 1900' : steps[ui.values[1]]));
                        changeFeatures(steps[ui.values[0]], steps[ui.values[1]]);
                    }
                });
                $("#amountL").val('Sin fecha');
                $("#amountR").val(steps[$("#slider-range").slider("values", 1)]);
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
        Drupal.behaviors.analizador_biodiversidad={
            attach:function(context,setting){
                var institutionNamesReuna =<?php echo json_encode($institutionNamesReuna); ?>;
                var name = 'Decada';
                var accumulatedDataReuna=<?php echo json_encode($accumulatedYearsReuna);?>;
                var accumulatedDataGbif=<?php echo json_encode($accumulatedYearsGbif);?>;
                var drillDownDataReuna=<?php echo json_encode($drillDownDataReuna); ?>;
                var dataREUNA = drillDownDataReuna[0];
                var institutionDataGbif =<?php echo json_encode($institutionDataGbif); ?>;
                var drillDownDataGbif=<?php echo json_encode($drillDownDataGbif); ?>;
                var dataGBIF = drillDownDataGbif[0];
                var monthCountReuna =<?php echo json_encode($monthCountReuna); ?>;
                var monthCountGbif =<?php echo json_encode($monthCountGbif); ?>;
                var noHayDatos={
                    title: {
                        text: '<span style="">No hay datos para mostrar</span>',
                        style: {
                            color: 'black'
                        }
                    },
                    anchorX: "left",
                    anchorY: "top",
                    allowDragX: true,
                    allowDragY: true,
                    x: 125,
                    y: 75
                };
                var y=0;

                var institutionPieReunaOptions={
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
                    legend:{
                        adjustChartSize: true
                    },
                    series: [{
                        type: 'pie',
                        name: 'Total',
                        data: institutionNamesReuna
                    }],
                    annotations:[]
                };
                console.log()
                if(institutionNamesReuna.length==0)institutionPieReunaOptions.annotations.push(noHayDatos);
                $('#institucionPieREUNA').highcharts(institutionPieReunaOptions);

                var institutionPieGbifOptions={
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
                        data: institutionDataGbif[0]
                    }],
                    annotations:[]
                };
                if(institutionDataGbif[0].length==0)institutionPieGbifOptions.annotations.push(noHayDatos);
                $('#institucionPieGBIF').highcharts(institutionPieGbifOptions);

                // GRAFICO TEMPORAL ANUAL GBIF
                var chartGbifOptions={
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
                        categories: drillDownDataGbif[1] ,
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
                                            setChart(chartGBIF, name, drillDownDataGbif[1], dataGBIF,'rgba(83, 173, 37, 0.8)');
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
                    }],
                    exporting: {
                        enabled: false
                    },
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled:false
                    },
                    annotations: []
                };
                y=0;
                for(var i=0;i<dataGBIF.length;i++){
                    y+=dataGBIF[i]['y'];
                }
                if(y==0)chartGbifOptions.annotations.push(noHayDatos);
                chartGBIF = new Highcharts.Chart(chartGbifOptions);

                // GRAFICO TEMPORAL ANUAL REUNA
                var chartReunaOptions={
                    chart: {
                        renderTo: 'contribucionBarras<?php echo $reuna; ?>',
                        type: 'column'
                    },
                    title: {
                        text: '<?php echo $reuna; ?>',
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold'
                        }
                    },
                    xAxis: {
                        categories: drillDownDataReuna[1] ,
                        labels: {
                            rotation:-45,
                            style: {

                                color: '#000000'
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
                                            setChart(chartREUNA, name, drillDownDataReuna[1], dataREUNA,'rgba(0, 0, 0, 0.8)');
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
                    }],
                    exporting: {
                        enabled: false
                    },
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled:false
                    },
                    annotations:[]
                };
                y=0;
                for(var i=0;i<dataREUNA.length;i++){
                    y+=dataREUNA[i]['y'];
                }
                if(y==0)chartReunaOptions.annotations.push(noHayDatos);
                chartREUNA = new Highcharts.Chart(chartReunaOptions);
                // GRAFICO TEMPORAL MENSUAL REUNA
                var reunaGbifBarrasOptions={
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
                        name: '<?php echo $reuna; ?>',
                        data: monthCountReuna
                    }
                    ],
                    annotations:[]
                };
                y=0;
                for(var i=0;i<monthCountReuna.length;i++){
                    y+=monthCountReuna[i];
                }
                if(y==0)reunaGbifBarrasOptions.annotations.push(noHayDatos);
                $('#reunaGbifBarras').highcharts(reunaGbifBarrasOptions);
                // GRAFICO TEMPORAL MENSUAL GBIF
                var gbifBarrasMesOptions={
                    chart: {
                        type: 'column'
                    },
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled: false,
                        adjustChartSize: true
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
                        data: monthCountGbif
                    },
                    ],
                    annotations:[]
                };
                y=0;
                for(var i=0;i<monthCountGbif.length;i++){
                    y+=monthCountGbif[i];
                }
                if(y==0)gbifBarrasMesOptions.annotations.push(noHayDatos);
                $('#GbifBarrasmes').highcharts(gbifBarrasMesOptions);
                // GRAFICO TEMPORAL ACUMULACION
                var acumulatedOptions={
                    chart: {
                        type: 'area'
                    },
                    credits: {
                        enabled: false
                    },
                    legend: {
                        adjustChartSize: true
                    },
                    title: {
                        text: 'Acumulación registros en el tiempo por fuente de datos',
                        style: '"fontSize": "12px"'
                    },
                    xAxis: {
                        allowDecimals: false,
                        labels: {
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
                        data: accumulatedDataGbif,
                        color:'#53AD25'
                    },{
                        name: '<?php echo $reuna; ?>',
                        data: accumulatedDataReuna,
                        color:'#000000'
                    }],
                    annotations:[]
                };
                y=0;
                for(var i=0;i<accumulatedDataGbif.length;i++){
                    y+=accumulatedDataGbif[i];
                }
                for(var i=0;i<accumulatedDataReuna.length;i++){
                    y+=accumulatedDataReuna[i];
                }
                if(y==0)acumulatedOptions.annotations.push(noHayDatos);
                $('#acumuladas').highcharts(acumulatedOptions);
            }
        }

    })(jQuery);
    var arrayCoordinatesReunaInJS =<?php echo json_encode($coordinatesReunaInPhp);?>;
    var arrayCoordinatesGbifInJS =<?php echo json_encode($coordinatesGbifInPhp);?>;
    var coordYearsReuna =<?php if(isset($coordYearsReuna)&&$coordYearsReuna!="")echo "[".$coordYearsReuna."]";else{echo "[]";}?>;
    var coordYearsGbif =<?php if(isset($coordYearsGbif)&&$coordYearsGbif!="")echo "[".$coordYearsGbif."]";else{echo "[]";}?>;
    var largo = (arrayCoordinatesReunaInJS.length);
    if (largo > 0) {
        var features = new Array(largo);
        for (var i = 0; i < arrayCoordinatesReunaInJS.length; i ++) {
            var coordinate = [parseFloat(arrayCoordinatesReunaInJS[i][1]), parseFloat(arrayCoordinatesReunaInJS[i][0])];
            var tempLonlat = ol.proj.transform(coordinate, 'EPSG:4326', 'EPSG:3857');
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
        distance: 8,
        source: source
    });
    function createLegend(){
        var legend='<div class="legendRow"><div class="qntity">Registros</div></div>';
        document.getElementById("legend").innerHTML = legend;
        for(var i=6;i>=1;i--){
            legend='<div class="legendRow"><div class="colorType" style="background-color:'+getColor(i-1)+';"></div><div class="qntity">'+(i==6?'>5':i)+'</div></div>';
            document.getElementById("legend").innerHTML += legend;
        }
    }
    createLegend();
    function getColor(index) {
        var colors = ['#FFFF00', '#FFD700', '#FFA500', '#FF8C00', '#FF4500', '#FF2000'];
        return colors[index] ? colors[index] : '#FF2000';
    }
    var capaOsm = new ol.layer.Tile({
        source: new ol.source.OSM()
    });
    //{source: new ol.source.MapQuest({layer: 'osm'})}
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
    });
    var map = new ol.Map({
        target: 'mapContainer',
        layers: [capaOsm, geoJson, clusters],
        renderer: 'canvas',
        view: mapView
    });
    var largoGBIF = (arrayCoordinatesGbifInJS.length);
    if (largoGBIF > 0) {
        var featuresGBIF = new Array(largoGBIF);
        for (var i = 0; i < arrayCoordinatesGbifInJS.length; i ++) {
            var coordinateGBIF = [parseFloat(arrayCoordinatesGbifInJS[i][0]), parseFloat(arrayCoordinatesGbifInJS[i][1])];
            var tempLonlatGBIF = ol.proj.transform(coordinateGBIF, 'EPSG:4326', 'EPSG:3857');
            featuresGBIF[i] = new ol.Feature(new ol.geom.Point(tempLonlatGBIF));
        }
    }
    var sourceGBIF = new ol.source.Vector({
        features: featuresGBIF
    });
    var clusterSourceGBIF = new ol.source.Cluster({
        distance: 8,
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
