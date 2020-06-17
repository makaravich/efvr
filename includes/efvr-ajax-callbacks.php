<?php

add_action('wp_ajax_fvr_update_relation', 'fvr_update_relation_callback');
//add_action('wp_ajax_nopriv_fvr_update_relation', 'fvr_update_relation_callback');
function fvr_update_relation_callback()
{
    $post_id = strip_tags($_REQUEST['id']);
    $post_title = strip_tags($_REQUEST['title']);
    $master_value = strip_tags($_REQUEST['master_value']);

    //if ($post_id == 1) $post_id = '';


    // Create an array of data for new relation
    $rel_post = array(
        'post_title' => $post_title,
        'post_type' => 'efvr_relation',
        'post_status' => 'publish',

        'meta_input' => array(
            'master_value' => $master_value,
        ),
    );

    $res = array();

    if ($post_id != '1') {
        $rel_post['ID'] = $post_id;
        // Update data in efvr_relation posts
        $rel_post_result = wp_update_post($rel_post, true);
    } else {
        //Create new post
        $rel_post_result = wp_insert_post($rel_post, true);
    }


    if (is_wp_error($rel_post_result)) {
        $res['error'] = $rel_post_result->get_error_message();
        $res['success'] = 0;

    } else {
        $res['error'] = '';
        $res['success'] = 1;
    }

    if ($rel_post_result) {
        $res['result'] = __('The relation has been successfully saved') . " post_id=$post_id";
        $res['post_id'] = $rel_post_result;
    } else {
        $res['result'] = __('There was an error during saving the relation');
        $res['post_id'] = 0;
    }

    $res['title'] = $post_title;
    $res['master_value'] = $master_value;

    echo json_encode($res);
    wp_die();
}

add_action('wp_ajax_fvr_get_relation_meta', 'fvr_get_relation_meta_callback');
//add_action('wp_ajax_nopriv_fvr_get_relation_meta', 'fvr_get_relation_meta_callback');
function fvr_get_relation_meta_callback()
{
    $post_id = strip_tags($_REQUEST['id']);
    $post_meta=get_post_meta($post_id);
    echo json_encode($post_meta);
    wp_die();
}