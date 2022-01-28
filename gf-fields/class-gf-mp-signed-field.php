<?php

class GF_Field_MP_Signed extends GF_Field {

    public $type = 'mp_signed';

    public function get_form_editor_field_title() {
      return esc_attr__( 'MP Signed', 'gravityforms' );
    }

    public function get_form_editor_field_description() {
  		return esc_attr__( 'Shows the boolean flag indicating if the MP has signed or not.', 'gravityforms' );
  	}

    public function get_form_editor_field_icon() {
  		return 'gform-icon--hidden';
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

    public function is_conditional_logic_supported() {
  		return true;
  	}

    public function get_form_editor_inline_script_on_page_render() {
      $script = sprintf( "function SetDefaultValues_%s(field) {field.label = '%s';}", $this->type, $this->get_form_editor_field_title() ) . PHP_EOL;
      return $script;
    }


    function getAreaCodeFromUserInput(){ return rgpost( $this->areaCodeInputID ); }
    function getMP(){ return apply_filters( 'emc_mp_value', $this->getAreaCodeFromUserInput() ); }
    function getMPName(){ $mp = $this->getMP(); return $mp[5]; }
    function getMPEmail(){ $mp = $this->getMP(); return $mp[9]; }
    function getMPFlag(){ $mp = $this->getMP(); return $mp[12]; }

    public function get_field_input( $form, $value = '', $entry = null ) {
      $mp_email = $this->getMPFlag();
      $form_id  = $form['id'];
  		$id       = intval( $this->id );
      $field_id = $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";
      $field_markup = "<input type='hidden' name='input_{$id}' id='{$field_id}' value='{$mp_email}' />";
      return "<p style='margin-top:0;'>{$field_markup}</p>";
    }


}
GF_Fields::register( new GF_Field_MP_Signed() );
