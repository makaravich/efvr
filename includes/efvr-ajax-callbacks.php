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
            'ID' => $post_id,
            'post_type' => 'efvr_relation',
            'post_status' => 'publish',

            /*            'meta_input' => array(
                            '' => '',
                        ),*/
        );

        // Update data in efvr_relation posts
        $rel_post_result = wp_update_post($rel_post, true);

    }
    //else {    $prod_res = true; }

    if (is_wp_error($rel_post_result)) {
        echo $rel_post_result->get_error_message();
        die();
    }

    if ($rel_post_result) {
        echo __('The relation info has been successfully saved') . " post_id=$post_id";
    } else {
        echo __('There was an error during saving the relation');
    }
    wp_die();
}