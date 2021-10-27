<?php

class GF_AreaCode_AddOn{
  public static function load() {
    if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
      return;
    }

    require_once( 'gf-fields/class-gf-areacode-field.php' );
    require_once( 'gf-fields/class-gf-areaname-field.php' );
    require_once( 'gf-fields/class-gf-mp-field.php' );

    add_action( 'gform_field_standard_settings', 'GF_AreaCode_AddOn::add_standard_settings', 10, 2 );

    //Action to inject supporting script to the form editor page
    add_action( 'gform_editor_js', 'GF_AreaCode_AddOn::add_editor_script' );

    //Filter to add a new tooltip
    add_filter( 'gform_tooltips', 'GF_AreaCode_AddOn::add_tooltips' );
  }

  public static function add_standard_settings( $position, $form_id ){
    if ( $position == 20 ) {
      ?>
      <li class="areacode_setting field_setting">
        <label for="field_areacode_value">
          <?php _e("Area Code Input ID", "gravityforms"); ?>
          <?php gform_tooltip("form_field_areacode_value") ?>
        </label>
        <input type="text" id="field_areacode_value" onblur="SetFieldProperty('areaCodeInputID', this.value);" />
      </li>
      <?php
    }
  }

  public static function add_editor_script(){
    ?>
    <script type='text/javascript'>
      //fieldSettings.text += ', .areacode_setting';
      jQuery(document).on('gform_load_field_settings', function(event, field, form){
        jQuery( '#field_areacode_value' ).val( rgar( field, 'areaCodeInputID' ) );
      });
    </script>
    <?php
  }

  public static function add_tooltips( $tooltips ) {
    $tooltips['form_field_areacode_value'] = "<h6>Area Code</h6>Fill the Input ID of the Area Code field";
    return $tooltips;
  }

}

add_action( 'gform_loaded', array( 'GF_AreaCode_AddOn', 'load' ), 5 );
