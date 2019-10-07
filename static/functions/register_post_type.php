<?php
add_action('init', 'new_post_type');


function new_post_type() {

	register_post_type('specialists', array(
		'labels'             => array(
			'name'               => 'Специалисты',
			'singular_name'      => 'Специалист', 
			'add_new'            => 'Добавить специалиста',
			'add_new_item'       => 'Добавить нового специалиста',
			'edit_item'          => 'Редактировать специалиста',
			'new_item'           => 'Новый специалист',
			'view_item'          => 'Посмотреть специалиста',
			'search_items'       => 'Найти специалиста',
			'not_found'          => 'Специалисты не найдены',
			'not_found_in_trash' => 'В корзине специалистов не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Специалисты'

			),
		'menu_position'      => 6,
		'public'             => true,
		'show_ui'            => true,
		'has_archive'        => true,
		'menu_icon'          => 'dashicons-format-aside',
		'supports'           => array('title'),
		'query_var'          => true,
		'capability_type'    => 'post',
		'rewrite'            => array( 'slug' => 'specialists' ),
		'taxonomies'         => array('specialists-tax'),
	) );
}

add_action( 'init', 'create_taxonomies' );

function create_taxonomies(){

	register_taxonomy('specialists-tax', array('specialists'), array(
		'hierarchical'  => false,
		'labels'        => array(
			'name'              => 'Вид деятельности',
			'singular_name'     => 'Вид деятельности',
			'search_items'      => 'Поиск деятельности',
			'all_items'         => 'Все категории',
			'parent_item'       => 'Родительская категория',
			'parent_item_colon' => 'Родительская категория:',
			'edit_item'         => 'Изменить',
			'update_item'       => 'Обновить',
			'add_new_item'      => 'Добавить вид деятельности',
			'new_item_name'     => 'Новый вид деятельности',
			'menu_name'         => 'Виды деятельности',
		),
		'show_ui'       => true,
		'query_var'     => true,
		'rewrite'       => array( 'slug' => 'specialists-tax' ),
	));
}
