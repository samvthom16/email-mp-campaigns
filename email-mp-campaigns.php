<?php
/*
	Plugin Name: Campaign Email to MPs
	Plugin URI: https://sputznik.com/
	Description: Send campaign emails to the MP based on post code
	Version: 1.0.0
	Author: Samuel Thomas, Sputznik
	Author URI: https://sputznik.com/
*/

if( ! defined( 'ABSPATH' ) ){
	exit;
}

$inc_files = array(
  'class-emc-base.php',
  'class-emc-util.php',
  'class-gf-areacode-addon.php'
);

foreach( $inc_files as $inc_file ){
	require_once( $inc_file );
}


add_filter( 'emc_areaname_value', function( $areacode ){
  $url = "https://api.postcodes.io/postcodes/$areacode";
  $response = EMC_UTIL::getInstance()->cache( 'api', $url, 600 );
  $areaname = $response->result->parliamentary_constituency;
  return $areaname;
} );

add_filter( 'emc_areacode_value', function( $areacode ){
  $url = "https://api.postcodes.io/postcodes/$areacode";
  $response = EMC_UTIL::getInstance()->cache( 'api', $url, 600 );
  $areaname = $response->result->codes->parliamentary_constituency;
  return $areaname;
} );

add_filter( 'emc_mp_value', function( $areacode ){
  $id = apply_filters( 'emc_areacode_value', $areacode );
  $data = EMC_UTIL::getInstance()->cache( 'convertCSVToArray', plugin_dir_path(__FILE__) . '/mps-list.csv', 600 );
  foreach( $data as $mp_data ){
    // ID will be the first column of the CSV data
    if( $mp_data[0] == $id ){
      return $mp_data;
    }
  }
  return array();
} );

add_shortcode( 'test-shortcode', function( $atts ){

  $url = "https://api.postcodes.io/postcodes/e162sq";
  $response = EMC_UTIL::getInstance()->cache( 'convertCSVToArray', plugin_dir_path(__FILE__) . '/mps-list.csv' );

  ob_start();
  echo "<pre>";
  print_r( $response );
  echo "</pre>";
  return ob_get_clean();
} );
