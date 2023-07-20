<?php
/**
 * Contact Form Ajax Processing.
 * 
 * Contact Form in template page-contact.php.
 * 
 * @package astrodj
 */

/**
 * Ajax action.
 */
add_action( 'wp_ajax_astrodj_contact_form', 'astrodj_contact_form_cb' );
add_action( 'wp_ajax_nopriv_astrodj_contact_form', 'astrodj_contact_form_cb' );
 
function astrodj_contact_form_cb() {
  $title = wp_strip_all_tags( $_POST['name'] );
  $email = wp_strip_all_tags( $_POST['email'] );
  $message = wp_strip_all_tags( $_POST['message'] );

  $args = array(
    'post_title'    => $title,
    'post_content'  => $message,
    'post_author'   => 1,
    'post_status'   => 'publish',
    'post_type'     => 'contact',
    'meta_input'    => array(
        '_contact_email_value_key' => $email
    )
  );

  $postID = wp_insert_post( $args );

  if ( $postID !== 0 ) {
    $to = get_bloginfo( 'admin_email' );
    $subject = 'Astrodj.ru Контактная Форма - ' . $title;
    $host = $_SERVER['SERVER_NAME'];

    // $headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . $to . '>';
    // На некоторых серверах в поле FROM должен фигурировать домен сайта, 
    // иначе, например, яндекс почта не получит письмо.
    $headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <contact@' . $host . '>';
    $headers[] = 'Reply-To: ' . $title . ' <' . $email . '>';
    $headers[] = 'Content-Type: text/html';
    $headers[] = 'charset=UTF-8';

    wp_mail( $to, $subject, $message, $headers );

    $output = '<div class="message-container">';
    $output .= '<div class="message-header">Письмо отправлено!</div>';
    $output .= '<div class="message-body">';
    $output .= '<div class="message-title">Вы писали:</div>';
    $output .= '<div class="message-content">';
    $output .= '<div class="message-content--tip"></div>';
    $output .= '<div class="message-text"><span>' . $title . '</span></div>';
    $output .= '<div class="message-text"><span>' . $email . '</span></div>';
    $output .= '<div class="message-text">' . $message . '</div>';
    $output .= '</div>';
    $output .= '<div class="message-footer">Спасибо за интерес!</div>';
    $output .= '</div>';
    $output .= '</div>';

    echo $output;
  } else {
    echo 0;
  }

  wp_die();
}
