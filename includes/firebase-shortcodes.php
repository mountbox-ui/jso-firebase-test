<?php
// includes/firebase-shortcodes.php

//  CHURCH SHORTCODE
error_log('firebase-shortcodes.php loaded');

function shortcode_firebase_churches() {
    $churches = fetch_firebase_data('church');

    if (empty($churches)) {
        return '<p>No church data found.</p>';
    }

    $output = '<div class="church-list">';
    foreach ($churches as $key => $church) {
        $output .= '<div class="church-item" style="margin-bottom:20px;">';
        $output .= '<h3>' . esc_html($church['churchName'] ?? 'Unknown Church') . '</h3>';
        $output .= '<p><strong>Address:</strong> ' . esc_html($church['address'] ?? 'N/A') . '</p>';
        $output .= '<p><strong>Diocese:</strong> ' . esc_html($church['diocese'] ?? 'N/A') . '</p>';
        $output .= '<p><strong>Phone:</strong> ' . esc_html($church['phoneNumber'] ?? 'N/A') . '</p>';
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('firebase_churches', 'shortcode_firebase_churches');


//  BIGFATHERS SHORTCODE
function shortcode_firebase_bigfathers() {
    $bigfathers = fetch_firebase_data('bigFathers'); // Firebase node name

    if (empty($bigfathers)) {
        return '<p>No Big Fathers found.</p>';
    }

    $output = '<div class="bigfathers-grid">';
    foreach ($bigfathers as $key => $father) {
        $name = esc_html($father['fatherName'] ?? 'Unknown');
        $email = esc_html($father['emailId'] ?? 'N/A');
        $dob = esc_html($father['dob'] ?? 'N/A');
        $image = esc_url($father['image'] ?? '');

        $output .= '<div class="bigfather-card">';
        if ($image) {
            $output .= '<img src="' . $image . '" alt="' . $name . '">';
        }
        $output .= '<h3>' . $name . '</h3>';
        $output .= '<p><strong>Email:</strong> ' . $email . '</p>';
        $output .= '<p><strong>DOB:</strong> ' . $dob . '</p>';
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('firebase_bigfathers', 'shortcode_firebase_bigfathers');

