(function( $ ) {
  "use strict";

  var debug = main_data.debug;
  var rest_url = frontpage_data.rest_url;
  var api_key = main_data.api_key;
  var api_secret = main_data.api_secret;
  var api_args = '&astrodj_api_key=' + api_key + '&astrodj_api_secret=' + api_secret;

  /* ---------------------------------------------------------------------------
   * Loader widget
   * --------------------------------------------------------------------------- */
   $(window).on('load', function() {
    $('.widget-loader-wrapper').remove();

    $('.gallery-item').each(function(i) {
      var that = $(this);
      var rand = 1 + Math.floor(Math.random() * 6);

      setTimeout(function() {
        that.css({
          'opacity': '1',
          'visibility': 'visible'
        });
      }, 150 * rand);
    });
  });

  /* ---------------------------------------------------------------------------
   * Infinite scroll loading posts
   * --------------------------------------------------------------------------- */
  $( document ).ready(function() {
    // initial loading posts
    var pageHeight = $('#page').outerHeight(),
        containerHeight = Math.round($('.recent-posts').outerHeight()),
        articleHeight = $('.recent-posts article').outerHeight();

    var delta = pageHeight - containerHeight;
    var containerVisible = Math.round($(window).outerHeight() - delta);

    if (containerHeight === containerVisible) {
      var countPosts = Math.ceil(containerHeight / articleHeight);

      if (debug) {
        console.log(countPosts);
      }

      init_posts_query(countPosts);
    } else {
      // $('#frontpage__loader').remove();
      // $('body').removeClass('frontpage__loader');

      infinite_post_trigger();
    }

    function init_posts_query(count) {
      $.ajax({
        type: 'post',
        data: {
          posts_per_page: count,
          action: 'astrodj_frontpage_init_query'
        },
        url: main_data.ajax_url
      })

      .done(function(res) {
        $('#loader_container').before(res);

        // $('#frontpage__loader').remove();
        // $('body').removeClass('frontpage__loader');

        astrodj_lqip();
        infinite_post_trigger();
      })
      
      .fail(function() {
      });
    }

    // Infinite scroll
    function infinite_post_trigger() {
      var trigger = $('#loader_container');
      var trigger_position = trigger.offset().top - $(window).outerHeight();
      
      $(window).on('scroll', function(event) {
        if (trigger_position > $(window).scrollTop()) {
          return;
        }
        
        get_previous_post(trigger);
        
        $(this).off(event);
      });
    }

    function get_previous_post(trigger) {
      var previous_post_ID = trigger.prev('article').data('previous-id');
      var json_url = rest_url + previous_post_ID + '?_embed=true' + api_args;
      
      trigger.find('.lds-ellipsis').show();

      $.ajax({
        dataType: 'json',
        url: json_url
      })

      .done(function(object) {
        // console.log(object);
        the_previous_post(object);

        astrodj_lqip();
      })
      
      .fail(function() {
        if (debug) {
          console.log('error');
        }
        trigger.find('.lds-ellipsis').hide();
        trigger.replaceWith('<article class=""><p>Постов больше нет...</p></article>');
      });

      function the_previous_post(object) {
        var featured_image_ID = object.featured_media;
        var feat_image;

        function get_featured_image() {
          if ( featured_image_ID === 0 ) {
              feat_image = '';
          } else {
              var featured_object = object._embedded['wp:featuredmedia'][0];
              var img_width = featured_object.media_details.sizes.full.width;
              var img_height = featured_object.media_details.sizes.full.height;
              var thumbnail_class = object.meta.featured_image_format_meta === 'vertical' ? ' vertical' : '';
              feat_image = '<div class="post-thumbnail-header">' +
                          '<a href="' + object.link + '">' +
                          '<figure class="astrodj-lqip post-thumbnail blog-thumbnail' + thumbnail_class + '" data-alt="' + featured_object.alt_text + '" ' +
                          'data-src="' + featured_object.media_details.sizes.full.source_url + '" ' +
                          '>' + 
                          '<div class="aspect-ratio-fill" style="padding-bottom: 66.7%;width: 100%;height: 0;"></div>' +
                          '<div class="astrodj-lqip__wrap">' +
                          '<img width="' + img_width + '" height="' + img_height + '" alt="" class="placeholder" ' + 
                          'src="' + featured_object.media_details.sizes.astrodj_lqip.source_url + '">' + 
                          '</div>' +
                          '<div class="astrodj-lqip__wrap">' +
                          '<img width="' + img_width + '" height="' + img_height + '" class="lazy">' + 
                          '</div>' +
                          '</figure>' +
                          '</a>' +
                          '</div>';
          }
          return feat_image;
        }

        function entry_meta_category_list() {
          var category_list = object._embedded['wp:term'][0],
              list = [];
          for (var i = 0; i < category_list.length; i++) {
            list.push('<a href="' + category_list[i].link + '" rel="category tag">' + category_list[i].name + '</a>');
          }
          return list.join(', ');
        }

        function entry_header() {
          var entry_header = 
              '<header class="entry-header">' +
              '<span class="posted-on">' +
              '<time class="entry-date published">' + object.astrodj_date + '</time>' +
              '</span>' +
              '<div class="entry-meta">' +
              '<span class="cat-links">' +
              entry_meta_category_list() +
              '</span>' +
              '</div>' +
              '<h2 class="entry-title">' +
              '<a href="' + object.link + '" rel="bookmark">' + object.title.rendered + '</a>' + 
              '</h2>' +
              '</header>';

          return entry_header;
        }

        function excerpt() {
          var excerpt;

          if ( object.excerpt.rendered === '' ) {
            excerpt = '';
          } else {
            excerpt = '<div class="entry-content">' + object.excerpt.rendered + '</div>';
          }

          return excerpt;
        }

        function build_post() {
          var previous_post_content = 
              '<article id="post-' + object.id + '" class="astrodj-post post front-page-infinite" data-previous-id="' + object.previous_post_ID + '">' +
              '<div class="post__content">' +
              entry_header() +
              get_featured_image() +
              excerpt() +
              '</div>' +
              '</article>';

          trigger.before(previous_post_content);

          setInterval(
            function() {
              trigger.prev('.front-page-infinite').addClass('loaded');
            }, 150
          );

          trigger.find('.lds-ellipsis').hide();

          infinite_post_trigger();
        }

        build_post();
      }
    }

    /* ---------------------------------------------------------------------------
    * LQIP - copy from main.js
    * --------------------------------------------------------------------------- */
    function astrodj_lqip() {
      // specifying selector for navigation
      var lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));

      if ('IntersectionObserver' in window) {
        var lazyImageObserver = new IntersectionObserver(function(entries, observer) {
          entries.forEach(function(entry) {
            if (entry.isIntersecting) {
              var lazyImage = entry.target;
              var bg = lazyImage.closest('.astrodj-lqip__wrap').previousElementSibling;
              var placeholder = bg.firstElementChild;
              var figure = lazyImage.closest('figure');
              bg.classList.add('bg');
              setTimeout(function() { 
                placeholder.classList.add('visible');
                lazyImage.alt = figure.hasAttribute('data-alt') ? figure.dataset.alt : '';
                lazyImage.src = figure.dataset.src;
                lazyImage.srcset = figure.hasAttribute('data-srcset') ? figure.dataset.srcset : '';
                lazyImage.sizes = figure.hasAttribute('data-sizes') ? figure.dataset.sizes : '';
                lazyImage.addEventListener('load', function(e) {
                  e.target.classList.add('visible');
                });
                lazyImageObserver.unobserve(lazyImage);
              }, 300);
            }
          });
        }, {
          threshold: 0.25
        });

        lazyImages.forEach(function(lazyImage) {
          lazyImageObserver.observe(lazyImage);
        });
      }

      // Calculate image aspect ratio
      $('img.lazy').each(function(el) {
        var img_width = parseInt($(this).attr('width')),
            img_height = parseInt($(this).attr('height')),
            aspect_ratio,
            padding_bottom;

        if (img_width > img_height) {
          aspect_ratio = img_width / img_height;
          padding_bottom = 100 / aspect_ratio;
        } else {
          aspect_ratio = img_height / img_width;
          padding_bottom = 100 * aspect_ratio;
        }

        $(this).parents('.astrodj-lqip__wrap').prevAll('.aspect-ratio-fill').attr('style', 'padding-bottom: ' + padding_bottom + '%;width: 100%;');
      });
    }
  });

}(jQuery));
