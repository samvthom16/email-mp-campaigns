<?php

class GF_Field_MP extends GF_Field {

    public $type = 'mp';

    public function get_form_editor_field_title() {
      return esc_attr__( 'Member Of Parliament', 'gravityforms' );
    }

    public function get_form_editor_field_description() {
  		return esc_attr__( 'Show the member of parliament after the user puts in the area code.', 'gravityforms' );
  	}

    public function get_form_editor_field_icon() {
  		return 'gform-icon--name-2';
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
      $areaCodeInputID = $this->areaCodeInputID;
      $areacode = rgpost( $areaCodeInputID );
      $mp = apply_filters( 'emc_mp_value', $areacode );

      $form_id  = $form['id'];
  		$id       = intval( $this->id );
      $field_id = $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

      $field_markup = "<input type='hidden' name='input_{$id}' id='{$field_id}' value='{$mp[9]}' />";

      $mp_name = $mp[5];

      return "<p style='margin-top:0;'>{$mp_name}{$field_markup}</p>";


    }

}
GF_Fields::register( new GF_Field_MP() );
