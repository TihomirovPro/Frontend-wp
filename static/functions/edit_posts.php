<?php

function edit_admin_menus() {
	global $menu;
	global $submenu;
	$menu[5][0] = 'Услуги';
		$submenu['edit.php'][5][0] = 'Все';
		$submenu['edit.php'][10][0] = 'Добавить';
}

add_action( 'admin_menu', 'edit_admin_menus' );

// add_filter( 'admin_init', 'disable_text' );

// function disable_text() {
//   remove_post_type_support('post','editor');
// }