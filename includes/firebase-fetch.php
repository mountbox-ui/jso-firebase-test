<?php
// includes/firebase-fetch.php

// if (!function_exists('fetch_firebase_data')) {
//     function fetch_firebase_data($path)
//     {
//         $firebase_url = 'https://jsochurch-7c956-default-rtdb.firebaseio.com/' . $path . '.json';
//         $response = wp_remote_get($firebase_url);

//         if (is_wp_error($response)) {
//             return [];
//         }

//         $body = wp_remote_retrieve_body($response);
//         return json_decode($body, true);
//     }
// }


if (!function_exists('fetch_firebase_data')) {
    function fetch_firebase_data($path, $use_first_project = false)
    {
        // Default Firebase (currently used everywhere)
        $default_project = 'https://jsochurch-7c956-default-rtdb.firebaseio.com/';

        // Optional first project (only used in one file)
        $secondary_project = 'https://jso-church-directory-default-rtdb.firebaseio.com/';

        // Choose which project to use
        $firebase_url = ($use_first_project ? $secondary_project : $default_project) . $path . '.json';

        $response = wp_remote_get($firebase_url);

        if (is_wp_error($response)) {
            return [];
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }
}


