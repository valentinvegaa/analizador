<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 15-02-2015
 * Time: 20:07
 */
function obtenerResumen($s){
    $solr = $s;
    function getResults($parameters,$solr){
        try {
            return $solr->search('*:*', 0, 0, $parameters);
        }catch (Exception $e)
        {
            return die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
        }
    }
    $salidaReuna='';
    $parametersSpecie = array(
        'fq' => '',
        'fl' => '',
        'facet' => 'true',
        'facet.field' => 'dwc.scientificName_s',
        'facet.limit' => 1000000,
    );
    $parametersGenus = array(
        'fq' => '',
        'fl' => '',
        'facet' => 'true',
        'facet.field' => 'dwc.genus_s',
        'facet.limit' => 1000000,
    );
    $parametersFamily = array(
        'fq' => '',
        'fl' => '',
        'facet' => 'true',
        'facet.field' => 'dwc.family_s',
        'facet.limit' => 1000000,
    );
    $parametersOrder = array(
        'fq' => '',
        'fl' => '',
        'facet' => 'true',
        'facet.field' => 'dwc.order_s',
        'facet.limit' => 1000000,
    );
    $parametersClass = array(
        'fq' => '',
        'fl' => '',
        'facet' => 'true',
        'facet.field' => 'dwc.class_s',
        'facet.limit' => 1000000,
    );
    $especies=0;
    $especiesSinNombre=0;
    $generos=0;
    $generosSinNombre=0;
    $familias=0;
    $familiasSinNombre=0;
    $ordenes=0;
    $ordenesSinNombre=0;
    $clases=0;
    $clasesSinNombre=0;
    $resultsS=getResults($parametersSpecie,$solr);
    if ($resultsS) {
        foreach ($resultsS->facet_counts->facet_fields as $doc) {
            foreach ($doc as $field => $value) {
                $especies++;
                if(strcmp($field,'_empty_')==0)$especiesSinNombre=$value;
            }
        }
    }
    $resultsG=getResults($parametersGenus,$solr);
    if ($resultsG) {
        foreach ($resultsG->facet_counts->facet_fields as $doc) {
            foreach ($doc as $field => $value) {
                $generos++;
                if(strcmp($field,'_empty_')==0)$generosSinNombre=$value;
            }
        }
    }
    $resultsF=getResults($parametersFamily,$solr);
    if ($resultsF) {
        foreach ($resultsF->facet_counts->facet_fields as $doc) {
            foreach ($doc as $field => $value) {
                $familias++;
                if(strcmp($field,'_empty_')==0)$familiasSinNombre=$value;
            }
        }
    }
    $resultsO=getResults($parametersOrder,$solr);
    if ($resultsO) {
        foreach ($resultsO->facet_counts->facet_fields as $doc) {
            foreach ($doc as $field => $value) {
                $ordenes++;
                if(strcmp($field,'_empty_')==0)$ordenesSinNombre=$value;
            }
        }
    }
    $resultsC=getResults($parametersClass,$solr);
    if ($resultsC) {
        foreach ($resultsC->facet_counts->facet_fields as $doc) {
            foreach ($doc as $field => $value) {
                $clases++;
                if(strcmp($field,'_empty_')==0)$clasesSinNombre=$value;
            }
        }
    }
    $salidaReuna.='<div class="tableElement"><div class="key"><b>Taxón en Reuna</b></div><div class="value"><b>Cantidad</b></div></div>';
    $salidaReuna.='<div class="tableElement"><div class="key">Especies</div><div class="value">'.$especies.'</div></div>';
    $salidaReuna.='<div class="tableElement"><div class="key">Especies sin nombre.</div><div class="value">'.$especiesSinNombre.'</div></div>';
    $salidaReuna.='<div class="tableElement"><div class="key">Generos</div><div class="value">'.$generos.'</div></div>';
    $salidaReuna.='<div class="tableElement"><div class="key">Generos sin nombre.</div><div class="value">'.$generosSinNombre.'</div></div>';
    $salidaReuna.='<div class="tableElement"><div class="key">Familias</div><div class="value">'.$familias.'</div></div>';
    $salidaReuna.='<div class="tableElement"><div class="key">Familias sin nombre.</div><div class="value">'.$familiasSinNombre.'</div></div>';
    $salidaReuna.='<div class="tableElement"><div class="key">Ordenes</div><div class="value">'.$ordenes.'</div></div>';
    $salidaReuna.='<div class="tableElement"><div class="key">Ordenes sin nombre.</div><div class="value">'.$ordenesSinNombre.'</div></div>';
    $salidaReuna.='<div class="tableElement"><div class="key">Clases</div><div class="value">'.$clases.'</div></div>';
    $salidaReuna.='<div class="tableElement"><div class="key">Clases sin nombre.</div><div class="value">'.$clasesSinNombre.'</div></div>';
    return $salidaReuna;
}
