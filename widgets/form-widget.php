<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Sair se acessado diretamente.
}

class Elementor_Form_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'form_widget';
    }

    public function get_title() {
        return __( 'Form Widget', 'plugin-name' );
    }

    public function get_icon() {
        return 'fa fa-code';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'plugin-name' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'form_shortcode',
            [
                'label' => __( 'Form Shortcode', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( '[formulario_shortcode]' , 'plugin-name' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo do_shortcode( $settings['form_shortcode'] );
    }
}

