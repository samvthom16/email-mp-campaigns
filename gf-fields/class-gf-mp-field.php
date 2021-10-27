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

    function getAreaCodeFromUserInput(){ return rgpost( $this->areaCodeInputID ); }
    function getMP(){ return apply_filters( 'emc_mp_value', $this->getAreaCodeFromUserInput() ); }
    function getMPName(){ $mp = $this->getMP(); return $mp[5]; }
    function getMPEmail(){ $mp = $this->getMP(); return $mp[9]; }

    public function get_field_label( $force_frontend_label, $value ) {
  		$label = $force_frontend_label ? $this->label : GFCommon::get_label( $this );
      if ( '' === $label ) {
  			if ( '' !== rgar( $this, 'placeholder' ) ) {
  				$label = $this->get_placeholder_value( $this->placeholder );
  			} elseif ( '' !== $this->description ) {
  				$label = wp_strip_all_tags( $this->description );
  			}
  		}

      $label .= ' ' . $this->getMPName();
      return $label;
  	}

    public function get_field_input( $form, $value = '', $entry = null ) {
      $mp_email = $this->getMPEmail();
      $mp_email = 'sam@sputznik.com';
      $form_id  = $form['id'];
  		$id       = intval( $this->id );
      $field_id = $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";
      $field_markup = "<input type='hidden' name='input_{$id}' id='{$field_id}' value='{$mp_email}' />";
      return "<p style='margin-top:0;'>{$field_markup}</p>";
    }

}
GF_Fields::register( new GF_Field_MP() );
