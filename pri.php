<?php
/*
Plugin Name: Formulario
Description: Primeiro plugin.
Version: 1.0
Author: Rodrigo de Freitas Camargo
*/

// Função para registrar o widget do Elementor
function register_form_widget() {
    // Verifique se Elementor está ativo e carregado
    if ( did_action( 'elementor/loaded' ) ) {
        // Inclua o arquivo da classe do widget
        require_once( __DIR__ . '/widgets/form-widget.php' );

        // Registre o widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Form_Widget() );
    }
}
add_action( 'elementor/widgets/widgets_registered', 'register_form_widget' );

// Função para carregar arquivos CSS e JS
function enqueue_form_styles() {
    wp_enqueue_style( 'form-styles', plugin_dir_url( __FILE__ ) . 'css/form-styles.css' );
    wp_enqueue_script( 'form-scripts', plugin_dir_url( __FILE__ ) . 'js/form-scripts.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_form_styles' );

// Adicionar o shortcode ao plugin
add_shortcode('formulario_shortcode', 'renderizar_formulario');

function renderizar_formulario() {
    // HTML do formulário
    $formulario = '
    <form action="" method="post">
        <label id="labelNome" for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>
        <label id="labelEmail" for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" id="btnSubmit" name="submit_formulario" value="Enviar">
    </form>';

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_formulario'])) {
        $nome = sanitize_text_field($_POST['nome']);
        $email = sanitize_email($_POST['email']);

        // Aqui você pode processar os dados do formulário como desejar
        $formulario .= '<p>Obrigado, ' . $nome . '! Seu email (' . $email . ') foi recebido.</p>';
    }

    return $formulario;
}

