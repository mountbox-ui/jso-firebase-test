<?php
// includes/firebase-fetch.php

if (!function_exists('fetch_firebase_data')) {
    function fetch_firebase_data($path) {
        $firebase_url = 'https://sitjsochurch-default-rtdb.firebaseio.com/' . $path . '.json';
        $response = wp_remote_get($firebase_url);

        if (is_wp_error($response)) {
            return [];
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }
}
