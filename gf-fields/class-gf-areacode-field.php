<?php

class GF_Field_AreaCode extends GF_Field {

    public $type = 'areacode';

    public function get_form_editor_field_title() {
      return esc_attr__( 'Area Code', 'gravityforms' );
    }

    public function get_form_editor_field_description() {
  		return esc_attr__( 'Allows users to enter their areacode in the format you have specified.', 'gravityforms' );
  	}

    public function get_form_editor_field_icon() {
  		return 'gform-icon--name';
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
  			//'autocomplete_setting',
  		);
  	}

    function validate( $value, $form ) {
      $areacode = apply_filters( 'emc_areacode_value', $value );
      if( !$areacode ){
        $this->failed_validation  = true;
        $this->validation_message = $this->errorMessage;
      }
    }

    public function get_field_input( $form, $value = '', $entry = null ) {

      $class_suffix = '';
      $css_class = '';
      $style = '';
      $form_id  = $form['id'];
  		$id       = intval( $this->id );
      $field_id = $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

      $input  = GFFormsModel::get_input( $this, $this->id );
      $placeholder_attribute  = $this->get_field_placeholder_attribute();
      $tabindex  = $this->get_tabindex();

      $aria_label  = esc_attr__( 'Area Code', 'gravityforms' );

      $field_markup = "<input type='text' name='input_{$id}' id='{$field_id}' value='{$value}' aria-label='{$aria_label}' {$placeholder_attribute} {$tabindex} />";

      return "<div class='ginput_complex{$class_suffix} ginput_container {$css_class}' id='{$field_id}'>
          {$field_markup}
          <div class='gf_clear gf_clear_complex'></div>
        </div>";
    }

}
GF_Fields::register( new GF_Field_AreaCode() );
