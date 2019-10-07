<?php

// Подключение стилей

add_action( 'wp_enqueue_scripts', 'theme_styles' );
function theme_styles() {
	wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'main', get_template_directory_uri() . '/css/main.css' );
}

?>