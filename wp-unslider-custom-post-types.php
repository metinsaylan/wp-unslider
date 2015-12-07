<?php 

/* Add custom post type */
 add_action( 'init', 'wp_unslider_create_post_types' );
 add_action( 'init', 'wp_unslider_taxonomies' );
function wp_unslider_create_post_types() {
  $labels = array(
    'name' => __('Slides', 'wp-unslider'),
    'singular_name' => __('Slide', 'wp-unslider'),
    'add_new' => __('Add Slide', 'wp-unslider'),
    'add_new_item' => __('Add New Slide', 'wp-unslider'),
    'edit_item' => __('Edit Slide', 'wp-unslider'),
    'new_item' => __('New Slide', 'wp-unslider'),
    'all_items' => __('All Slides', 'wp-unslider'),
    'view_item' => __('View Slide', 'wp-unslider'),
    'search_items' => __('Search Slides', 'wp-unslider'),
    'not_found' =>  __('No Slides Found', 'wp-unslider'),
    'not_found_in_trash' => __('No Slides Found', 'wp-unslider'), 
    'parent_item_colon' => '',
    'menu_name' => __('Slides', 'wp-unslider')
  );
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
	'menu_position' => 4,
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'supports' => array( 'title', 'thumbnail' ),
	'taxonomies' => array( 'slide_group'  )
  ); 
  
  register_post_type( 'slide', $args);
  
    /*$labels = array(
    'name' => __('Köşe Yazarları', 'wp-unslider'),
    'singular_name' => __('Köşe Yazarı', 'wp-unslider'),
    'add_new' => __('Yazar Ekle', 'wp-unslider'),
    'add_new_item' => __('Yeni Yazar Ekle', 'wp-unslider'),
    'edit_item' => __('Yazarı Düzenle', 'wp-unslider'),
    'new_item' => __('Yeni Köşe Yazarı', 'wp-unslider'),
    'all_items' => __('Tüm Köşe Yazarları', 'wp-unslider'),
    'view_item' => __('Yazar Sayfasını Görüntüle', 'wp-unslider'),
    'search_items' => __('Köşe Yazarı Ara', 'wp-unslider'),
    'not_found' =>  __('Hiç Yazar Bulunamadı', 'wp-unslider'),
    'not_found_in_trash' => __('Çöpte yazar bulunamadı', 'wp-unslider'), 
    'parent_item_colon' => '',
    'menu_name' => __('Köşe Yazarları', 'wp-unslider')
  );
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
	'menu_position' => 4,
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'supports' => array( 'title', 'post_thumbnail' ),
	'taxonomies' => array( 'post_tag'  )
  ); 
  
  register_post_type('yazarlar',$args); 
  
  $labels = array(
    'name' => __('Listeler', 'wp-unslider'),
    'singular_name' => __('Liste', 'wp-unslider'),
    'add_new' => __('Liste Ekle', 'wp-unslider'),
    'add_new_item' => __('Yeni Liste Ekle', 'wp-unslider'),
    'edit_item' => __('Listeyi Düzenle', 'wp-unslider'),
    'new_item' => __('Yeni Liste', 'wp-unslider'),
    'all_items' => __('Tüm Listeler', 'wp-unslider'),
    'view_item' => __('Listeyi Görüntüle', 'wp-unslider'),
    'search_items' => __('Liste Ara', 'wp-unslider'),
    'not_found' =>  __('Hiç Liste Bulunamadı', 'wp-unslider'),
    'not_found_in_trash' => __('Çöpte Liste Bulunamadı', 'wp-unslider'), 
    'parent_item_colon' => '',
    'menu_name' => __('Listeler', 'wp-unslider')
  );
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
	'menu_position' => 4,
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'supports' => array( 'title', 'thumbnail', 'comments' ),
	'taxonomies' => array( 'post_tag'  )
  ); 
  
  register_post_type('listeler',$args);*/
  
  	/*$labels = array(
    'name' => __('Futbol Takımları', 'wp-unslider'),
    'singular_name' => __('Takım', 'wp-unslider'),
    'add_new' => __('Takım Ekle', 'wp-unslider'),
    'add_new_item' => __('Yeni Takım Ekle', 'wp-unslider'),
    'edit_item' => __('Takımı Düzenle', 'wp-unslider'),
    'new_item' => __('Yeni Takım', 'wp-unslider'),
    'all_items' => __('Tüm Takımlar', 'wp-unslider'),
    'view_item' => __('Takımı Görüntüle', 'wp-unslider'),
    'search_items' => __('Takım Ara', 'wp-unslider'),
    'not_found' =>  __('Hiç Takım Bulunamadı', 'wp-unslider'),
    'not_found_in_trash' => __('Çöpte takım bulunamadı', 'wp-unslider'), 
    'parent_item_colon' => '',
    'menu_name' => __('Takımlar', 'wp-unslider')
  );
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
	'menu_position' => 6,
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
	'taxonomies' => array( 'post_tag'  )
  ); 
  
  register_post_type('futbol-takimlari',$args);*/
  
}

function wp_unslider_taxonomies(){

	$labels = array(
		'name' => __( 'Decks', 'wp-unslider'),
		'singular_name' => __( 'Deck',  'wp-unslider'),
		'search_items' =>  __( 'Search Decks' , 'wp-unslider'),
		'all_items' => __( 'All Decks' , 'wp-unslider'),
		'parent_item' => __( 'Parent Deck' , 'wp-unslider'),
		'parent_item_colon' => __( 'Parent Deck:' , 'wp-unslider'),
		'edit_item' => __( 'Edit Deck', 'wp-unslider'), 
		'update_item' => __( 'Update Deck' , 'wp-unslider'),
		'add_new_item' => __( 'Add New Deck' , 'wp-unslider'),
		'new_item_name' => __( 'New Deck Name' , 'wp-unslider'),
		'menu_name' => __( 'Slide Decks', 'wp-unslider'),
	); 	

	register_taxonomy( 'slidedeck', array('slide'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'slidedecks' ),
	) );
}