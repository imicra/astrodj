<?php
/**
 * Template part for displaying page content in template-shop.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Astrodj
 */

$args = array(
	'post_type'      => 'shop',
  'post_status'    => 'publish',
  'posts_per_page' => -1,
  // 'meta_key' => '_imicra_program_date',
  // 'orderby' => 'meta_value_num',
  // 'order' => 'ASC'
);
$shop = new WP_Query( $args );

if ( $shop->have_posts() ) :
?>
  <div class="astrodj-shop-header">
    <h2>Фотокалендари на 2025 год</h2>
    <p>Отметьте понравившиеся, обратите внимание на изменение цены, затем в любом месте нажмите кнопку <span class="button-emulate">Заказать</span>.</p>
  </div>
  <div class="astrodj-shop-container">
    <?php
    while ( $shop->have_posts() ) :
      $shop->the_post();
    ?>
    <div class="astrodj-shop">
      <div class="shop-check">
        <label>
          <input type="checkbox" value="<?php echo get_the_title() . ' - ' . wp_filter_nohtml_kses( apply_filters( 'the_content', get_the_content() ) ); ?>" data-price="250">
          <span></span>
        </label>
      </div>
      <div class="shop-thumbnail">
        <?php astrodj_post_thumbnail_lqip( '', 'full' ); ?>
      </div>
      <div class="shop-content">
        <div class="content">
          <h3><?php the_title(); ?></h3>
          <?php the_content(); ?>
        </div>
        <div class="cart">
          <span class="cart-price">250 &#8381;</span>
          <button class="shop-cart" data-fancybox data-src="#shop_form" href="javascript:;">Заказать</button>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
<?php
endif;
wp_reset_postdata();
