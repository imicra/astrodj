(function( $ ) {
  "use strict";

  $( document ).ready(function() {
    var debug = main_data.debug;
    var rest_url = portfolio_data.rest_url,
        ajax_url = portfolio_data.ajax_url,
        api_key = main_data.api_key,
        api_secret = main_data.api_secret,
        api_args = '&astrodj_api_key=' + api_key + '&astrodj_api_secret=' + api_secret;

    function infinite_post_trigger() {
      var trigger = $('#loader_container');
      var trigger_position = trigger.offset().top - $(window).outerHeight();
      
      $(window).on('scroll', function(event) {
        if (trigger_position > $(window).scrollTop()) {
          return;
        }
        
        if ($(document).width() < 576) {
          get_previous_post(trigger);
        } else {
          get_previous_posts(trigger);
        }
        
        $(this).off(event);
      });
    }

    infinite_post_trigger();

    /* ---------------------------------------------------------------------------
    * Load More on mobile screen
    * --------------------------------------------------------------------------- */
    function get_previous_post(trigger) {
      var previous_post_ID = trigger.attr('data-previous-id'),
          loader = trigger.find('svg'),
          json_url = rest_url + previous_post_ID + '?_embed=true' + api_args;
      
      loader.show();

      $.ajax({
        dataType: 'json',
        url: json_url
      })

      .done(function(object) {
        // console.log(object);
        loader.hide();
        the_previous_post(object);

        astrodj_lqip();
      })
      
      .fail(function() {
        if (debug) {
          console.log('error');
        }
        loader.hide();
        trigger.prepend('<article class="astrodj-post-navigation__end"><p>Снимков больше нет...</p></article>');
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
              feat_image = '<a href="' + object.link + '" data-hash-id="' + object.id + '">' +
                          '<figure class="astrodj-lqip" data-alt="' + featured_object.alt_text + '" ' +
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
                          '<figcaption>' +
                          '<h2 class="entry-title">' + object.title.rendered + '</h2>'+
                          '</figcaption>' +
                          '</figure>' +
                          '</a>';
          }
          return feat_image;
        }

        function build_post() {
          var thumbnail_class = object.cmb2.astrodj_portfolio_meta_metabox.astrodj_radio_orientation;

          var previous_post_content = 
              '<article id="post-' + object.id + '" class="astrodj-portfolio ' + thumbnail_class + '">' +
              get_featured_image() +
              '</article>';

          trigger.prev('.portfolio-content').append(previous_post_content);

          trigger.attr('data-previous-id', object.previous_post_ID);

          infinite_post_trigger();
        }

        build_post();
      }
    }

    /* ---------------------------------------------------------------------------
    * Load More on big screen
    * --------------------------------------------------------------------------- */
    function get_previous_posts(trigger) {
      var post_ID = trigger.attr('data-this-id'),
          post_type = trigger.data('cpt'),
          loader = trigger.find('svg');

          if (debug) {
            console.log(post_ID);
          }

      $.ajax({
        url: ajax_url,
        type: 'post',
        data: {
          post_id: post_ID,
          post_type: post_type,
          action: 'astrodj_portfolio_infinite'
        },
        beforeSend: function() {
          loader.show();
        },
        success: function(response) {
          loader.hide();

          if (response == 0) {
            trigger.prepend('<article class="astrodj-post-navigation__end"><p>Снимков больше нет...</p></article>');
          } else {
            trigger.prev('.portfolio-content').append(response);

            astrodj_lqip();

            set_trigger_id();

            infinite_post_trigger();
          }

          if (debug) {
            console.log('Ajax Portfolio Infinite');
          }
        },
        error: function(jqxhr, status, errorMsg) {
          if (debug) {
            console.log('error Portfolio Infinite: ' + errorMsg);
          }
        }
      });
    }

    function set_trigger_id() {
      var loader_wrapper = $('#loader_wrapper'),
          trigger_id = loader_wrapper.find('.astrodj-portfolio').last().data('id');

      loader_wrapper.find('#loader_container').attr('data-this-id', trigger_id);
    }

    /* ---------------------------------------------------------------------------
    * LQIP - copy from main.js
    * --------------------------------------------------------------------------- */
    function astrodj_lqip() {
      // specifying selector for navigation
      var lazyImages = [].slice.call(document.querySelectorAll('#loader_wrapper img.lazy'));

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
