<?php

class GF_Field_AreaName extends GF_Field {

    public $type = 'areaname';

    public function get_form_editor_field_title() {
      return esc_attr__( 'Area Name', 'gravityforms' );
    }

    public function get_form_editor_field_description() {
  		return esc_attr__( 'Show area name after the user puts in the area code.', 'gravityforms' );
  	}

    public function get_form_editor_field_icon() {
  		return 'gform-icon--place';
  	}

    function get_form_editor_field_settings() {
  		return array(
  			'conditional_logic_field_setting',
  			'prepopulate_field_setting',
  			'error_message_setting',
  			'label_setting',
  			'admin_label_setting',
  			'label_placement_setting',
  			//'sub_label_placement_setting',
  			'default_input_values_setting',
  			'input_placeholders_setting',
  			//'name_setting',
  			'rules_setting',
  			'visibility_setting',
  			'description_setting',
  			'css_class_setting',
        'areacode_setting'
  		);
  	}

    public function get_form_editor_inline_script_on_page_render() {
      $script = sprintf( "function SetDefaultValues_%s(field) {field.label = '%s';}", $this->type, $this->get_form_editor_field_title() ) . PHP_EOL;
      return $script;
    }

    public function get_field_input( $form, $value = '', $entry = null ) {
      $areacodeInputID = rgar( $this, 'areaCodeInputID' );
      $areacode = rgpost( $areacodeInputID );
      $value = apply_filters( 'emc_areaname_value', $areacode );
      return "&nbsp;<span>$value</span>";
    }

}
GF_Fields::register( new GF_Field_AreaName() );
