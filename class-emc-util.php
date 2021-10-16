<?php

class EMC_UTIL extends EMC_BASE{

  function cache( $fn, $params = array(), $cache_min = 5 ){
    $data = array();

    $temp_arr = is_array( $params ) ? $params : array( $params );
    $cache_key = 'emc' . md5( $fn . implode( ',', $temp_arr ) );

		// Get any existing copy of our transient data
		if ( false === ( $data = get_transient( $cache_key ) ) ) {
      $data = $this->$fn( $params );
      set_transient( $cache_key, $data, $cache_min * MINUTE_IN_SECONDS );
		}
		return $data;
  }

  function api( $url ){
    $ch = curl_init();
  	curl_setopt( $ch, CURLOPT_URL, $url );
  	curl_setopt( $ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0' );
  	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  	curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  	$result = curl_exec($ch);
  	return json_decode($result);
  }

  // CONVERT CSV FILE TO ARRAY
  function convertCSVToArray( $file_path ){
    $arrayCsv = array();
    $file     = fopen(  $file_path, "r" );

    // ITERATE THROUGH THE FILE TO READ
    while ( !feof( $file ) ) {
      $fpTotal = fgetcsv( $file );
      array_push( $arrayCsv, $fpTotal );
    }
    fclose($file);

    return $arrayCsv;
  }
}
