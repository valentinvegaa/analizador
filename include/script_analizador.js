/**
 * Created by valentinvegaa on 24-09-2014.
 */
jQuery(document).ready(function () {
    var arrayCoordinatesInJS = "";//<?php if($coordinatesInPHP!="")echo "[".$coordinatesInPHP."]";else{echo "[]";}?>;
    var arrayCoordinatesGBIFInJS = "";//<?php if($coordinatesGBIFInPHP!="")echo "[".$coordinatesGBIFInPHP."]";else{echo "[]";}?>;
    var largo = (arrayCoordinatesInJS.length) / 2;
    if (largo > 0) {
        var features = new Array(largo);
        var j = 0;
        for (var i = 0; i < arrayCoordinatesInJS.length - 1; i += 2) {
            //alert(arrayCoordinatesInJS[i] + " " + arrayCoordinatesInJS[i+1]);
            var coordinate = [arrayCoordinatesInJS[i + 1], arrayCoordinatesInJS[i]];
            var tempLonlat = ol.proj.transform(coordinate, 'EPSG:4326', 'EPSG:3857');
            //var tempLonlat = [arrayCoordinatesInJS[i], arrayCoordinatesInJS[i+1]];
            features[j] = new ol.Feature(new ol.geom.Point(tempLonlat));
            j++;
        }
        ;
    }

    var source = new ol.source.Vector({
        features: features
    });

    var clusterSource = new ol.source.Cluster({
        distance: 8,
        source: source
    });

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
                        radius: 10,
                        stroke: new ol.style.Stroke({
                            color: '#fff'
                        }),
                        fill: new ol.style.Fill({
                            color: '#F00'
                        })
                    }),
                    text: new ol.style.Text({
                        text: size.toString(),
                        fill: new ol.style.Fill({
                            color: '#fff'
                        })
                    })
                })];
                styleCache[size] = style;
            }
            return style;
        },
        //projection: ol.proj.get('EPSG:4326')
    });
    var map = new ol.Map({
        target: 'mapContainer',
        layers: [capaOsm, clusters],
        renderer: 'canvas',
        view: new ol.View({
            projection: 'EPSG:3857',
            center: ol.proj.transform([-70.143812, -38.063057], 'EPSG:4326', 'EPSG:3857'),
            zoom: 4,
            minZoom: 4,
            maxZoom: 18,

        })
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
            featuresGBIF[j] = new ol.Feature(new ol.geom.Point(tempLonlatGBIF));
            j++;
        }
        ;
    }
    var sourceGBIF = new ol.source.Vector({
        features: featuresGBIF
    });

    var clusterSourceGBIF = new ol.source.Cluster({
        distance: 8,
        source: sourceGBIF
    });

    var capaOsmGBIF = new ol.layer.Tile({source: new ol.source.MapQuest({layer: 'osm'})});
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
                        radius: 10,
                        stroke: new ol.style.Stroke({
                            color: '#fff'
                        }),
                        fill: new ol.style.Fill({
                            color: '#F00'
                        })
                    }),
                    text: new ol.style.Text({
                        text: size.toString(),
                        fill: new ol.style.Fill({
                            color: '#fff'
                        })
                    })
                })];
                styleCacheGBIF[size] = style;
            }
            return style;
        },
        //projection: ol.proj.get('EPSG:4326')
    });
    var mapGBIF = new ol.Map({
        target: 'mapContainerGBIF',
        layers: [capaOsmGBIF, clustersGBIF],
        renderer: 'canvas',
        view: new ol.View({
            projection: 'EPSG:3857',
            center: ol.proj.transform([-73.143812, -37.063057], 'EPSG:4326', 'EPSG:3857'),
            zoom: 4,
            minZoom: 4,
            maxZoom: 18,
        })
    });
    //mapGBIF.bindTo('layergroup', map);
    mapGBIF.bindTo('view', map);
});