<?php
add_action('init', 'efvr_cpt');
function efvr_cpt()
{
    register_post_type('efvr_relation', array(
        'labels' => array(
            'name' => 'Relations', // Main title of CPT
            'singular_name' => 'Relation', // Name of single CPT
            'add_new' => 'Add new',
            'add_new_item' => 'Add new relation',
            'edit_item' => 'Edit Relation',
            'new_item' => 'New Relation',
            'view_item' => 'View Relation',
            'search_items' => 'Find Relation',
            'not_found' => 'Not Found Relations',
            'not_found_in_trash' => 'Not found in trash',
            'parent_item_colon' => '',
            'menu_name' => 'Elementor Relations'

        ),
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 22,
        'supports' => array('title', 'custom-fields'/*'editor','author','thumbnail','excerpt','comments'*/)
    ));
}
