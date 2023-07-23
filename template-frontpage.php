<?php
/**
 * The template for displaying the Frontpage.
 *
 * Template name: Frontpage
 *
 * @package astrodj
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site front-page__wrapper">
  <div class="front-page__nav">
    <div class="front-page__nav--inner">
      <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">/follow/<strong>astrodj</strong></a>
    </div>
  </div>
  <header id="masthead" class="site-header">
    <div class="front-page__hero">
      <div class="post-thumbnail">
        <?php get_template_part( 'template-parts/header/header', 'image' ); ?>
      </div><!-- .post-thumbnail -->
      <?php get_template_part( 'template-parts/header/frontpage', 'user' ); ?>
    </div>
    <div class="front-page__sub-nav">
      <?php astrodj_dropdown_menu() ?>
    </div>
  </header>

  <div id="content" class="site-content">
    <div id="primary" class="content-area">
      <main id="main" class="site-main">

      <?php
      while ( have_posts() ) :
        the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( array( 'front-page-post' ) ); ?>>
          <div class="entry-content">
            <?php the_content(); ?>
          </div><!-- .entry-content -->
          <?php get_sidebar( 'frontpage' ); ?>
        </article><!-- #post-<?php the_ID(); ?> -->
        
      <?php
      endwhile; // End of the loop.
      ?>

      <div class="front-page__count">
        <?php
        $total = wp_count_posts( 'post', 'readable' )->publish;

        echo '<div class="item"><span class="item-count">' . $total . '</span><span> Блог</span></div>';

        $total = wp_count_posts( 'portfolio', 'readable' )->publish;
        echo '<div class="item"><span class="item-count">' . $total . '</span><span> Фотогалерея</span></div>';

        $total = wp_count_posts( 'stock', 'readable' )->publish;
        echo '<div class="item"><span class="item-count">' . $total . '</span><span> Сток</span></div>';
        ?>
      </div><!-- .site-branding__count -->

      <section class="recent-posts">
        <header class="page-header-index">
          <div>Последние записи</div>
        </header>
        <div class="recent-posts-inner">
          <?php
          /**
           * astrodj_placeholder_frontpage_preloader - 10
           */
          do_action( 'astrodj_frontpage_before_content' );
          ?>
          <?php
          // Get the latest post
          $args = array(
            'posts_per_page' => 1,
            'post_status'    => 'publish',
          );

          $latest_posts_query = new WP_Query( $args );

          // The Loop
          if ( $latest_posts_query->have_posts() ) {
              while ( $latest_posts_query->have_posts() ) {
                $latest_posts_query->the_post();

                // Get the standard index page content
                get_template_part( 'template-parts/content', get_post_format() );
              }
          }

          /* Restore original Post Data */
          wp_reset_postdata();
          ?>
          
          <div id="loader_container">
            <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
          </div>
        </div>
      </section><!-- .recent-posts -->

      </main>
    </div><!-- #primary -->
  </div><!-- #content -->

  <footer id="colophon" class="site-footer">
    <div class="site-info">
      <div class="site-rights">
        <span class="dev">
          &copy; 2019 - <?php echo date( 'Y' ); ?> astrodj.ru
        </span>
      </div>
    </div><!-- .site-info -->
  </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
