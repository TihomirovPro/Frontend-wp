<?php

// Подключение скриптов

add_action( 'wp_enqueue_scripts', 'theme_scripts' );
function theme_scripts(){
//   wp_deregister_script( 'jquery' );
	wp_enqueue_script( 'scripts', get_template_directory_uri() . '/js/main.js', array(), null, true);
	// wp_enqueue_script( 'filter_ajax', get_theme_file_uri( '/js/filter_ajax.js' ), array( 'jquery' ), null, true );
	// wp_localize_script( 'filter_ajax', 'filter_ajax_params', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

}