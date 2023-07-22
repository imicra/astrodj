(function( $ ) {
  "use strict";

  $( document ).ready(function() {
    var debug = main_data.debug;
    var ajax_url = archive_ajax.ajax_url,
        is_home = archive_ajax.is_home,
        // last_page = archive_ajax.last_page,
        site_url = archive_ajax.site_url,
        site_name = archive_ajax.site_name,
        site_description = archive_ajax.site_description,
        is_archive_view = $('body.archive-view').length,
        placeholder_archive = '<div id="placeholder__content">' +
                              '<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>' +
                              '<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>' +
                              '<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>' +
                              '<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>' +
                              '</div>',
        svg_icon_image = '<svg viewBox="0 -36 512 511">' +
                        '<path d="m231.898438 198.617188c28.203124 0 51.152343-22.945313 51.152343-51.148438 0-28.207031-22.949219-51.152344-51.152343-51.152344-28.203126 0-51.148438 22.945313-51.148438 51.152344 0 28.203125 22.945312 51.148438 51.148438 51.148438zm0-72.300782c11.664062 0 21.152343 9.488282 21.152343 21.152344 0 11.660156-9.488281 21.148438-21.152343 21.148438-11.660157 0-21.148438-9.488282-21.148438-21.148438 0-11.664062 9.488281-21.152344 21.148438-21.152344zm0 0"/><path d="m493.304688.5h-474.609376c-10.308593 0-18.695312 8.386719-18.695312 18.695312v401.726563c0 10.308594 8.386719 18.695313 18.695312 18.695313h474.609376c10.308593 0 18.695312-8.386719 18.695312-18.695313v-401.726563c0-10.308593-8.386719-18.695312-18.695312-18.695312zm-11.304688 30v237.40625l-94.351562-94.355469c-6.152344-6.140625-16.15625-6.136719-22.304688.011719l-133.441406 133.441406-85.238282-85.234375c-2.980468-2.984375-6.945312-4.628906-11.164062-4.628906-4.214844 0-8.175781 1.640625-11.15625 4.621094l-94.34375 94.34375v-285.605469zm-452 379.117188v-51.085938l105.5-105.5 85.234375 85.234375c2.984375 2.984375 6.949219 4.632813 11.167969 4.632813 4.210937 0 8.175781-1.644532 11.152344-4.625l133.445312-133.445313 105.503906 105.503906v99.285157zm0 0"/>' +
                        '</svg>',
        placeholder__gallery = '<div id="placeholder__gallery">' +
                              '<div class="placeholder__gallery-item">' + svg_icon_image + '</div>' +
                              '<div class="placeholder__gallery-item">' + svg_icon_image + '</div>' +
                              '<div class="placeholder__gallery-item">' + svg_icon_image + '</div>' +
                              '<div class="placeholder__gallery-item">' + svg_icon_image + '</div>' +
                              '<div class="placeholder__gallery-item">' + svg_icon_image + '</div>' +
                              '</div>';

    /* ---------------------------------------------------------------------------
    * History for Pagination and Links to archives
    * --------------------------------------------------------------------------- */
    window.addEventListener('popstate', function(event) {
      if (debug) {
        console.log('Popstate State: ' + JSON.stringify(event.state));
      }
      document.title = event.state.page_title;
      ajax_pagination(event.state.data_paged, event.state.data_archive);
    }, false);

    /* ---------------------------------------------------------------------------
    * History Manipulation.
    * --------------------------------------------------------------------------- */
    function history_manipulation() {
      var page_title = document.title,
          data_paged;

      var last_page = find_page_number($('.nav-links .page-numbers:last-child').clone());
      var archive = $('.page-limit').first().data('page');
      if (! $('.load-more__btn').length && ! last_page) {
        data_paged = 1;
      } else {
        data_paged = $('.load-more__btn').length ? $('.load-more__btn').data('page') : last_page;
      }
      // for develop: http://imicra.loc/ replace with http://192.168.18.1:8080/ (not work on arrows buttons in pagination)
      var link_history = location.href.replace(location.origin + '/', site_url);
      history.replaceState({data_paged: data_paged, data_archive: archive, page_title: page_title}, '', link_history);
      history.pushState({data_paged: data_paged, data_archive: archive, page_title: page_title}, '', null);

      if (debug) {
        console.log('Current State: ' + JSON.stringify(history.state));
      }
    }

    /* ---------------------------------------------------------------------------
    * Load More Button in Archive Pages
    * --------------------------------------------------------------------------- */
    $(document).on('click', '.load-more__btn', function() {
      var that = $(this);
      var page = that.attr('data-page');
      var archive = that.attr('data-archive');
      var wrapper = $('.pagination-wrapper');
      var loader = wrapper.find('.loader');

      if(typeof archive === 'undefined') {
        archive = 0;
      }

      that.hide();
      loader.show();

      $.ajax({
        url: ajax_url,
        type: 'post',
        data: {
          page: page,
          archive: archive,
          load_more_btn: true,
          action: 'astrodj_archive_pagination'
        },
        success: function(response) {
          if(response == 0) {
            $('.load-more').before('<div><p>Больше нет постов для загрузки...</p></div>');
          } else {
            wrapper.replaceWith(response);

            var new_content = $('.pagination-wrapper').prev('.page-limit').find('article').first();

            if (is_archive_view) {
              $('html, body').animate({
                scrollTop: new_content.offset().top
              }, 500, 'swing');
            }
            
            astrodj_lqip();
            $('.fancybox').fancybox();
            exif_init();

            if (debug) {
              console.log('Ajax Load More Button');
            }
          }
        }
      });
    });

    // scroll functions for update url.
    // scroll optimization method.
    var didScroll;
    
    $(window).on('scroll', function(event) {
      didScroll = true;
    });
    
    setInterval(function() {
      if (didScroll) {
        update_url();
        didScroll = false;
      }
    }, 250);

    function update_url() {
      var scroll = $(window).scrollTop();
      var last_scroll = 0;
      // console.log('update_url');

      if(Math.abs(scroll - last_scroll) > $(window).height()*0.1) {
        last_scroll = scroll;

        $('.page-limit').each(function(index) {
          if(isVisible($(this))) {
            history.replaceState(null, null, $(this).data('page'));
            return(false);
          }
        });
      }
    }

    function isVisible(element) {
      var scroll_pos = $(window).scrollTop();
      var window_height = $(window).height();
      var el_top = $(element).offset().top;
      var el_height = $(element).height();
      var el_bottom = el_top + el_height;

      return ( (el_bottom - el_height * 0.25 > scroll_pos) && (el_top < (scroll_pos + 0.5 * window_height)) );
    }

    /* ---------------------------------------------------------------------------
    *  Ajax pagination.
    * --------------------------------------------------------------------------- */
    $(document).on('click', '.nav-links a', function(event) {
      event.preventDefault();

      var paged = $(this).data('page');
      var page_title = document.title;

      // History manipulations
      var last_page = find_page_number($('.nav-links .page-numbers:last-child').clone());
      // this page - it's a previouse page for history
      var data_paged = $('.load-more__btn').length ? $('.load-more__btn').data('page') : last_page;
      // console.log('previouse page: ' + data_paged);
      // previouse link for history
      var link_history;
      if (data_paged !== 1) {
        // for archives pages and search results
        link_history = $('.page-limit').last().data('page').replace(/\d/, paged);
      } else {
        if ($('.page-limit').last().data('page').indexOf('?s=') !== -1) {
          // for search results
          link_history = '/page/' + paged + $('.page-limit').last().data('page');
        } else {
          // for archives pages
          link_history = $('.page-limit').last().data('page') + 'page/' + paged + '/';
        }
      }
      // console.log('link: ' + link_history);
      
      // for develop: http://imicra.loc/ replace with http://192.168.18.1:8080/ (not work on arrows)
      // link_history = event.target.href.replace(event.target.origin, site_url);

      history.replaceState({data_paged: data_paged, page_title: page_title}, '', link_history);
      history.pushState({data_paged: data_paged, page_title: page_title}, '', null);

      if (debug) {
        console.log('Current State: ' + JSON.stringify(history.state));
      }

      // saving Widget Filter initazlize text results when page refresh
      if (! is_archive_view && $('header#filter-init').length) {
        $('#results').append($('header#filter-init').html());
      }

      ajax_pagination(paged);
    });

    function ajax_pagination(paged, archive) {
      var archive;
      
      archive = archive ? archive : $('.page-limit').first().data('page');

      $.ajax({
        url: ajax_url,
        type: 'post',
        data: {
          paged: paged,
          archive: archive, // need add data $_SERVER["REQUEST_URI"] to paginate_links
          action: 'astrodj_archive_pagination'
        },
        beforeSend: function() {
          $('body').addClass('placeholder__preloading');

          if (is_archive_view) {
            $('#main').html(placeholder_archive);
          } else {
            $('#main').html(placeholder__gallery);
          }
          
          $('html, body').animate({
            scrollTop: 0
          }, 100, 'swing', function() {
            $('.main-navigation').removeClass('hide-menu');
          });
        },
        success: function(response) {
          $('body').removeClass('placeholder__preloading');
          $('#main').html(response);

          history.replaceState(null, '', $('.page-limit').data('page'));

          astrodj_lqip();
          $('.fancybox').fancybox();
          exif_init();

          if (debug) {
            console.log('Ajax pagination');
          }
        },
        error: function(jqxhr, status, errorMsg) {
          if (debug) {
            console.log('error Pagination: ' + errorMsg);
          }
        }
      });
    }

    /* ---------------------------------------------------------------------------
    *  Archive Pages Links Ajax Loading.
    * --------------------------------------------------------------------------- */
    $(document).on('click', '.archive-view:not(.error404) .cat-links a, .archive-view:not(.error404) .widget_categories a, .archive-view:not(.error404) .widget_tag_cloud a', function(e) {
      e.preventDefault();

      var link = $(this).attr('href');
      var title_archive = $(this).text();

      // History manipulations
      history_manipulation();

      document.title = title_archive + ' | ' + site_description + ' — ' + site_name;

      // Disable Sidebar opening effect
      if ($('body').hasClass('opened-sidebar')) {
        $('body').removeClass('opened-sidebar');
      }

      $('.site').attr('style', '');

      // to empty search field if previously get search result
      $('.header-search .search-form, .widget_search .search-form').find('input').val('');

      archive_link_ajax(link);
    });

    function archive_link_ajax(link) {
      $.ajax({
        url: ajax_url,
        type: 'post',
        data: {
          link: link,
          action: 'astrodj_archive_links'
        },
        beforeSend: function() {
          $('body').addClass('placeholder__preloading');
          $('#main').html(placeholder_archive);

          $('html, body').animate({
            scrollTop: 0
          }, 100, 'swing', function() {
            $('.main-navigation').removeClass('hide-menu');
          });
        },
        success: function(response) {
          $('body').removeClass('placeholder__preloading');
          $('#main').html(response);
          history.replaceState(null, null, $('.page-limit').data('page'));
          astrodj_lqip();

          if (debug) {
            console.log('Ajax Links');
          }
        },
        error: function(jqxhr, status, errorMsg) {
          if (debug) {
            console.log('error Links: ' + errorMsg);
          }
        }
      });
    }

    /* ---------------------------------------------------------------------------
    * Search Form Ajax Submit
    * --------------------------------------------------------------------------- */
    $(document).on('submit', '.archive-view:not(.error404) .search-form', function(e) {
      e.preventDefault();

      var form = $(this),
          value = form.find('input').val();

      // History manipulations
      history_manipulation();

      document.title = 'Результаты поиска «' + value + '» — ' + site_name;

      // Disable Sidebar opening effect
      if ($('body').hasClass('opened-sidebar')) {
        $('body').removeClass('opened-sidebar');
      }

      $('.site').attr('style', '');

      $.ajax({
        type: 'post',
        url: ajax_url,
        data: {
          value: value,
          action: 'astrodj_search_form'
        },
        beforeSend: function() {
          $('body').addClass('placeholder__preloading');
          $('#main').html(placeholder_archive);
          $('.header-search .search-form, .widget_search .search-form').find('input').val(value);
          
          $('html, body').animate({
            scrollTop: 0
          }, 100, 'swing', function() {
            $('.main-navigation').removeClass('hide-menu');
          });
        },
        success: function(response) {
          $('body').removeClass('placeholder__preloading');
          $('#main').html(response);
          history.replaceState(null, '', $('.page-limit').data('page'));
          astrodj_lqip();

          if (debug) {
            console.log('Search Form Ajax Results');
          }
        },
        error: function(jqxhr, status, errorMsg) {
          if (debug) {
            console.log('error Search Form: ' + errorMsg);
          }
        }
      }).done(function() {
        form.find('input').blur();
      });
    });

    /* ---------------------------------------------------------------------------
    * Widget Photo Filter
    * --------------------------------------------------------------------------- */
    // Filter Panel Showing
    var widget = $('#filter-widget-area'),
        count = $('.widget-filter__count #count').text();

    $(window).on('click', function() {
      widget.removeClass('opened');
      $('body').removeClass('opened-filter');
      $('.widget-filter__select .options').fadeOut(150);
    });

    $('#menu-filter').on('click', function(e) {
      e.stopPropagation();

      widget.toggleClass('opened');
      $('body').addClass('opened-filter');
    });

    widget.on('click', function(e) {
      e.stopPropagation();
      $('.widget-filter__select .options').fadeOut(150);
    });

    // Button Close
    $('.widget-filter__btn.close').on('click', function() {
      widget.removeClass('opened');
      $('body').removeClass('opened-filter');
    });

    // Select element imitation
    var init_select = $('.widget-filter__select .selected').find('span').text(),
        show_btns_select,
        show_btns_checkbox,
        icon_angle_down = '<svg id="icon-angle-down" viewBox="0 0 21 32">' +
                          '<path class="path1" d="M19.196 13.143q0 0.232-0.179 0.411l-8.321 8.321q-0.179 0.179-0.411 0.179t-0.411-0.179l-8.321-8.321q-0.179-0.179-0.179-0.411t0.179-0.411l0.893-0.893q0.179-0.179 0.411-0.179t0.411 0.179l7.018 7.018 7.018-7.018q0.179-0.179 0.411-0.179t0.411 0.179l0.893 0.893q0.179 0.179 0.179 0.411z"></path>' +
                          '</svg>';

    $('.widget-filter__select').on('click', '.selected', function(e) {
      e.stopPropagation();
      $(this).next('.options').fadeToggle(150);
    });

    // select option
    $('.widget-filter__select .options').on('click', '.options-item', function() {
      var text = $(this).text(),
          order = $(this).data('order'),
          icon_close = '<svg id="icon-close" viewBox="0 0 25 32">' +
                        '<path class="path1" d="M23.179 23.607q0 0.714-0.5 1.214l-2.429 2.429q-0.5 0.5-1.214 0.5t-1.214-0.5l-5.25-5.25-5.25 5.25q-0.5 0.5-1.214 0.5t-1.214-0.5l-2.429-2.429q-0.5-0.5-0.5-1.214t0.5-1.214l5.25-5.25-5.25-5.25q-0.5-0.5-0.5-1.214t0.5-1.214l2.429-2.429q0.5-0.5 1.214-0.5t1.214 0.5l5.25 5.25 5.25-5.25q0.5-0.5 1.214-0.5t1.214 0.5l2.429 2.429q0.5 0.5 0.5 1.214t-0.5 1.214l-5.25 5.25 5.25 5.25q0.5 0.5 0.5 1.214z"></path>' +
                        '</svg>';

      $(this).parent().fadeOut(150);
      $(this).siblings('.select').removeClass('select');
      $(this).addClass('select');
      $(this).parent().prev().find('span').text(text).attr('data-order', order);
      $(this).parent().prev().find('.icon-wrap').addClass('close');
      $(this).parent().prev().find('svg').attr('class', 'icon icon-close').html(icon_close);

      show_btns_select = true;
      check_show_btns();
    });

    // reset
    $('.widget-filter__select .selected').on('click', '.icon-wrap.close', function(e) {
      e.stopPropagation();

      $(this).removeClass('close');
      $(this).parent().next().fadeOut(150);
      $(this).find('svg').attr('class', 'icon icon-angle-down').html(icon_angle_down);
      $(this).prev('span').attr('data-order', '').text(init_select);
      $(this).parent().next().find('.options-item').removeClass('select');

      show_btns_select = false;
      check_show_btns();
    });

    // Checkbox element initation
    var cat_IDs = [];

    $('.widget-filter__grid--item').on('click', 'img, span', function(e) {
      $(this).parent().toggleClass('active');

      if ($(this).parent().hasClass('active')) {
        cat_IDs.push($(this).parent().attr('data-id'));

        if (debug) {
          console.log(cat_IDs);
        }
      } else {
        for (var i = 0; i < cat_IDs.length; i++) {
          if ($(this).parent().attr('data-id') == cat_IDs[i]) {
            cat_IDs[i] = '';
          }
        }
        cat_IDs = jQuery.grep(cat_IDs, function(value) {
          return value != '';
        });

        if (debug) {
          console.log(cat_IDs);
        }
      }

      if (cat_IDs.length != 0) {
        show_btns_checkbox = true;
      } else {
        show_btns_checkbox = false;
      }

      check_show_btns();

      // ajax request for total post count
      var cpt = $('.widget-filter__wrapper').data('cpt'),
          preloader = '<svg viewBox="0 0 32 32">' + 
                      '<path d="M16,5.2c5.9,0,10.8,4.8,10.8,10.8S21.9,26.8,16,26.8S5.2,21.9,5.2,16H0c0,8.8,7.2,16,16,16s16-7.2,16-16S24.8,0,16,0V5.2z"/>' +
                      '</svg>';

      $.ajax({
        url: ajax_url,
        type: 'post',
        data: {
          cpt: cpt,
          cat_IDs: cat_IDs,
          action: 'astrodj_widget_filter_count'
        },
        beforeSend: function() {
          $('.widget-filter__count #count').html(preloader);
        },
        success: function(response) {
          $('.widget-filter__count #count').html(response);
        },
        error: function(jqxhr, status, errorMsg) {
          if (debug) {
            console.log('error Widget Filter Count: ' + errorMsg);
          }
        }
      });
    });

    // Show Buttons
    function check_show_btns() {
      if (show_btns_checkbox || show_btns_select) {
        $('.widget-filter__btn:not(.close)').show();
      } else {
        $('.widget-filter__btn:not(.close)').hide();
      }
    }

    // filter saved state on page reload with data stored in local storage
    // for checkboxes
    var store_cat_IDs = [];

    if (localStorage.getItem('filter-check_cat') && '' !== location.search) {
      store_cat_IDs = localStorage.getItem('filter-check_cat').split(',');
      show_btns_checkbox = true;
      check_show_btns();
      cat_IDs = store_cat_IDs.concat(cat_IDs);

      for (var i = 0; i < store_cat_IDs.length; i++) {
        $('.widget-filter__grid--item').each(function(el) {
          if ($(this).attr('data-id') == store_cat_IDs[i]) {
            $(this).addClass('active');
          }
        });
      }
    }

    // for select
    if (localStorage.getItem('filter-check_order') && '' !== location.search) {
      $('.widget-filter__select .options .options-item[data-order=' + localStorage.getItem('filter-check_order') + ']').trigger('click');
    }

    // for count
    if (localStorage.getItem('filter-check_count') && '' !== location.search) {
      $('.widget-filter__count #count').html(localStorage.getItem('filter-check_count'));
    }

    // Button Reset
    $('.widget-filter__btn.reset').on('click', function() {
      // reset checkbox
      $('.widget-filter__grid--item').removeClass('active');
      cat_IDs = [];

      if (debug) {
        console.log(cat_IDs);
      }

      // reset select
      $('.widget-filter__select .icon-wrap').removeClass('close');
      $('.widget-filter__select .icon-close').attr('class', 'icon icon-angle-down').html(icon_angle_down);
      $('.widget-filter__select .selected span').attr('data-order', '').text(init_select);
      $('.widget-filter__select .options-item').removeClass('select');

      // hide buttons
      $('.widget-filter__btn:not(.close)').hide();

      // show total count of posts
      $('.widget-filter__count #count').html(count);

      // remove local storage data with filter checks
      // if ('' === location.search) {
      //   localStorage.removeItem('filter-check_order');
      //   localStorage.removeItem('filter-check_cat');
      // }
    });

    // Button Success and Ajax send
    $('.widget-filter__btn.success').on('click', function() {
      widget.removeClass('opened');
      $('body').removeClass('opened-filter');
      
      // collect data
      var cpt = $('.widget-filter__wrapper').data('cpt');

      if (debug) {
        console.log(cpt);
      }

      if (debug) {
        console.log(cat_IDs);
      }

      var order = $('.widget-filter__select .selected').find('span').attr('data-order');

      if (debug) {
        console.log(order);
      }

      // set local storage data with filter checks for reload page case
      localStorage.setItem('filter-check_order', order);
      localStorage.setItem('filter-check_cat', cat_IDs);
      localStorage.setItem('filter-check_count', $('.widget-filter__count #count').text());

      // create location
      var query_cat = cat_IDs.length > 0 ? 'terms=' + cat_IDs.join().replaceAll(',', '%2C') : '';
      var query_order = order !== '' ? 'order=' + order : '';
      var query_array = [query_cat, query_order].filter(function (element) {
        return element !== '';
      });

      var filter_query = '/?' + query_array.join('&');
      var path_to_replace = location.pathname.split('/').splice(1, 1).toString() + filter_query;
      var link_history = location.href.replace(location.pathname + location.search, '/' + path_to_replace);

      // history manipulation
      var page_title = document.title;
      var last_page = find_page_number($('.nav-links .page-numbers:last-child').clone());
      // this page - it's a previouse page for history
      if (! $('.load-more__btn').length && ! last_page) {
        data_paged = 1;
      } else {
        var data_paged = $('.load-more__btn').length ? $('.load-more__btn').data('page') : last_page;
      }
      
      var archive = '/' + location.pathname.split('/').splice(1, 1).toString() + '/';

      history.replaceState({data_paged: data_paged, data_archive: archive, page_title: page_title}, null, link_history);
      history.pushState({data_paged: data_paged, data_archive: archive, page_title: page_title}, null, null);

      if (debug) {
        console.log('Widget Filter: ' + JSON.stringify(history.state));
      }

      // Create Filter Results block
      var icon_equalizer = '<svg class="icon" viewBox="0 0 477.867 477.867">' + 
                          '<g><g><path d="M460.8,221.867H185.31c-9.255-36.364-46.237-58.34-82.602-49.085c-24.116,6.138-42.947,24.969-49.085,49.085H17.067' +
                          'C7.641,221.867,0,229.508,0,238.934S7.641,256,17.067,256h36.557c9.255,36.364,46.237,58.34,82.602,49.085' +
                          'c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,221.867,460.8,221.867z' +
                          'M119.467,273.067c-18.851,0-34.133-15.282-34.133-34.133c0-18.851,15.282-34.133,34.133-34.133s34.133,15.282,34.133,34.133' +
                          'C153.6,257.785,138.318,273.067,119.467,273.067z"/></g></g>' +
                          '<g><g><path d="M460.8,51.2h-53.623c-9.255-36.364-46.237-58.34-82.602-49.085C300.459,8.253,281.628,27.084,275.49,51.2H17.067' +
                          'C7.641,51.2,0,58.841,0,68.267s7.641,17.067,17.067,17.067H275.49c9.255,36.364,46.237,58.34,82.602,49.085' +
                          'c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,51.2,460.8,51.2z M341.334,102.4' +
                          'c-18.851,0-34.133-15.282-34.133-34.133s15.282-34.133,34.133-34.133s34.133,15.282,34.133,34.133S360.185,102.4,341.334,102.4z"/>' +
                          '</g></g>' +
                          '<g><g><path d="M460.8,392.534h-87.757c-9.255-36.364-46.237-58.34-82.602-49.085c-24.116,6.138-42.947,24.969-49.085,49.085H17.067' +
                          'C7.641,392.534,0,400.175,0,409.6s7.641,17.067,17.067,17.067h224.29c9.255,36.364,46.237,58.34,82.602,49.085' +
                          'c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,392.534,460.8,392.534z' +
                          'M307.2,443.734c-18.851,0-34.133-15.282-34.133-34.133s15.282-34.133,34.133-34.133c18.851,0,34.133,15.282,34.133,34.133' +
                          'S326.052,443.734,307.2,443.734z"/></g></g>' +
                          '</svg>',
      close_icon = '<svg class="icon" viewBox="0 0 25 32">' +
                  '<path class="path1" d="M23.179 23.607q0 0.714-0.5 1.214l-2.429 2.429q-0.5 0.5-1.214 0.5t-1.214-0.5l-5.25-5.25-5.25 5.25q-0.5 0.5-1.214 0.5t-1.214-0.5l-2.429-2.429q-0.5-0.5-0.5-1.214t0.5-1.214l5.25-5.25-5.25-5.25q-0.5-0.5-0.5-1.214t0.5-1.214l2.429-2.429q0.5-0.5 1.214-0.5t1.214 0.5l5.25 5.25 5.25-5.25q0.5-0.5 1.214-0.5t1.214 0.5l2.429 2.429q0.5 0.5 0.5 1.214t-0.5 1.214l-5.25 5.25 5.25 5.25q0.5 0.5 0.5 1.214z"></path>' +
                  '</svg>';

      var output_count = cat_IDs.length > 0 ? '<span class="count"><span class="block round">' + $('.widget-filter__count #count').text() + '</span> Фото</span>' : '';

      if (order !== '') {
        var results_order = order === 'DESC' ? 'Сначала новые' : 'Сначала ранние';
        var output_order = '<span class="data">Дата съемки: <span class="block">' + results_order + '</span></span>';
      } else {
        output_order = '';
      }

      var cat_names = [];

      if (cat_IDs.length > 0) {
        for (var i = 0; i < cat_IDs.length; i++) {
          $('.widget-filter__grid--item').each(function() {
            if ($(this).attr('data-id') == cat_IDs[i]) {
              cat_names.push($(this).find('span').text());
            }
          });
        }
      }

      var output_names = cat_names.join('</span>, <span class="block">');

      if (output_names !== '') {
        var output_cat = '<span class="cat">Категории: <span class="block">'+ output_names + '</span></span>';
      } else {
        output_cat = '';
      }
      
      var results = '<div class="widget-filter__page--inner">' +
                    '<span class="title">' + icon_equalizer + '</span>' +
                    output_order + output_cat + output_count +
                    '<div class="reset">Сбросить ' + close_icon + '</div>' +
                    '</div>';

      $('#filter-init').remove();
      $('#results').html(results);

      $.ajax({
        url: ajax_url,
        type: 'post',
        data: {
          cpt: cpt,
          cat_IDs: cat_IDs,
          order: order,
          action: 'astrodj_widget_filter'
        },
        beforeSend: function() {
          $('body').addClass('placeholder__preloading');
          $('#main').html(placeholder__gallery);

          $('html, body').animate({
            scrollTop: 0
          }, 100, 'swing', function() {
            $('.main-navigation').removeClass('hide-menu');
          });
        },
        success: function(response) {
          $('body').removeClass('placeholder__preloading');
          $('#main').html(response);
          astrodj_lqip();

          if (debug) {
            console.log('Ajax Widget Filter');
          }
        },
        error: function(jqxhr, status, errorMsg) {
          if (debug) {
            console.log('error Widget Filter: ' + errorMsg);
          }
        }
      });
    });

    // reset filter results
    $('#results, #filter-init').on('click', '.reset', function() {
      $('#filter-init').remove();
      $('#results').html('');

      // remove local storage data with filter checks
      localStorage.removeItem('filter-check_order');
      localStorage.removeItem('filter-check_cat');
      localStorage.removeItem('filter-check_count');

      var cpt = $('.widget-filter__wrapper').data('cpt'),
          archive,
          pathname_init = location.pathname.split('/').splice(1, 1).toString(),
          link = location.href.replace(location.pathname + location.search, '/' + pathname_init + '/');

      history.replaceState(null, null, link);

      $('.widget-filter__btn.reset').trigger('click');

      if ( cpt == 'portfolio' ) {
        archive = '/photo-gallery/';
      } else if ( cpt == 'stock' ) {
        archive = '/stock-photography/';
      } else if ( cpt == 'cats' ) {
        archive = '/cats-photography/';
      }

      $.ajax({
        url: ajax_url,
        type: 'post',
        data: {
          paged: 1,
          archive: archive, // need add data $_SERVER["REQUEST_URI"] to paginate_links
          action: 'astrodj_archive_pagination'
        },
        beforeSend: function() {
          $('body').addClass('placeholder__preloading');
          $('#main').html(placeholder__gallery);
        },
        success: function(response) {
          $('body').removeClass('placeholder__preloading');
          $('#main').html(response);
          astrodj_lqip();

          if (debug) {
            console.log('Ajax pagination');
          }
        },
        error: function(jqxhr, status, errorMsg) {
          if (debug) {
            console.log('error Pagination: ' + errorMsg);
          }
        }
      });
    }); 

    /* ---------------------------------------------------------------------------
    * LQIP - copy from main.js
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

    /* ---------------------------------------------------------------------------
    *  Find Page Number in Pagination
    * --------------------------------------------------------------------------- */
    function find_page_number( element ) {
      element.find('span').remove();
      return parseInt( element.html() );
    }

    /* ---------------------------------------------------------------------------
    *  Media Uploader's EXIF data
    * --------------------------------------------------------------------------- */
    function exif_init() {
      var img_IDs = [];

      // destroy
      $('img[data-exif-id]').each(function(el) {
        var parent = $(this).parents('.astrodj-lqip');

        parent.find(".image-exif__overlay").remove();
        parent.find('.lds-ring').remove();
        parent.find('.image-exif__container').remove();
        parent.find('.image-exif__button').remove();

        var wrapper = parent.find(".image-exif__wrapper");
        var content = wrapper.contents();
        wrapper.replaceWith(content);

      });

      // new markup
      $('img[data-exif-id]').each(function(el) {
        img_IDs.push($(this).attr('data-exif-id'));

        for (var i = 0; i < img_IDs.length; i++) {
          if ($(this).attr('data-exif-id') == img_IDs[i]) {
            $(this).parents('.astrodj-lqip').prepend("<div class='image-exif__overlay'></div>")
              .append('<div class="lds-ring"><div></div><div></div><div></div><div></div></div><div class="image-exif__container"></div><div class="image-exif__button" data-exif-id="' + img_IDs[i] + '"><span>exif</span></div>')
              .wrapInner("<div class='image-exif__wrapper'></div>");
          }
        }
      });

      // all function
      var debug = main_data.debug;
      var rest_url = main_data.rest_media;
      var api_key = main_data.api_key;
      var api_secret = main_data.api_secret;
      var api_args = '?astrodj_api_key=' + api_key + '&astrodj_api_secret=' + api_secret;

      var svg_icon_camera_dslr = '<svg id="icon-camera-dslr" viewBox="0 0 510.338 510.338">' +
      '<g>' +
        '<path d="M246.825,97.303c-4.142,0-7.5,3.358-7.5,7.5s3.358,7.5,7.5,7.5h116.312c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5   H246.825z"></path>' +
        '<path d="M510.338,196.338c-0.866-23.259-16.279-43.535-37.979-51.837c-13.372-7.415-24.87-17.327-34.183-29.464   c-2.521-3.286-7.229-3.907-10.516-1.385c-3.286,2.521-3.906,7.229-1.385,10.516c10.594,13.809,23.681,25.079,38.902,33.503   c0.424,0.264,0.88,0.489,1.364,0.668c16.32,6.038,27.284,21.172,27.931,38.557l0.58,15.551h-71.079   c-26.333-43.225-73.914-72.148-128.124-72.148c-54.21,0-101.791,28.923-128.124,72.148H18.335l12.67-33.678   c3.895-10.352,12.187-18.178,22.749-21.471c0.159-0.05,0.316-0.105,0.47-0.164l106.954-29.857   c22.128-6.177,40.134-21.776,49.4-42.798c9.009-20.437,29.262-33.642,51.596-33.642h91.246c22.575,0,42.915,13.405,51.816,34.151   l5.233,12.198c1.633,3.806,6.043,5.569,9.85,3.935c3.807-1.633,5.568-6.044,3.936-9.85l-5.233-12.197   c-11.271-26.265-37.021-43.236-65.602-43.236h-91.246c-28.276,0-53.916,16.718-65.322,42.592   c-5.123,11.621-13.569,21.181-24.13,27.633l-1.627-5.786c-3.668-13.044-17.266-20.674-30.311-17.004l-37.044,10.417   C90.697,97.357,83.069,110.955,86.737,124l2.213,7.867l-39.571,11.046c-0.349,0.097-0.686,0.218-1.01,0.361   c-14.567,4.841-25.982,15.805-31.402,30.212L0.481,217.306C0.166,218.14,0,219.056,0,219.947v162.696c0,4.142,3.358,7.5,7.5,7.5   c4.142,0,7.5-3.358,7.5-7.5V227.447h80.123v232.054H37.5c-12.407,0-22.5-10.093-22.5-22.5v-24.358c0-4.142-3.358-7.5-7.5-7.5   c-4.142,0-7.5,3.358-7.5,7.5v24.358c0,20.678,16.822,37.5,37.5,37.5h435.338c20.678,0,37.5-16.822,37.5-37.5V196.338z    M101.177,119.939c-0.692-2.462-0.385-5.046,0.867-7.277c1.251-2.23,3.296-3.84,5.758-4.533l37.044-10.417   c5.084-1.431,10.382,1.543,11.811,6.625l2.237,7.953c-0.582,0.181-1.158,0.376-1.748,0.541l-53.748,15.004L101.177,119.939z    M442.861,260.81c14.233,2.217,25.16,14.556,25.16,29.402c0,14.845-10.928,27.185-25.16,29.401   c1.899-9.511,2.903-19.341,2.903-29.401C445.764,280.151,444.76,270.321,442.861,260.81z M295.851,155.298   c74.392,0,134.913,60.522,134.913,134.913s-60.521,134.913-134.913,134.913c-74.391,0-134.913-60.522-134.913-134.913   S221.459,155.298,295.851,155.298z M472.838,459.501H110.123V227.447h49.605c-8.844,19.104-13.79,40.366-13.79,62.765   c0,82.663,67.25,149.913,149.913,149.913c67.085,0,124.018-44.294,143.094-105.173c24.366-0.367,44.077-20.289,44.077-44.74   c0-24.452-19.711-44.374-44.077-44.741c-1.94-6.19-4.277-12.206-6.971-18.024h63.365v209.554   C495.338,449.408,485.244,459.501,472.838,459.501z"></path>' +
        '<path d="M295.851,407.836c64.858,0,117.625-52.767,117.625-117.625s-52.767-117.625-117.625-117.625   c-20.543,0-40.767,5.377-58.486,15.55c-3.592,2.063-4.832,6.646-2.77,10.239c2.063,3.592,6.646,4.833,10.239,2.77   c15.45-8.87,33.092-13.559,51.018-13.559c56.588,0,102.625,46.038,102.625,102.625s-46.037,102.625-102.625,102.625   c-56.587,0-102.625-46.038-102.625-102.625c0-27.412,10.675-53.184,30.058-72.567c2.929-2.929,2.929-7.678,0-10.607   c-2.929-2.929-7.678-2.929-10.606,0c-22.216,22.216-34.452,51.754-34.452,83.173C178.225,355.07,230.992,407.836,295.851,407.836z"></path>' +
        '<path d="M354.966,290.211c0-32.596-26.519-59.115-59.115-59.115c-32.597,0-59.115,26.519-59.115,59.115   s26.519,59.115,59.115,59.115C328.447,349.327,354.966,322.808,354.966,290.211z M251.735,290.211   c0-24.325,19.79-44.115,44.115-44.115c24.325,0,44.115,19.79,44.115,44.115c0,24.325-19.79,44.115-44.115,44.115   C271.525,334.327,251.735,314.537,251.735,290.211z"></path>' +
        '<path d="M60.987,181.466c0,13.547,11.022,24.568,24.568,24.568c13.547,0,24.568-11.021,24.568-24.568   c0-13.547-11.021-24.568-24.568-24.568C72.008,156.898,60.987,167.919,60.987,181.466z M95.123,181.466   c0,5.276-4.292,9.568-9.568,9.568c-5.276,0-9.568-4.293-9.568-9.568c0-5.276,4.292-9.568,9.568-9.568   C90.831,171.898,95.123,176.19,95.123,181.466z"></path>' +
      '</g>' +
      '</svg>';
      var svg_icon_camera_aperture = '<svg id="icon-camera-aperture" viewBox="0 0 512 512">' +
      '<path d="m289.257812 2.410156c-.722656-.148437-1.40625-.277344-2.109374-.320312-10.242188-1.257813-20.589844-2.089844-31.148438-2.089844-141.164062 0-256 114.835938-256 256s114.835938 256 256 256 256-114.835938 256-256c0-129.855469-97.28125-237.207031-222.742188-253.589844zm162.261719 144.597656h-221.480469l64.359376-111.464843c67.523437 11.730469 124.757812 53.652343 157.121093 111.464843zm-151.082031 185.898438h-88.875l-44.4375-76.949219 44.4375-76.949219h88.875l44.4375 76.949219zm-44.4375-300.90625c1.152344 0 2.261719.148438 3.390625.171875l-110.71875 191.785156-64.40625-111.507812c41.132813-49.109375 102.828125-80.449219 171.734375-80.449219zm-192.019531 109.269531 110.652343 191.636719h-128.746093c-8.832031-24.019531-13.886719-49.855469-13.886719-76.90625 0-41.960938 11.820312-81.128906 31.980469-114.730469zm-3.542969 223.636719h221.523438l-64.402344 111.550781c-67.542969-11.753906-124.78125-53.695312-157.121094-111.550781zm195.5625 115.09375c-1.152344 0-2.28125-.148438-3.433594-.171875l110.761719-191.871094 64.425781 111.574219c-41.128906 49.128906-102.824218 80.46875-171.753906 80.46875zm192.042969-109.3125-110.675781-191.679688h128.703124c8.851563 24.042969 13.929688 49.921876 13.929688 76.992188 0 41.941406-11.796875 81.089844-31.957031 114.6875zm0 0"></path>' +
      '</svg>';
      var svg_icon_camera_focus = '<svg id="icon-camera-focus" viewBox="0 0 512 512">' +
      '<path d="M509,476.09c-1.25-.58-31.1-16.3-31.1-16.3-5.3-2.8-9.7-5.2-9.9-5.3a77.57,77.57,0,0,1,3.3-7.9c4.4-9.7,15.57-40.82,17.6-47.1,3.21-9.92,4-13.7,5.31-18.09,1.84-6.31,3.37-11.64,5.09-20.11,2.72-13.46,7.9-42.6,9.5-61.8.7-7.7,1.7-23.27,2.2-31.36.8-16.12-.7-41.54-2.6-59.14,0,0-4.82-34.16-5-35.8-1.1-7.4-5.4-26.6-8.8-38.9l-2.87-10.73c-.69-1.59-4.78-15.75-5.43-17.87-.1-.1-2.8-7.1-5.9-15.5s-7.3-18.8-9.2-22.9a67.42,67.42,0,0,1-3.2-7.8c.2-.2,9.7-5.1,21.2-11.1s21-11.4,21.3-12-1.2-4.4-3.2-8.4c0,0-14.4-27-15-27S.85,256.69.85,257.09s4.8,3.3,10.8,6.3,15.3,7.9,20.9,10.8c0,0,37.55,19.55,47.9,24.9,0,0,57.6,30.1,65.7,34.4l51.7,26.7c12.1,6.2,41,21.4,41,21.4,18,9.23,66.9,34.81,78.3,40.7l58.3,30.5,105.8,55.1c5.3,2.8,10.2,5.1,10.9,5.1s5.4-8.1,10.4-17.9l9.3-17.9Zm-227.8-315.4c-5.4,17.5-11.66,51.34-13.56,70.54.06-2.36-1,15.13-1,22.42.31,7.85.31,14,.6,19.55.7,13.9,2.7,32.4,4.5,42l.8,4.8L279,356.38,250.65,341l-80.1-41.3c-11.6-6-59.3-30.9-59.3-30.9-11.8-6.1-21.4-11.4-21.4-11.8s192.6-100.9,192.7-101S281.95,158.09,281.25,160.69ZM358.69,313.1A153,153,0,0,1,348.55,344c-6.06,13.22-16.89,30.54-16.89,30.54l-7-19.25-3.1-9.6a331.83,331.83,0,0,1-9.3-37.6s-1.73-10-2.31-14.86-1.26-16.34-1.59-22.75c-.37-7.25.23-44.07,1.34-52.05,1.36-9.75,3.18-20.95,5.73-32.43,2-9.21,14.09-42.87,18.93-52.71l1.6-3.1,1.8,3.6c1,2,3.2,7,4.9,11.2s9.1,26.8,9.1,27.3c.4.4,2.2,6.2,3.8,12.8,3.1,12.5,8.15,43.17,8.8,52.53C366.84,273.11,364.17,288.52,358.69,313.1Zm72.25,118.59-2.7,2.1-22.1-11.4-33.36-17.28s6.46-16.32,9.16-23.12c8.5-21,17.35-58.25,19.3-70.7s3.11-28.52,3.9-43.1A302.47,302.47,0,0,0,403,218c-2.22-16.63-4.57-27.31-7.3-39.7a187.52,187.52,0,0,0-7.5-26.3c-6.18-17.07-16.4-40.3-16.4-41.5,0-.8,57.41-32,59.31-32.2.22-.07,7,16.1,9.09,21.4,5,12.7,19.6,64.4,21,71.3,2.4,11.8,4.58,18.82,6.6,42.1s1.8,39.9,1.8,39.9c.2,9.6-.56,24.29-.9,34.7a239.35,239.35,0,0,1-6.1,48.5C454.85,370.45,438.25,425.89,430.95,431.69Z"></path>' +
      '</svg>';
      var svg_icon_camera_timer_2 = '<svg id="icon-camera-timer-2" viewBox="0 0 238.18 274.01">' +
      '<path d="M119.05,35.81c65.43,0,119,53.41,119.13,119S184.39,274.19,119,274,0,220.24,0,154.84,53.59,35.85,119.05,35.81ZM226.53,155.12a107.44,107.44,0,1,0-214.88-.64h0c-.38,59.24,47.64,107.59,107.12,107.85C178.09,262.6,226.32,214.61,226.53,155.12Z"></path><path d="M119,18.53H101c-4.39,0-6.78-2.14-6.77-5.74S96.63,7.13,101,7.11q18-.06,35.93,0c4.42,0,6.86,2,7,5.57s-2.38,5.83-7,5.87C131,18.57,125,18.53,119,18.53Z"></path><path d="M221.14,59.69c-2-1.25-3.69-1.92-4.88-3.06-4.23-4-8.35-8.21-12.42-12.41-2.83-2.92-2.91-6.12-.41-8.57,2.32-2.28,5.69-2.27,8.35.28,4.4,4.23,8.88,8.42,12.85,13a7.88,7.88,0,0,1,1.27,6.38C225.39,57,223,58.15,221.14,59.69Z"></path><path d="M114.81,167.72c-10-2.9-15.33-11.6-13.65-20.19a17,17,0,0,1,18.92-13.89,5.13,5.13,0,0,0,5.31-2.12c10.05-12.24,20.21-24.4,30.34-36.59.64-.77,1.24-1.57,1.91-2.3,2.75-3,6-3.44,8.58-1.15s2.72,5.49.14,8.67c-5.19,6.39-10.49,12.7-15.75,19S140,132,134.49,138.36a4.19,4.19,0,0,0-.73,5.25c3,6.63,2.08,13-2.72,18.49A16.34,16.34,0,0,1,114.81,167.72ZM112.68,149a5.72,5.72,0,1,0,7.18-3.76,5.77,5.77,0,0,0-7.18,3.76Z"></path><path class="cls-1" d="M118.52,144.56a5.72,5.72,0,1,1-5.79,5.65v0A5.82,5.82,0,0,1,118.52,144.56Z"></path>' +
      '</svg>';
      var svg_icon_camera_iso_1 = '<svg id="icon-camera-iso-1" viewBox="0 -64 512 512">' +
      '<path d="m453.332031 0h-394.664062c-32.363281 0-58.667969 26.304688-58.667969 58.667969v266.664062c0 32.363281 26.304688 58.667969 58.667969 58.667969h394.664062c32.363281 0 58.667969-26.304688 58.667969-58.667969v-266.664062c0-32.363281-26.304688-58.667969-58.667969-58.667969zm26.667969 325.332031c0 14.699219-11.96875 26.667969-26.667969 26.667969h-394.664062c-14.699219 0-26.667969-11.96875-26.667969-26.667969v-266.664062c0-14.699219 11.96875-26.667969 26.667969-26.667969h394.664062c14.699219 0 26.667969 11.96875 26.667969 26.667969zm0 0"></path><path d="m389.332031 106.667969h-32c-20.585937 0-37.332031 16.746093-37.332031 37.332031v96c0 20.585938 16.746094 37.332031 37.332031 37.332031h32c20.589844 0 37.335938-16.746093 37.335938-37.332031v-96c0-20.585938-16.746094-37.332031-37.335938-37.332031zm5.335938 133.332031c0 2.945312-2.390625 5.332031-5.335938 5.332031h-32c-2.941406 0-5.332031-2.386719-5.332031-5.332031v-96c0-2.945312 2.390625-5.332031 5.332031-5.332031h32c2.945313 0 5.335938 2.386719 5.335938 5.332031zm0 0"></path><path d="m117.332031 106.667969c-8.832031 0-16 7.167969-16 16v138.664062c0 8.832031 7.167969 16 16 16s16-7.167969 16-16v-138.664062c0-8.832031-7.167969-16-16-16zm0 0"></path><path d="m224 138.667969h37.332031c8.832031 0 16-7.167969 16-16s-7.167969-16-16-16h-37.332031c-29.398438 0-53.332031 23.914062-53.332031 53.332031 0 28.265625 21.929687 48 53.332031 48 4.992188 0 21.332031 1.152344 21.332031 16 0 11.777344-9.578125 21.332031-21.332031 21.332031h-37.332031c-8.832031 0-16 7.167969-16 16s7.167969 16 16 16h37.332031c29.398438 0 53.332031-23.914062 53.332031-53.332031 0-28.265625-21.929687-48-53.332031-48-4.992188 0-21.332031-1.152344-21.332031-16 0-11.777344 9.578125-21.332031 21.332031-21.332031zm0 0"></path>' +
      '</svg>';

      var wrapper = $('.image-exif__wrapper'),
          overlay = wrapper.find('.image-exif__overlay'),
          container = wrapper.find('.image-exif__container'),
          button = wrapper.find('.image-exif__button');

      overlay.on('click', function() {
        $(this).parent(wrapper).removeClass('show');
      });

      container.on('click', function() {
        $(this).parent(wrapper).removeClass('show');
      });

      button.on('click', function() {
        $(this).parent(wrapper).toggleClass('show');
      });

      button.one('click', function() {
        var $this = $(this);
        var button_ID = $this.attr('data-exif-id');
        var json_url = rest_url + button_ID;
        var json_url = json_url + api_args;

        $this.prevAll('.lds-ring').show();

        $.ajax({
          dataType: 'json',
          url: json_url
        })
          
          .done(function(object) {
            exif_data(object);
            $this.prevAll('.lds-ring').hide();
          })
          
          .fail(function() {
            if (debug) {
              console.log('REST Exif error');
            }
          });

          function exif_data(object) {
            var media_author = '',
                media_datetime = '',
                media_camera = '',
                media_lens = '',
                media_aperture = '',
                media_focal = '',
                media_time = '',
                media_iso = '';

            if (object.astrodj_exif.media_author !== '') {
              media_author = '<div><span>Автор: </span>' + object.astrodj_exif.media_author + '</div>';
            }

            if (object.astrodj_exif.media_datetime !== '') {
              media_datetime = '<div><span>Снято: </span>' + object.astrodj_exif.media_datetime + '</div>';
            }

            if (object.astrodj_exif.media_camera !== '') {
              var camera_br = object.astrodj_exif.media_camera.replace('EOS', 'EOS<br>');
              media_camera = '<span>' + camera_br + '</span>';
            }

            if (object.astrodj_exif.media_lens !== '0') {
              var lens_br = object.astrodj_exif.media_lens.replace('f/', '<br>f/');
              media_lens = '<span>' + lens_br + '</span>';
            }

            if (object.astrodj_exif.media_aperture !== '') {
              media_aperture = '<div class="media-exif__content--item">' +
                                '<div class="icon">' + svg_icon_camera_aperture + '</div>' +
                                '<span>ƒ/' + object.astrodj_exif.media_aperture + '</span>' +
                                '</div>';
            }

            if (object.astrodj_exif.media_focal !== '') {
              media_focal = '<div class="media-exif__content--item">' +
                            '<div class="icon">' + svg_icon_camera_focus + '</div>' +
                            '<span>' + object.astrodj_exif.media_focal + ' mm</span>' +
                            '</div>';
            }

            if (object.astrodj_exif.media_time !== '') {
              media_time = '<div class="media-exif__content--item">' +
                            '<div class="icon">' + svg_icon_camera_timer_2 + '</div>' +
                            '<span>' + object.astrodj_exif.media_time + 'с</span>' +
                            '</div>';
            }

            if (object.astrodj_exif.media_iso !== '') {
              media_iso = '<div class="media-exif__content--item">' +
                          '<div class="icon">' + svg_icon_camera_iso_1 + '</div>' +
                          '<span>' + object.astrodj_exif.media_iso + '</span>' +
                          '</div>';
            }

            var exif_data_content = media_author +
                                    media_datetime +
                                    '<div class="media-exif__wrapper block" style="text-align: left;"><div class="media-exif__content">' +
                                    '<div class="media-exif__content--item media-exif__content--item-camera">' +
                                    '<div class="media-exif__content--icon"><div class="icon">' + svg_icon_camera_dslr + '</div></div>' +
                                    '<div class="media-exif__content--inner">' +
                                    media_camera +
                                    media_lens +
                                    '</div>' +
                                    '</div>' +
                                    media_aperture +
                                    media_focal +
                                    media_time +
                                    media_iso +
                                    '</div></div>';

            $this.parent('.image-exif__wrapper').find('.image-exif__container').html(exif_data_content);
          }
      });
    }

    exif_init();
  });

}(jQuery));
