<?php

function holiday_post_types() {

  //Event Post Type
  register_post_type('event', array(
    'capability_type' => 'event',
    'map_meta_cap' => true,
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'excerpt'),
    'rewrite' => array('slug' => 'events'),
    'has_archive' => true,
    'public' => true,
    'labels' => array(
       'name' => 'Events',
       'add_new_item' => 'Add New Event',
       'edit_item' => 'Edit Event',
       'all_items' => 'All Events',
       'singular_name' => 'Event'
    ),
    'menu_icon' => 'dashicons-calendar'
  ));

   //Destinations Post Types
   register_post_type('destination', array(
     'taxonomies' => array('category'),
     'show_in_rest' => true,
     'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
     'rewrite' => array('slug' => 'destinations'),
     'has_archive' => true,
     'public' => true,
     'labels' => array(
        'name' => 'Destinations',
        'add_new_item' => 'Add New Destination',
        'edit_item' => 'Edit Destination',
        'all_items' => 'All Destinations',
        'singular_name' => 'Destination'
     ),
     'menu_icon' => 'dashicons-admin-site-alt2'
   ));

   //Villas Post Types
   register_post_type('villa', array(
     'capability_type' => 'post',
     'map_meta_cap' => true,
     'show_in_rest' => true,
     'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
     'public' => true,
     'publicly_queryable' => true,
     'show_ui' => true,
	   'show_in_menu' => true,
     'query_var' => true,
     'rewrite' => array('slug' => 'villas'),
     'has_archive' => true,
	   'hierarchical' => false,
     'labels' => array(
        'name' => 'Villas',
        'add_new_item' => 'Add New Villa',
        'edit_item' => 'Edit Villa',
        'all_items' => 'All Villas',
        'singular_name' => 'Villa'
     ),
     'menu_icon' => 'dashicons-admin-home'
   ));

   //Clients Post Types
   register_post_type('client', array(
    'capability_type'    => 'post',
    'show_in_rest'       => true,
    'supports'           => array('title', 'author', 'comments'),
    'rewrite'            => array('slug' => 'clients'),
    'has_archive'        => false,
    'public'             => false,
    'publicly_queryable' => false,
    'show_ui'            => true,
	  'show_in_menu'       => true,
    'query_var'          => true,
    'labels' => array(
       'name'          => 'Clients',
       'singular_name' => 'Client',
       'add_new_item'  => 'Add New Client',
       'edit_item'     => 'Edit Client',
       'all_items'     => 'All Clients'
    ),
    'menu_icon'        => 'dashicons-groups'
  ));

}

add_action('init', 'holiday_post_types');


?>
