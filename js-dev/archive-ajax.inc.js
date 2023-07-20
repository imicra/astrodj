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
  });

}(jQuery));
