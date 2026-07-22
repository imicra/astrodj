<?php
/**
 * Contact Form Ajax Processing.
 * 
 * Contact Form in template page-contact.php.
 * 
 * @package astrodj
 */

/**
 * Contact form.
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

/**
 * Shop popup form.
 */
function astrodj_shop_form_cb() {
  $order = wp_strip_all_tags( $_POST['order'] );
  $price = wp_strip_all_tags( $_POST['price'] );
  $name = wp_strip_all_tags( $_POST['name'] );
  $email = wp_strip_all_tags( $_POST['email'] );
  $city = wp_strip_all_tags( $_POST['city'] );
  $text = wp_strip_all_tags( $_POST['message'] );
  $success = false;

  $order = explode( ',', trim( rtrim( $order, ',' ) ) );
  $order = implode( '</li><li>', $order );

  $args = array(
    'post_title'    => $name . ' - Календарь',
    'post_content'  => '<ul><li>' . $order . '</li></ul>' . '<br><br>' . $text,
    'post_author'   => 1,
    'post_status'   => 'publish',
    'post_type'     => 'contact',
    'meta_input'    => array(
        '_contact_email_value_key' => $email,
        '_contact_city_value_key' => $city,
        '_contact_price_value_key' => $price
    )
  );
  $postID = wp_insert_post( $args );

  if ( $postID !== 0 ) {
    $success = true;

    $to = get_bloginfo( 'admin_email' );
    $subject = 'Astrodj.ru Shop';
    $host = $_SERVER['SERVER_NAME'];

    $headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <contact@' . $host . '>';
    $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    $headers[] = 'Content-Type: text/html';
    $headers[] = 'charset=UTF-8';

    $message = '';

    if ( ! empty( $name ) ) {
      $message .= "Имя: <b>{$name}</b><br>";
    }
    if ( ! empty( $email ) ) {
      $message .= "Email: <b>{$email}</b><br>";
    }
    if ( ! empty( $city ) ) {
      $message .= "Город: <b>{$city}</b><br>";
    }
    if ( ! empty( $order ) ) {
      $message .= "Заказ: <ul><li>{$order}</li></ul><br>";
    }
    if ( ! empty( $price ) ) {
      $message .= "Цена: <b>{$price}</b><br>";
    }
    if ( ! empty( $text ) ) {
      $message .= "Пожелание: <b>{$text}</b><br>";
    }

    wp_mail( $to, $subject, $message, $headers );
  }

  wp_send_json([
    'success' => $success
  ]);
}
add_action( 'wp_ajax_astrodj_shop_form', 'astrodj_shop_form_cb' );
add_action( 'wp_ajax_nopriv_astrodj_shop_form', 'astrodj_shop_form_cb' );

/**
 * Single Portfolio and Stock Shop popup form.
 */
function astrodj_single_shop_form_cb() {
  $name = ''; // not used
  $email = wp_strip_all_tags( $_POST['email'] );
  $city = ''; // not used
  $text = ''; // not used
  $post_id = wp_strip_all_tags( $_POST['post_id'] );
  $success = false;

  $admin_url = admin_url( 'post.php?post=' . $post_id ) . '&action=edit';

  $args = array(
    'post_title'    => 'Заказ - Фото',
    'post_content'  => '<p><a href="' . get_the_permalink( $post_id ) . '" target="_blank">' . get_the_title( $post_id ) . '</a></p>' . '<br><br>' . $text,
    'post_author'   => 1,
    'post_status'   => 'publish',
    'post_type'     => 'contact',
    'meta_input'    => array(
        '_contact_email_value_key' => $email,
        '_contact_city_value_key' => $city,
        '_contact_price_value_key' => get_option( 'astrodj_price' )
    )
  );
  $postID = wp_insert_post( $args );

  if ( $postID !== 0 ) {
    $success = true;

    $to = get_bloginfo( 'admin_email' );
    $subject = 'Astrodj.ru Shop';
    $host = $_SERVER['SERVER_NAME'];

    $headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <contact@' . $host . '>';
    $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    $headers[] = 'Content-Type: text/html';
    $headers[] = 'charset=UTF-8';

    $message = '<p>Я получил заказ на фотографию с сайта Astrodj.ru</p>';

    if ( ! empty( $name ) ) {
      $message .= "Имя: <b>{$name}</b><br>";
    }
    if ( ! empty( $email ) ) {
      $message .= "Email: <b>{$email}</b><br>";
    }
    if ( ! empty( $post_id ) ) {
      $message .= '<a href="' . get_the_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a></b><br>';
    }

    // to admin
    wp_mail( $to, $subject, $message, $headers );

    // to customer
    $message = '';
    $message .= '<p>Здравствуйте!</p>';
    $message .= '<p>Спасибо за заказ!</p>';
    $message .= '<p>В ближайшее время я отправлю вам ссылку на готовое объявление с фотографиями на Авито.</p>';
    $message .= '<p>Вы заказли: <a href="' . get_the_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a></p>';
    $message .= '<p>С уважением,<br>Василий Жигалов <a href="https://astrodj.ru/">Astrodj.ru</a></p>';

    $headers[] = 'From: Astrodj.ru <contact@' . $host . '>';
    $headers[] = 'Content-Type: text/html';
    $headers[] = 'charset=UTF-8';

    wp_mail( $email, 'Astrodj.ru Ваш заказ', $message, $headers );
  }

  wp_send_json([
    'success' => $success
  ]);
}
add_action( 'wp_ajax_astrodj_single_shop_form', 'astrodj_single_shop_form_cb' );
add_action( 'wp_ajax_nopriv_astrodj_single_shop_form', 'astrodj_single_shop_form_cb' );

/**
 * SMTP for mail.ru
 */
function smtp_phpmailer_init( $phpmailer ) {
	// only on hosting
	if ( WP_DEBUG ) {
		return;
	}

	$phpmailer->IsSMTP();

	$phpmailer->CharSet    = 'UTF-8';

	$phpmailer->Host       = 'smtp.mail.ru';
	$phpmailer->Username   = 'astrodj@mail.ru';
	$phpmailer->Password   = 'Qy1Vds9GMjfuFerTnyAi';
	$phpmailer->SMTPAuth   = true;
	$phpmailer->SMTPSecure = 'tls';

	$phpmailer->Port       = 587;
	$phpmailer->From       = 'contact@astrodj.ru';
	$phpmailer->FromName   = 'Astrodj.ru Контактная Форма';

	$phpmailer->isHTML( true );
}
// add_action( 'phpmailer_init', 'smtp_phpmailer_init' );
