<?php
/**
 * Template Name: Deacons Detail
 */

get_header();

$id = isset($_GET['id']) ? sanitize_text_field($_GET['id']) : '';
$data = $id ? fetch_firebase_data('clergy/deacons/' . $id) : null;
?>

<main style="padding:80px 10%; text-align:center;">
  <?php if ($data) : ?>
    <?php if (!empty($data['image'])) : ?>
      <img src="<?php echo esc_url($data['image']); ?>" alt="<?php echo esc_attr($data['fatherName'] ?? $data['name']); ?>" style="width:200px; border-radius:50%; margin-bottom:20px;">
    <?php endif; ?>
    <h1><?php echo esc_html($data['fatherName'] ?? $data['name']); ?></h1>
    <?php if (!empty($data['vicarAt']) || !empty($data['vicar'])) : ?>
      <p><strong>Vicar At:</strong> <?php echo esc_html($data['vicarAt'] ?? $data['vicar']); ?></p>
    <?php endif; ?>
    <?php if (!empty($data['phoneNumber']) || !empty($data['phonenumber'])) : ?>
      <p><strong>Phone Number:</strong> <?php echo esc_html($data['phoneNumber'] ?? $data['phonenumber']); ?></p>
    <?php endif; ?>
  <?php else : ?>
    <p>No deacons data found.</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
