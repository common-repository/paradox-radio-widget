<?php
/*
Plugin Name: PARADOX Radio Widget
Plugin URI: http://paradox-radio.com/radio-widget-wordpress-plugin
Description: PARADOX Radio Widget
Version: 1.0.4
Author: PARADOX
Author URI: http://paradox-radio.com/
*
* Development by Arkadius Jonczek :)
*
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Paradox_Radio_Plugin
{
    protected static $instance = false;

    public static function getInstance() {

        if (self::$instance === false)
            self::$instance = new self;

        return self::$instance;
    }

    private function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
    }

    public function register_plugin_styles() {
        wp_register_style( 'fontello', plugins_url( 'paradox-radio-widget/fontello/css/fontello.css' ) );
        wp_enqueue_style( 'fontello' );

        wp_register_style( 'paradox-radio-widget', plugins_url( 'paradox-radio-widget/paradox-radio-widget.css' ) );
        wp_enqueue_style( 'paradox-radio-widget' );
    }

    public function register_plugin_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-slider');

        wp_register_script( 'soundmanager2', plugins_url( 'paradox-radio-widget/script/soundmanager2-nodebug-jsmin.js' ) );
        wp_enqueue_script( 'soundmanager2' );

        wp_register_script( 'paradox-radio-widget', plugins_url( 'paradox-radio-widget/paradox-radio-widget.js' ) );
        wp_enqueue_script( 'paradox-radio-widget' );
    }

    public function render($return = false, $shortcode = false) {

        if ($return) {
            ob_start();
        }

        include __DIR__ . DIRECTORY_SEPARATOR . 'paradox-radio-widget-template.php';

        if ($return) {
            $output_string = ob_get_contents();
            ob_end_clean();

            return $output_string;
        }
    }
}

$paradox_radio_plugin = Paradox_Radio_Plugin::getInstance();

class Paradox_Radio_Widget extends WP_Widget {

    protected $paradox_radio_plugin;

    function __construct() {
        parent::__construct( false, 'Paradox Radio Widget' );

        $this->paradox_radio_plugin = Paradox_Radio_Plugin::getInstance();
    }

    function widget( $args, $instance ) {

        echo $args['before_widget'];

        $this->paradox_radio_plugin->render();

        echo $args['after_widget'];
    }

    function update( $new_instance, $old_instance ) {
        // Save widget options
    }

    function form( $instance ) {
        // Output admin widget options form
    }
}

function paradox_shoutcast_plugin_register_widget() {
    register_widget( 'Paradox_Radio_Widget' );
}

add_action( 'widgets_init', 'paradox_shoutcast_plugin_register_widget' );

function paradox_shoutcast_shortcode_func() {
    $paradox_radio_plugin = Paradox_Radio_Plugin::getInstance();

    return $paradox_radio_plugin->render(true, true);
}

add_shortcode('paradox_radio', 'paradox_shoutcast_shortcode_func');