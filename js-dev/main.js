(function( $ ) {
  "use strict";

  var debug = main_data.debug;
  /* ---------------------------------------------------------------------------
   * Placeholder Content
   * --------------------------------------------------------------------------- */
   $(window).on('load', function() {
    // DOM and js variant
    var placeholder__content = $('#placeholder__content'),
        placeholder__gallery = $('#placeholder__gallery');
        placeholder__content.addClass('action').remove();
        placeholder__gallery.remove();
    $('body').removeClass('placeholder__preloading');

    // History back with Local Storage on Blog pages
    function scrollToHash() {
      if (! $('.archive-view').length) {
        return;
      }

      var el = $(localStorage.getItem('hash')),
          height_limit = $(window).height();

      if (localStorage.getItem('hash') && el.length) {
        var el_offset = el.offset().top;
        // scroll to the anchor id
        if (el_offset > height_limit) {
          $('html, body').animate({
            scrollTop: el_offset
          }, 1);
        } else {
          $('html, body').animate({
            scrollTop: 0
          }, 1, function() {
            $('.main-navigation').removeClass('hide-menu');
          });
        }
        
        localStorage.removeItem('hash');
      } else {
        localStorage.removeItem('hash');
      }
    }

    // History back with Local Storage on Portfolio pages
    function scrollToHashPortfolio() {
      if (! $('body.archive-portfolio:not(.astrodj-full-portfolio)').length && ! $('body.archive-stock:not(.astrodj-full-portfolio)').length) {
        return;
      }

      var el = $(localStorage.getItem('portfolio-hash')),
          height_limit = $(window).height();

      if (localStorage.getItem('portfolio-hash') && el.length) {
        // scroll to the anchor id
        var el_offset = el.offset().top;

        if (el_offset > height_limit) {
          $('html, body').animate({
            scrollTop: el_offset
          }, 1);
        } else {
          $('html, body').animate({
            scrollTop: 0
          }, 1);
        }

        localStorage.removeItem('portfolio-hash');
      } else {
        localStorage.removeItem('portfolio-hash');
      }
    }

    scrollToHash();
    scrollToHashPortfolio();
  });

  $( document ).ready(function() {
    /* ---------------------------------------------------------------------------
    * Responsive menu
    * --------------------------------------------------------------------------- */
    //calculate translate content, because using wrapper of content windth
    var content_area = $('.content-area').width(),
        site_main = $('.site-main').width(),
        // wrapper = $('.wrapper').width(),
        widget_area = $('.widget-area').innerWidth(),
        full_w = $('body').width(),
        border_w = 4,
        bp__md = 768,
        content_w;

    // if (! $('.wrapper').length) {
    //   content_w = site_main;
    //   console.log('site_main');
    // } else {
    //   content_w = wrapper;
    //   console.log('entry_content');
    // }

		$('#menu-toggle').on('click', function() {
      $('body').addClass('opened-sidebar');
      if ($(document).width() > bp__md) {
        $('.site')
        .css({'transform': 'translateX(' + (widget_area - (content_area - site_main)/2) + 'px)'});
      }
		});

		$('.sidebar-overlay-layer').on('click', function() {
      $('body').removeClass('opened-sidebar');
      if ($(document).width() > bp__md) {
        $('.site').attr('style', '');
      }
    });

    /* ---------------------------------------------------------------------------
    * Menu animations plugin
    * --------------------------------------------------------------------------- */
    var header_height = $('.site-header').height(),
        menu = $('.main-navigation'),
        menu_height = menu.height(),
        single_menu = $('.single-post .main-navigation');

    (function() {
      function Menu($element, options) {

        var handler,
        defaults = {
          domObj : $element,
          className : 'small-menu',
          position : '100px',
          onIntellingenceMenu : function() {},
          onNormalMenu : function() {}
        },
        config = $.extend({}, defaults, options),
        coreFuns = {
          displayMenu : function() {
            if ( config.domObj.hasClass(config.className) ) {
              config.domObj.removeClass(config.className);
            }
          },
          hideMenu : function() {
            if ( !config.domObj.hasClass(config.className) ) {
              config.domObj.addClass(config.className);
            }
          }
        },
        publicFuns = {
          intelligent_menu : function() {

            var lastScrollTop = 0, 
            direction;

            if ( handler != undefined ) {
              $(window).unbind('scroll', handler);
            }

            handler = function(e) {
              if (e.currentTarget.scrollY > lastScrollTop){
                direction = 'down';
              } else {
                direction = 'up';
              }
              lastScrollTop = e.currentTarget.scrollY;

              // check is user scrolling to up or down?
              // archive pages or single post
              if (($(window).scrollTop() > header_height + 2*menu_height) && !single_menu.length) {
                if ( direction == 'up' ) {
                  coreFuns.displayMenu();
                } else {
                  coreFuns.hideMenu();
                }
              } else if (single_menu.length && ($(window).scrollTop() > $('.hentry').offset().top + menu_height)) {
                if ( direction == 'up' ) {
                  coreFuns.displayMenu();
                } else {
                  coreFuns.hideMenu();
                }
              }
              
            };
            $(window).on('scroll', handler);

            config.onNormalMenu();
          },
          fixed_menu : function() {
            if ( handler != undefined ) {
              $(window).unbind('scroll', handler);
            }

            handler = function(e) {
              // check have we display small menu or normal menu ?
              coreFuns.displayMenu();
            };

            $(window).on('scroll', handler);

            config.onNormalMenu();
          }
        };

        return publicFuns;
      }

      $.fn.menu = function( options ) {
        var $element = this.first();
        var menuFuns = new Menu( $element, options );
        return menuFuns;
      };

    })();

    // call to Menu plugin
    var menuFun = $('.main-navigation').menu({
      className : 'hide-menu',
      position : '100px'
    });

    window.menuFun = menuFun;
    //default intelligent_menu mod
    menuFun.intelligent_menu();
    
    /* ---------------------------------------------------------------------------
    * Fancybox plugin
    * --------------------------------------------------------------------------- */
    $('.fancybox').fancybox();

    /* ---------------------------------------------------------------------------
    * LQIP
    * --------------------------------------------------------------------------- */
    function astrodj_lqip() {
      var lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));

      if ('IntersectionObserver' in window) {
        var lazyImageObserver = new IntersectionObserver(function(entries, observer) {
          entries.forEach(function(entry) {
            if (entry.isIntersecting) {
              var lazyImage = entry.target;
              var bg = lazyImage.closest('.astrodj-lqip__wrap').previousElementSibling;
              var placeholder = bg.firstElementChild;
              var wrapper = lazyImage.closest('.astrodj-lqip');
              bg.classList.add('bg');
              setTimeout(function() {
                placeholder.classList.add('visible');
                lazyImage.alt = wrapper.hasAttribute('data-alt') ? wrapper.dataset.alt : '';
                lazyImage.src = wrapper.dataset.src;
                lazyImage.srcset = wrapper.hasAttribute('data-srcset') ? wrapper.dataset.srcset : '';
                lazyImage.sizes = wrapper.hasAttribute('data-sizes') ? wrapper.dataset.sizes : '';
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
        // if (! main_data.is_singular) {
        //   return;
        // }

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

    astrodj_lqip();

    // for vertical image in fullscreen portfolio page
    if ($('.fullpage-post-portfolio.vertical').length) {
      var img_width_vertical = $('img.placeholder').attr('width');

      $(this).find('img.placeholder').css({'max-width': img_width_vertical + 'px'});
    }
    
    /* ---------------------------------------------------------------------------
    * Header background image LQIP
    * --------------------------------------------------------------------------- */
    function astrodj_background_header_image() {
      // execute only on big screen
      if ($(document).width() < 576) {
        return;
      }

      var BgImage = document.getElementById('bgLazy');

      if ('IntersectionObserver' in window && (typeof(BgImage) != 'undefined' && BgImage != null)) {
        var BgImageObserver = new IntersectionObserver(function(entries, observer) {
          entries.forEach(function(entry) {
            if (entry.isIntersecting) {
              var BgImage = entry.target;
              var style = document.createAttribute('style');
              style.value = 'background-image: url(' + BgImage.dataset.src + ');';
              BgImage.setAttributeNode(style);
              BgImageObserver.unobserve(BgImage);
            }
          });
        });

        BgImageObserver.observe(BgImage);
      }
    }

    astrodj_background_header_image();

    /* ---------------------------------------------------------------------------
    * fancybox iframe for Portfolio
    * --------------------------------------------------------------------------- */
    $().fancybox({
      selector: '[data-portfolio]',
      toolbar: false,
      arrows: false,
      infobar: false,
      iframe: {
        // preload: true,
        css: {
          width : '90vw',
          maxHeight : '90vh',
        },
      },
      spinnerTpl: '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>',
      afterLoad : function( instance, current ) {
        if( $('.fancybox-content .ajax-loader').length == 0 ) {
          $('iframe').before('<div class="ajax-loader"></div><div class="iframe__overlay"></div>');
        }
        // console.log('before');
      },
      afterShow: function( instance, current ) {
        // console.log('after');
        $('.fancybox-content .ajax-loader').fadeOut(500);
        $('.fancybox-content .iframe__overlay').fadeOut(500);
      },
    });

    var post_nav = $('.astrodj-iframe-portfolio .nav-links a');

    post_nav.on('click', function(e) {
      $(parent.document).find('.ajax-loader').fadeIn(500);
      $(parent.document).find('.iframe__overlay').fadeIn(500);
    });

    /* ---------------------------------------------------------------------------
    *  Single Post Navigation History
    * --------------------------------------------------------------------------- */
    $(document).on('click', '.astrodj-post-navigation .entry-title a, .astrodj-post-navigation .post-thumbnail-header a', function() {
      var that = $(this),
          id = that.data('id'),
          page = that.data('blog_page'),
          site_url = main_data.site_url,
          link;

      var post_hash = '#post-' + id;

      if (page == 1) {
        link = site_url + 'blog/';
      } else {
        link = site_url + 'blog/page/' + page + '/';
      }

      history.replaceState(null, null, link);
      // if (localStorage.getItem('link')) {
      //   localStorage.removeItem('link');
      // }
      // localStorage.setItem('link', link);

      // window.location.hash = post_hash;
      localStorage.setItem('hash', post_hash);
    });

    // $(window).on('popstate', function() {
    //   if (localStorage.getItem('link')) {
    //     location = localStorage.getItem('link');
    //     localStorage.removeItem('link');
    //   }
    // }, false);

    /* ---------------------------------------------------------------------------
    *  Portfolio Pages Post Navigation History
    * --------------------------------------------------------------------------- */
    $(document).on(
      'click',
      'body.astrodj-full-portfolio .astrodj-portfolio a, body.astrodj-full-portfolio .nav-links a, body.archive-portfolio .astrodj-portfolio a, body.archive-stock .astrodj-portfolio a',
      function() {
        var that = $(this),
            id = that.data('hash-id'),
            post_hash = '#post-' + id;

        localStorage.setItem('portfolio-hash', post_hash);
      }
    );

    /* ---------------------------------------------------------------------------
    * Dropdown Menu with plugin Popper.js.
    * --------------------------------------------------------------------------- */
    var dropdown_button = document.querySelector('.dropdown-button'),
        dropdown_menu = document.querySelector('.dropdown-menu__wrapper'),
        popperInstance = null;

    function create() {
      popperInstance = Popper.createPopper(dropdown_button, dropdown_menu, {
        modifiers: [
          {
            name: 'offset',
            options: {
              offset: [0, 8],
            },
          },
        ],
      });
    }

    function destroy() {
      if (popperInstance) {
        popperInstance.destroy();
        popperInstance = null;
      }
    }
        

    $('.dropdown-button').on('click', function(e) {
      e.stopPropagation();
      $('.dropdown-menu__wrapper').toggleClass('visible');
      create();
    });

    $(window).on('click', function() {
      $('.dropdown-menu__wrapper').removeClass('visible');
      destroy();
    });

    /* ---------------------------------------------------------------------------
    * Contact Form Validate
    * --------------------------------------------------------------------------- */
    // form submit
    $('#contact-form').on('submit', function(e) {
      e.preventDefault();

      var form = $(this),
          name = form.find('#name').val(),
          email = form.find('#email').val(),
          message = form.find('#message').val(),
          data_container = form.parent().prev('.form__data'),
          ajax_url = main_data.ajax_url;

      $('#formHelp').html('Уходит...');
      form.find('.form-footer').html('');
      
      $.ajax({
        type: 'post',
        url: ajax_url,
        data: {
          name: name,
          email: email,
          message: message,
          action: 'astrodj_contact_form'
        },
        error: function(response) {
          if (debug) {
            console.log(response);
          }
        },
        success: function(response) {
          if (response !== 0) {
            data_container.show().prepend(response);
            $('html, body').animate({
              scrollTop: data_container.offset().top + data_container.height() / 2 - $(window).height() / 2
            }, 300, 'swing');
          }
        }
      }).done(function() {
        form.find('.form-control').val('').removeClass('success');
        form.trigger('reset');
        form.find('#formHelp').addClass('hidden').removeClass('success').html('Все поля обязательны для заполнения');
      });
    });

    // form validate
    $('#contact-form :input[required]').on('keyup blur', function() {
      var small = $(this).next();
      var label = $(this).prev().text();
      var pattern_attr = $(this).attr('pattern');
      var pattern;
      var placeholder = $(this).attr('placeholder');
      var isValid;
      
      if (typeof pattern_attr !== typeof undefined && pattern_attr !== false) {
        pattern = $(this).attr('pattern');
      } else {
        pattern = $(this).attr('data-pattern');
      }
      
      $('#formHelp').addClass('hidden');

      small.removeClass('error');
      $(this).removeClass('error').addClass('success');

      isValid = $(this).val().search(pattern) >= 0;

      if (!isValid) {
        $(this).removeClass('success').addClass('error');
        small.addClass('error').html('Введите правильный формат: ' + placeholder);
      }

      if ($(this).val() === "") {
        $(this).removeClass('success').addClass('error');
        small.addClass('error').html('Поле "' + label + '" обязательно');
      }

      // display submit
      var form = document.contactForm;
      validate(form);
    });

    function validate(form) {
      var fail;

      fail = validateName(form.name.value);
      fail += validateEmail(form.email.value);
      fail += validateText(form.message.value);

      if (fail === "") {
        $('#formHelp').removeClass('hidden').addClass('success').html('Можно отправлять!');
        if (! $('#contact-form button').length) {
          $('#contact-form').find('.form-footer').html('<button type="submit" class="btn btn-submit" name="submit" value="submit">Отправить</button>');
        }
      } else {
        $('#contact-form').find('.form-footer').html('');
      }
    }

    function validateName(field) {
      if (field === "") return false;
      var reg = /^[А-Яа-яA-Za-z ]+\s?[А-Яа-яA-Za-z ]*$/;
      if (!reg.test(field.trim())) {
        return false;
      }
      return "";
    }

    function validateEmail(field) {
      if (field === "") return false;
      var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      if (!reg.test(field.trim())) {
        return false;
      }
      return "";
    }

    function validateText(field) {
      if (field === "") return false;
      // /^([A-Za-z0-9]+\.[A-Za-z0-9]+(\r)?(\n)?)+$/
      // /^\s*[А-Яа-яA-Za-z0-9]+\.*/;
      var reg = /^\s*[А-Яа-яA-Za-z0-9\.ёЁ]{3,}\.*/;
      if (!reg.test(field.trim())) {
        return false;
      }
      return "";
    }
    
    /* ---------------------------------------------------------------------------
    * Page Navigation by Subpages.
    * --------------------------------------------------------------------------- */
    $(document).on('click', '.subpages-navigation a', function(e) {
      e.preventDefault();

      var link = $(this).attr('href'),
          title_page = $(this).text(),
          li = $(this).parent(),
          site_name = main_data.site_name;

      sessionStorage.setItem('subpage_prev_title', document.title);

      history.pushState(null, null, link);

      document.title = title_page + ' — ' + site_name;

      li.addClass('current-menu-item').siblings('.current-menu-item').removeClass('current-menu-item');

      subpages_navigation_ajax(link);
    });

    // History back
    window.addEventListener('popstate', function (event) {
      // Title
      document.title = sessionStorage.getItem('subpage_prev_title');
      sessionStorage.setItem('subpage_prev_title', document.title);

      // Active Link
      $('.subpages-navigation a').each(function() {
        if (location.pathname === $(this)[0].pathname) {
          $(this).parent().addClass('current-menu-item').siblings('.current-menu-item').removeClass('current-menu-item');
        }
      });

      // Load Conten Subpage only on page
      if (main_data.is_singular) {
        subpages_navigation_ajax(location.href);
      }
    }, false);

    // Content Data
    function subpages_navigation_ajax(link) {
      var ajax_url = main_data.ajax_url,
          content = $('.subpage-content-wrapper');

      $.ajax({
        url: ajax_url,
        type: 'post',
        data: {
          link: link,
          action: 'astrodj_subpages_navigation'
        },
        beforeSend: function() {
          content.animate({opacity: 0}, 300);
        },
        success: function(response) {
          content.html(response).animate({opacity: 1}, 300);

          astrodj_lqip();

          $('.fancybox').fancybox();

          if (debug) {
            console.log('Ajax Subpages Navigation');
          }
        },
        error: function(jqxhr, status, errorMsg) {
          if (debug) {
            console.log('error Subpages Navigation: ' + errorMsg);
          }
        }
      });
    }

    /* ---------------------------------------------------------------------------
    * Next function...
    * --------------------------------------------------------------------------- */

  });

}(jQuery));