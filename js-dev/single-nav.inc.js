(function( $ ) {
  "use strict";

  $(window).on('load', function() {
    var debug = main_data.debug;
    var rest_url = nav_data.rest_url;
    var api_key = main_data.api_key;
    var api_secret = main_data.api_secret;
    var api_args = '&astrodj_api_key=' + api_key + '&astrodj_api_secret=' + api_secret;

    /* ---------------------------------------------------------------------------
    *  Initial scroll position for Next Posts
    * --------------------------------------------------------------------------- */
    function init_page_scroll_top() {
      var add_height = $('.astrodj-post-navigation.next').height();
      // $(this).scrollTop(add_height);
      $('html, body').animate({
        scrollTop: add_height
      }, 10, function() {
        $('.main-navigation').removeClass('hide-menu');
      });
    }
    
    // init_page_scroll_top();
    
    /* ---------------------------------------------------------------------------
    *  Single Post's Header sticky behavior
    * --------------------------------------------------------------------------- */
    function single_header_positioning() {
      if (! $('.astrodj-post-navigation.next').length) {
        return;
      }

      $(window).on('scroll', function() {
        var wScroll = $(window).scrollTop();
        
        // 4 - .main-navigation border 4px
        if (wScroll < $('.hentry').offset().top - $('.site-header').height() - $('.main-navigation').height() + 4) {
          $('.single-post .site-header').css({
            'position': 'sticky',
            'top': '0'
          });
          $('.single-post .main-navigation').css({
            'position': 'sticky',
            'top': $('.site-header').height()
          });
        } else {
          $('.single-post .site-header').css({
            'position': 'relative',
            'top': $('.hentry').offset().top - $('.site-header').height() - $('.main-navigation').height()
          });
          $('.single-post .main-navigation').css({
            'position': 'relative',
            'top': $('.hentry').offset().top - $('.site-header').height() - $('.main-navigation').height()
          });
        }

        if (wScroll > $('.hentry').offset().top - $('.site-header').height() + 8) {
          $('.single-post .main-navigation').css({
            'position': 'sticky',
            'top': '0'
          });
        }
      });
    }

    single_header_positioning();

    /* ---------------------------------------------------------------------------
    *  Single Template Previouse Posts Load More from REST API
    * --------------------------------------------------------------------------- */
    function previous_post_trigger() {
      var trigger = $('.astrodj-post-navigation__previous');
      var trigger_position = trigger.offset().top - $(window).outerHeight();
      
      $(window).on('scroll', function(event) {
        if (trigger_position > $(window).scrollTop()) {
          return;
        }
        
        get_previous_post(trigger);
        
        $(this).off(event);
      });
    }

    if ($('.astrodj-post-navigation__load--prev').length) {
      previous_post_trigger();
    }

    function get_previous_post(trigger) {
      var previous_post_ID = trigger.attr('data-id');
      var json_url = rest_url + previous_post_ID + '?_embed=true' + api_args;
      
      $('.astrodj-post-navigation__load--prev .lds-ellipsis').show();

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
        
        $('.astrodj-post-navigation__load--prev .lds-ellipsis').hide();
        $('.astrodj-post-navigation__load--prev').replaceWith('<article class="astrodj-post-navigation__end"><p>Постов больше нет...</p></article>');
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
                          '<a href="' + object.link + '" data-id="' + object.id + '" data-blog_page="' + object.astrodj_blog_page + '">' +
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
              '<a href="' + object.link + '" data-id="' + object.id + '" data-slug="' + object.slug + '" data-blog_page="' + object.astrodj_blog_page + '" rel="bookmark">' + object.title.rendered + '</a>' + 
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
          var loader = '<div class="astrodj-post-navigation__load--prev">' +
                      '<div class="astrodj-post-navigation__previous" data-id="' + object.previous_post_ID + '"></div>' +
                      '<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>' +
                      '</div>';

          var previous_post_content = 
              '<article id="post-' + object.id + '" class="astrodj-post post">' +
              '<div class="post__content">' +
              entry_header() +
              get_featured_image() +
              excerpt() +
              '</div>' +
              '</article>' +
              loader;

          $('.astrodj-post-navigation__load--prev').replaceWith(previous_post_content);
          setInterval(
            function() {
              $('.astrodj-post-navigation__load--prev').prev('.astrodj-post').addClass('loaded');
            }, 150
          );

          previous_post_trigger();
        }

        build_post();
      }
    }

    /* ---------------------------------------------------------------------------
    *  Single Template Next Posts Load More from REST API
    * --------------------------------------------------------------------------- */
    function next_post_trigger() {
      var trigger = $('.astrodj-post-navigation__next');
      var header_height = $('#masthead').height();
      var menu_height = $('#site-navigation').height();
      var trigger_position = trigger.offset().top * 0.5;

      var lastScrollTop = 0,
      handler_next,
      direction;

      if ( handler_next != undefined ) {
        $(window).unbind('scroll', handler_next);
      }

      handler_next = function(e) {
        if (e.currentTarget.scrollY > lastScrollTop){
          direction = 'down';
        } else {
          direction = 'up';
        }
        lastScrollTop = e.currentTarget.scrollY;

        if ($(window).scrollTop() < trigger_position) {
          if ( direction == 'up' ) {
            get_next_post(trigger);
            $(window).unbind('scroll', handler_next);
          }
        }
      };
      $(window).bind('scroll', handler_next);
    }

    function single_header_init() {
      if (! $('.astrodj-post-navigation.next').length) {
        return;
      }

      // $(window).load(function() {
      //   // var init_scroll = $('.hentry').offset().top - 2*($('.site-header').height() - $('.main-navigation').height() + 4);
      //   // $(this).scrollTop(init_scroll);
      //   // var add_height = $('.astrodj-post-navigation.next').height();
      //   // $(this).scrollTop(add_height);
      //   // console.log(add_height);
      // });
    }

    // single_header_init();

    if ($('.astrodj-post-navigation.next').length) {
      next_post_trigger();
    }

    function get_next_post(trigger) {
      var next_post_ID = trigger.attr('data-id');
      var json_url = rest_url + next_post_ID + '?_embed=true';
      var header_height = $('#masthead').height();

      $('.astrodj-post-navigation__load--next .lds-ellipsis').show();

      $.ajax({
        dataType: 'json',
        url: json_url
      })

      .done(function(object) {
        // console.log(object);
        the_next_post(object);
        $(window).scrollTop(header_height);
        astrodj_lqip();
      })
      
      .fail(function() {
        // console.log('error');
        $('.astrodj-post-navigation__load--next .lds-ellipsis').hide();
        $('.astrodj-post-navigation__load--next').replaceWith('<article class="astrodj-post-navigation__end"><p>Новых постов больше нет...</p></article>');
      });

      function the_next_post(object) {
        var featured_image_ID = object.featured_media;
        var feat_image;

        function get_featured_image() {
          if ( featured_image_ID === 0 ) {
              feat_image = '';
          } else {
              var featured_object = object._embedded['wp:featuredmedia'][0];
              var img_width = featured_object.media_details.sizes.medium_large.width;
              var img_height = featured_object.media_details.sizes.medium_large.height;
              var thumbnail_class = object.meta.featured_image_format_meta === 'vertical' ? 'vertical' : 'blog-thumbnail';
              feat_image = '<div class="post-thumbnail-header">' +
                          '<a href="' + object.link + '" data-id="' + object.id + '" data-blog_page="' + object.astrodj_blog_page + '">' +
                          '<figure class="astrodj-lqip post-thumbnail blog-thumbnail ' + thumbnail_class + '" data-alt="' + featured_object.alt_text + '" ' +
                          'data-src="' + featured_object.media_details.sizes.medium_large.source_url + '" ' +
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
              '<a href="' + object.link + '" data-id="' + object.id + '" data-slug="' + object.slug + '" data-blog_page="' + object.astrodj_blog_page + '" rel="bookmark">' + object.title.rendered + '</a>' + 
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
          var loader = '<div class="astrodj-post-navigation__load--next">' +
                      '<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>' +
                      '<div class="astrodj-post-navigation__next" data-id="' + object.next_post_ID + '"></div>' +
                      '</div>';

          var previous_post_content = 
          loader +
          '<article id="post-' + object.id + '" class="astrodj-post post">' +
          '<div class="post__content">' +
          entry_header() +
          get_featured_image() +
          excerpt() +
          '</div>' +
          '</article>';

          $('.astrodj-post-navigation__load--next').replaceWith(previous_post_content);
          setInterval(
            function() {
              $('.astrodj-post-navigation__load--next').next('.astrodj-post').addClass('loaded');
            }, 150
          );

          next_post_trigger();
        }

        build_post();
      }
    }

    /* ---------------------------------------------------------------------------
    * LQIP - copy from main.js
    * --------------------------------------------------------------------------- */
    function astrodj_lqip() {
      // specifying selector for navigation
      var lazyImages = [].slice.call(document.querySelectorAll('.astrodj-post-navigation img.lazy'));

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
