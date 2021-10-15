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
  'class-emc-base.php'
);

foreach( $inc_files as $inc_file ){
	require_once( $inc_file );
}

define( 'GF_SIMPLE_FIELD_ADDON_VERSION', '1.0' );

add_action( 'gform_loaded', array( 'GF_AreaCode_AddOn', 'load' ), 5 );

class GF_AreaCode_AddOn{
  public static function load() {
    if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
      return;
    }
    require_once( 'gf-fields/class-gf-areacode-field.php' );
    require_once( 'gf-fields/class-gf-areaname-field.php' );
    //GFAddOn::register( 'GF_Field_AreaCode' );

    //require_once( 'gf-fields/class-gf-areacode-field.php' );
    //GFAddOn::register( 'GF_Field_AreaCode' );
  }
}



class EMC_API extends EMC_BASE{

  function processRequest( $url ){
    $ch = curl_init();
  	curl_setopt( $ch, CURLOPT_URL, $url );
  	curl_setopt( $ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0' );
  	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  	curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  	$result = curl_exec($ch);
  	return json_decode($result);
  }

  function cachedProcessRequest( $url, $cache_min = 5 ){
		$cache_key = 'emc' . md5( $url );
		$data = array();

		// Get any existing copy of our transient data
		if ( false === ( $data = get_transient( $cache_key ) ) ) {
      $data = $this->processRequest( $url );
      set_transient( $cache_key, $data, $cache_min * MINUTE_IN_SECONDS );
		}
		return $data;
	}

}

add_filter( 'emc_areaname_value', function( $areacode ){
  $url = "https://api.postcodes.io/postcodes/$areacode";
  $response = EMC_API::getInstance()->cachedProcessRequest( $url, 600 );
  $areaname = $response->result->parliamentary_constituency;
  return $areaname;
} );



function getUniqueCodeOfMPByPostCode( $postcode ){
  $url = "https://api.postcodes.io/postcodes/$postcode";
  $response = EMC_API::getInstance()->cachedProcessRequest( $url, 600 );
  return $response->result->codes->parliamentary_constituency;
}


add_shortcode( 'test-shortcode', function( $atts ){
  ob_start();
  echo "<pre>";
  print_r( getUniqueCodeOfMPByPostCode( 'e162sq' ) );
  echo "</pre>";
  return ob_get_clean();
} );

add_action( 'gform_field_standard_settings', function( $position, $form_id ){
  if ( $position == 20 ) {
    ?>
      <li class="areacode_setting field_setting">
        <label for="field_areacode_value">
          <?php _e("Area Code Input ID", "gravityforms"); ?>
          <?php gform_tooltip("form_field_encrypt_value") ?>
        </label>
        <input type="text" id="field_areacode_value" onblur="SetFieldProperty('areaCodeInputID', this.value);" />
      </li>
      <?php
    }
}, 10, 2 );


//Action to inject supporting script to the form editor page
add_action( 'gform_editor_js', 'editor_script' );
function editor_script(){
    ?>
    <script type='text/javascript'>
        fieldSettings.text += ', .areacode_setting';
        jQuery(document).on('gform_load_field_settings', function(event, field, form){
            jQuery( '#field_areacode_value' ).val( rgar( field, 'areaCodeInputID' ) );
        });
    </script>
    <?php
}
//Filter to add a new tooltip
add_filter( 'gform_tooltips', 'add_encryption_tooltips' );
function add_encryption_tooltips( $tooltips ) {
   $tooltips['form_field_encrypt_value'] = "<h6>Encryption</h6>Check this box to encrypt this field's data";
   return $tooltips;
}
