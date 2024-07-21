<?php
/*
Plugin Name: Formulario
Description: Primeiro plugin.
Version: 1.0
Author: Rodrigo de Freitas Camargo
*/

// Função para criar a tabela no banco de dados ao ativar o plugin
register_activation_hook(__FILE__, 'criar_tabela_formulario');

function criar_tabela_formulario() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'formulario';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nome varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Função para registrar o widget do Elementor
function register_form_widget() {
    if ( did_action( 'elementor/loaded' ) ) {
        require_once( __DIR__ . '/widgets/form-widget.php' );
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

// Função para renderizar o formulário e processar a submissão
function renderizar_formulario() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'formulario';

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_formulario'])) {
        $nome = sanitize_text_field($_POST['nome']);
        $email = sanitize_email($_POST['email']);

        // Insere os dados no banco de dados
        $wpdb->insert(
            $table_name,
            array(
                'nome' => $nome,
                'email' => $email,
            )
        );

        // Exibe mensagem de sucesso
        $formulario = '<p>Obrigado, ' . $nome . '! Seu email (' . $email . ') foi recebido e salvo.</p>';
        return $formulario;
    }

    // HTML do formulário
    $formulario = '
    <form action="" method="post">
        <label id="labelNome" for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>
        <label id="labelEmail" for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" id="btnSubmit" name="submit_formulario" value="Enviar">
    </form>';

    return $formulario;
}
add_shortcode('formulario_shortcode', 'renderizar_formulario');



