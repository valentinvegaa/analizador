<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 21-01-2015
 * Time: 17:10
 */
$query = "*:*";
$additionalParameters = array(
    'fq' => '',
    'fl' => '',
    'facet' => 'true',
    'facet.field' => 'dwc.genus_s'
);
try {
    $results = $solr->search($query, 0, 0, $additionalParameters);
} catch (Exception $e) {
    die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
}
