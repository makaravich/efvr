<?php

add_action('wp_ajax_fvr_update_relation', 'fvr_update_relation_callback');
add_action('wp_ajax_nopriv_fvr_update_relation', 'fvr_update_relation_callback');
function fvr_update_relation_callback()
{
    $post_id = strip_tags($_REQUEST['id']);
    $post_title = strip_tags($_REQUEST['title']);


    //if ( $post_id )
    {
        // Create an array of data for new relation
        $rel_post = array(
            'post_title' => $post_title,
            'post_type' => 'efvr_relation',
            'status' => 'publish',

            /*            'meta_input' => array(
                            '' => '',
                        ),*/
        );

        // Update data in efvr_relation posts
        $rel_post_result = wp_insert_post($rel_post);

    }
    //else {    $prod_res = true; }


    if ($rel_post_result) {
        echo __('The relation info has been successfully saved') . "Post_id=$post_id";
    } else {
        echo __('There was an error during saving the relation');
    }
    wp_die();
}