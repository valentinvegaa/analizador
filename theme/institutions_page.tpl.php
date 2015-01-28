<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 28-01-2015
 * Time: 11:23
 */
$path = drupal_get_path('module', 'analizador_biodiversidad');
include($path . '/include/solrConnection.php');
include($path . '/Apache/Solr/Service.php');