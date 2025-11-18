<?php
//  BIGFATHERS SHORTCODE
function shortcode_firebase_bigfathers()
{
    $bigfathers = fetch_firebase_data('bigFathers'); // Firebase node name

    if (empty($bigfathers)) {
        return '<p style="text-align:center>No Big Fathers found.</p>';
    }

    $output = '<div class="bigfathers-grid">';
    foreach ($bigfathers as $key => $father) {
        $name = esc_html($father['fatherName'] ?? 'Unknown');
        $positions = esc_html($father['positions'] ?? 'N/A');
        $image = esc_url($father['image'] ?? '');

        $output .= '<div class="bigfather-card">';
        if ($image) {
            $output .= '<img src="' . $image . '" alt="' . $name . '">';
        }
        $output .= '<h3>' . $name . '</h3>';
        $output .= '<p><strong></strong> ' . $positions . '</p>';
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('firebase_bigfathers', 'shortcode_firebase_bigfathers');



