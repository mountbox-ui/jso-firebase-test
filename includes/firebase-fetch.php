<?php
// includes/firebase-fetch.php

if (!function_exists('fetch_firebase_data')) {
    function fetch_firebase_data($path)
    {
        $firebase_url = 'https://jsochurch-7c956-default-rtdb.firebaseio.com/' . $path . '.json';
        $response = wp_remote_get($firebase_url);

        if (is_wp_error($response)) {
            return [];
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }
}

//REST API: Get Church List
// add_action('rest_api_init', function () {
//     register_rest_route('church-data/v1', '/list', [
//         'methods' => 'GET',
//         'callback' => 'jso_get_church_list',
//         'permission_callback' => '__return_true'
//     ]);
// });

// function jso_get_church_list()
// {
//     $churches = fetch_firebase_data('church');

//     if (!$churches || !is_array($churches)) {
//         return [];
//     }

//     $formatted = [];

//     foreach ($churches as $id => $church) {
//         $formatted[] = [
//             'id' => $id,
//             'churchName' => $church['churchName'] ?? '',
//             'diocese' => $church['diocese'] ?? '',
//             'vicarAt' => $church['vicarAt'] ?? '',
//             'image' => !empty($church['image']) ? $church['image'] : get_template_directory_uri() . '/assets/images/church.jpg'
//         ];
//     }

//     return $formatted;
// }



