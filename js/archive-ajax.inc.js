!function(k){"use strict";k(document).ready(function(){var t,s=main_data.debug,c=archive_ajax.ajax_url,o=(archive_ajax.is_home,archive_ajax.site_url),i=archive_ajax.site_name,a=archive_ajax.site_description,l=k("body.archive-view").length,n='<div id="placeholder__content"><div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div><div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div><div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div><div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div></div>',e='<svg viewBox="0 -36 512 511"><path d="m231.898438 198.617188c28.203124 0 51.152343-22.945313 51.152343-51.148438 0-28.207031-22.949219-51.152344-51.152343-51.152344-28.203126 0-51.148438 22.945313-51.148438 51.152344 0 28.203125 22.945312 51.148438 51.148438 51.148438zm0-72.300782c11.664062 0 21.152343 9.488282 21.152343 21.152344 0 11.660156-9.488281 21.148438-21.152343 21.148438-11.660157 0-21.148438-9.488282-21.148438-21.148438 0-11.664062 9.488281-21.152344 21.148438-21.152344zm0 0"/><path d="m493.304688.5h-474.609376c-10.308593 0-18.695312 8.386719-18.695312 18.695312v401.726563c0 10.308594 8.386719 18.695313 18.695312 18.695313h474.609376c10.308593 0 18.695312-8.386719 18.695312-18.695313v-401.726563c0-10.308593-8.386719-18.695312-18.695312-18.695312zm-11.304688 30v237.40625l-94.351562-94.355469c-6.152344-6.140625-16.15625-6.136719-22.304688.011719l-133.441406 133.441406-85.238282-85.234375c-2.980468-2.984375-6.945312-4.628906-11.164062-4.628906-4.214844 0-8.175781 1.640625-11.15625 4.621094l-94.34375 94.34375v-285.605469zm-452 379.117188v-51.085938l105.5-105.5 85.234375 85.234375c2.984375 2.984375 6.949219 4.632813 11.167969 4.632813 4.210937 0 8.175781-1.644532 11.152344-4.625l133.445312-133.445313 105.503906 105.503906v99.285157zm0 0"/></svg>',d='<div id="placeholder__gallery"><div class="placeholder__gallery-item">'+e+'</div><div class="placeholder__gallery-item">'+e+'</div><div class="placeholder__gallery-item">'+e+'</div><div class="placeholder__gallery-item">'+e+'</div><div class="placeholder__gallery-item">'+e+"</div></div>";function r(){var e=document.title,t=C(k(".nav-links .page-numbers:last-child").clone()),a=k(".page-limit").first().data("page"),t=k(".load-more__btn").length||t?k(".load-more__btn").length?k(".load-more__btn").data("page"):t:1,i=location.href.replace(location.origin+"/",o);history.replaceState({data_paged:t,data_archive:a,page_title:e},"",i),history.pushState({data_paged:t,data_archive:a,page_title:e},"",null),s&&console.log("Current State: "+JSON.stringify(history.state))}function p(e,t){t=t||k(".page-limit").first().data("page"),k.ajax({url:c,type:"post",data:{paged:e,archive:t,action:"astrodj_archive_pagination"},beforeSend:function(){k("body").addClass("placeholder__preloading"),l?k("#main").html(n):k("#main").html(d),k("html, body").animate({scrollTop:0},100,"swing",function(){k(".main-navigation").removeClass("hide-menu")})},success:function(e){k("body").removeClass("placeholder__preloading"),k("#main").html(e),history.replaceState(null,"",k(".page-limit").data("page")),S(),s&&console.log("Ajax pagination")},error:function(e,t,a){s&&console.log("error Pagination: "+a)}})}window.addEventListener("popstate",function(e){s&&console.log("Popstate State: "+JSON.stringify(e.state)),document.title=e.state.page_title,p(e.state.data_paged,e.state.data_archive)},!1),k(document).on("click",".load-more__btn",function(){var e=k(this),t=e.attr("data-page"),a=e.attr("data-archive"),i=k(".pagination-wrapper"),o=i.find(".loader");void 0===a&&(a=0),e.hide(),o.show(),k.ajax({url:c,type:"post",data:{page:t,archive:a,load_more_btn:!0,action:"astrodj_archive_pagination"},success:function(e){0==e?k(".load-more").before("<div><p>Больше нет постов для загрузки...</p></div>"):(i.replaceWith(e),e=k(".pagination-wrapper").prev(".page-limit").find("article").first(),l&&k("html, body").animate({scrollTop:e.offset().top},500,"swing"),S(),s&&console.log("Ajax Load More Button"))}})}),k(window).on("scroll",function(e){t=!0}),setInterval(function(){var e;t&&(e=k(window).scrollTop(),Math.abs(+e)>.1*k(window).height()&&k(".page-limit").each(function(e){if(t=k(this),a=k(window).scrollTop(),i=k(window).height(),o=k(t).offset().top,t=k(t).height(),a<o+t-.25*t&&o<a+.5*i)return history.replaceState(null,null,k(this).data("page")),!1;var t,a,i,o}),t=!1)},250),k(document).on("click",".nav-links a",function(e){e.preventDefault();var e=k(this).data("page"),t=document.title,a=C(k(".nav-links .page-numbers:last-child").clone()),a=k(".load-more__btn").length?k(".load-more__btn").data("page"):a,i=1!==a?k(".page-limit").last().data("page").replace(/\d/,e):-1!==k(".page-limit").last().data("page").indexOf("?s=")?"/page/"+e+k(".page-limit").last().data("page"):k(".page-limit").last().data("page")+"page/"+e+"/";history.replaceState({data_paged:a,page_title:t},"",i),history.pushState({data_paged:a,page_title:t},"",null),s&&console.log("Current State: "+JSON.stringify(history.state)),!l&&k("header#filter-init").length&&k("#results").append(k("header#filter-init").html()),p(e)}),k(document).on("click",".archive-view:not(.error404) .cat-links a, .archive-view:not(.error404) .widget_categories a, .archive-view:not(.error404) .widget_tag_cloud a",function(e){e.preventDefault();var e=k(this).attr("href"),t=k(this).text();r(),document.title=t+" | "+a+" — "+i,k("body").hasClass("opened-sidebar")&&k("body").removeClass("opened-sidebar"),k(".site").attr("style",""),k(".header-search .search-form, .widget_search .search-form").find("input").val(""),k.ajax({url:c,type:"post",data:{link:e,action:"astrodj_archive_links"},beforeSend:function(){k("body").addClass("placeholder__preloading"),k("#main").html(n),k("html, body").animate({scrollTop:0},100,"swing",function(){k(".main-navigation").removeClass("hide-menu")})},success:function(e){k("body").removeClass("placeholder__preloading"),k("#main").html(e),history.replaceState(null,null,k(".page-limit").data("page")),S(),s&&console.log("Ajax Links")},error:function(e,t,a){s&&console.log("error Links: "+a)}})}),k(document).on("submit",".archive-view:not(.error404) .search-form",function(e){e.preventDefault();var t=k(this),a=t.find("input").val();r(),document.title="Результаты поиска «"+a+"» — "+i,k("body").hasClass("opened-sidebar")&&k("body").removeClass("opened-sidebar"),k(".site").attr("style",""),k.ajax({type:"post",url:c,data:{value:a,action:"astrodj_search_form"},beforeSend:function(){k("body").addClass("placeholder__preloading"),k("#main").html(n),k(".header-search .search-form, .widget_search .search-form").find("input").val(a),k("html, body").animate({scrollTop:0},100,"swing",function(){k(".main-navigation").removeClass("hide-menu")})},success:function(e){k("body").removeClass("placeholder__preloading"),k("#main").html(e),history.replaceState(null,"",k(".page-limit").data("page")),S(),s&&console.log("Search Form Ajax Results")},error:function(e,t,a){s&&console.log("error Search Form: "+a)}}).done(function(){t.find("input").blur()})});var g,h,v=k("#filter-widget-area"),_=k(".widget-filter__count #count").text(),f=(k(window).on("click",function(){v.removeClass("opened"),k("body").removeClass("opened-filter"),k(".widget-filter__select .options").fadeOut(150)}),k("#menu-filter").on("click",function(e){e.stopPropagation(),v.toggleClass("opened"),k("body").addClass("opened-filter")}),v.on("click",function(e){e.stopPropagation(),k(".widget-filter__select .options").fadeOut(150)}),k(".widget-filter__btn.close").on("click",function(){v.removeClass("opened"),k("body").removeClass("opened-filter")}),k(".widget-filter__select .selected").find("span").text()),u='<svg id="icon-angle-down" viewBox="0 0 21 32"><path class="path1" d="M19.196 13.143q0 0.232-0.179 0.411l-8.321 8.321q-0.179 0.179-0.411 0.179t-0.411-0.179l-8.321-8.321q-0.179-0.179-0.179-0.411t0.179-0.411l0.893-0.893q0.179-0.179 0.411-0.179t0.411 0.179l7.018 7.018 7.018-7.018q0.179-0.179 0.411-0.179t0.411 0.179l0.893 0.893q0.179 0.179 0.179 0.411z"></path></svg>',m=(k(".widget-filter__select").on("click",".selected",function(e){e.stopPropagation(),k(this).next(".options").fadeToggle(150)}),k(".widget-filter__select .options").on("click",".options-item",function(){var e=k(this).text(),t=k(this).data("order");k(this).parent().fadeOut(150),k(this).siblings(".select").removeClass("select"),k(this).addClass("select"),k(this).parent().prev().find("span").text(e).attr("data-order",t),k(this).parent().prev().find(".icon-wrap").addClass("close"),k(this).parent().prev().find("svg").attr("class","icon icon-close").html('<svg id="icon-close" viewBox="0 0 25 32"><path class="path1" d="M23.179 23.607q0 0.714-0.5 1.214l-2.429 2.429q-0.5 0.5-1.214 0.5t-1.214-0.5l-5.25-5.25-5.25 5.25q-0.5 0.5-1.214 0.5t-1.214-0.5l-2.429-2.429q-0.5-0.5-0.5-1.214t0.5-1.214l5.25-5.25-5.25-5.25q-0.5-0.5-0.5-1.214t0.5-1.214l2.429-2.429q0.5-0.5 1.214-0.5t1.214 0.5l5.25 5.25 5.25-5.25q0.5-0.5 1.214-0.5t1.214 0.5l2.429 2.429q0.5 0.5 0.5 1.214t-0.5 1.214l-5.25 5.25 5.25 5.25q0.5 0.5 0.5 1.214z"></path></svg>'),g=!0,w()}),k(".widget-filter__select .selected").on("click",".icon-wrap.close",function(e){e.stopPropagation(),k(this).removeClass("close"),k(this).parent().next().fadeOut(150),k(this).find("svg").attr("class","icon icon-angle-down").html(u),k(this).prev("span").attr("data-order","").text(f),k(this).parent().next().find(".options-item").removeClass("select"),g=!1,w()}),[]);function w(){h||g?k(".widget-filter__btn:not(.close)").show():k(".widget-filter__btn:not(.close)").hide()}k(".widget-filter__grid--item").on("click","img, span",function(e){if(k(this).parent().toggleClass("active"),k(this).parent().hasClass("active"))m.push(k(this).parent().attr("data-id")),s&&console.log(m);else{for(var t=0;t<m.length;t++)k(this).parent().attr("data-id")==m[t]&&(m[t]="");m=jQuery.grep(m,function(e){return""!=e}),s&&console.log(m)}h=0!=m.length,w();var a=k(".widget-filter__wrapper").data("cpt");k.ajax({url:c,type:"post",data:{cpt:a,cat_IDs:m,action:"astrodj_widget_filter_count"},beforeSend:function(){k(".widget-filter__count #count").html('<svg viewBox="0 0 32 32"><path d="M16,5.2c5.9,0,10.8,4.8,10.8,10.8S21.9,26.8,16,26.8S5.2,21.9,5.2,16H0c0,8.8,7.2,16,16,16s16-7.2,16-16S24.8,0,16,0V5.2z"/></svg>')},success:function(e){k(".widget-filter__count #count").html(e)},error:function(e,t,a){s&&console.log("error Widget Filter Count: "+a)}})});var b=[];if(localStorage.getItem("filter-check_cat")&&""!==location.search){b=localStorage.getItem("filter-check_cat").split(","),h=!0,w();for(var m=b.concat(m),y=0;y<b.length;y++)k(".widget-filter__grid--item").each(function(e){k(this).attr("data-id")==b[y]&&k(this).addClass("active")})}function S(){var o,e=[].slice.call(document.querySelectorAll("img.lazy"));"IntersectionObserver"in window&&(o=new IntersectionObserver(function(e,t){e.forEach(function(e){var t,a,i;e.isIntersecting&&(e=(t=e.target).closest(".astrodj-lqip__wrap").previousElementSibling,a=e.firstElementChild,i=t.closest("figure"),e.classList.add("bg"),setTimeout(function(){a.classList.add("visible"),t.alt=i.hasAttribute("data-alt")?i.dataset.alt:"",t.src=i.dataset.src,t.srcset=i.hasAttribute("data-srcset")?i.dataset.srcset:"",t.sizes=i.hasAttribute("data-sizes")?i.dataset.sizes:"",t.addEventListener("load",function(e){e.target.classList.add("visible")}),o.unobserve(t)},300))})},{threshold:.25}),e.forEach(function(e){o.observe(e)})),k("img.lazy").each(function(e){var t=parseInt(k(this).attr("width")),a=parseInt(k(this).attr("height")),a=a<t?100/(t/a):100*(a/t);k(this).parents(".astrodj-lqip__wrap").prevAll(".aspect-ratio-fill").attr("style","padding-bottom: "+a+"%;width: 100%;")})}function C(e){return e.find("span").remove(),parseInt(e.html())}localStorage.getItem("filter-check_order")&&""!==location.search&&k(".widget-filter__select .options .options-item[data-order="+localStorage.getItem("filter-check_order")+"]").trigger("click"),localStorage.getItem("filter-check_count")&&""!==location.search&&k(".widget-filter__count #count").html(localStorage.getItem("filter-check_count")),k(".widget-filter__btn.reset").on("click",function(){k(".widget-filter__grid--item").removeClass("active"),m=[],s&&console.log(m),k(".widget-filter__select .icon-wrap").removeClass("close"),k(".widget-filter__select .icon-close").attr("class","icon icon-angle-down").html(u),k(".widget-filter__select .selected span").attr("data-order","").text(f),k(".widget-filter__select .options-item").removeClass("select"),k(".widget-filter__btn:not(.close)").hide(),k(".widget-filter__count #count").html(_)}),k(".widget-filter__btn.success").on("click",function(){v.removeClass("opened"),k("body").removeClass("opened-filter");var e=k(".widget-filter__wrapper").data("cpt"),t=(s&&console.log(e),s&&console.log(m),k(".widget-filter__select .selected").find("span").attr("data-order"));s&&console.log(t),localStorage.setItem("filter-check_order",t),localStorage.setItem("filter-check_cat",m),localStorage.setItem("filter-check_count",k(".widget-filter__count #count").text());var a="/?"+[0<m.length?"terms="+m.join().replaceAll(",","%2C"):"",""!==t?"order="+t:""].filter(function(e){return""!==e}).join("&"),a=location.pathname.split("/").splice(1,1).toString()+a,a=location.href.replace(location.pathname+location.search,"/"+a),i=document.title,o=C(k(".nav-links .page-numbers:last-child").clone()),o=k(".load-more__btn").length||o?k(".load-more__btn").length?k(".load-more__btn").data("page"):o:1,l="/"+location.pathname.split("/").splice(1,1).toString()+"/",a=(history.replaceState({data_paged:o,data_archive:l,page_title:i},null,a),history.pushState({data_paged:o,data_archive:l,page_title:i},null,null),s&&console.log("Widget Filter: "+JSON.stringify(history.state)),0<m.length?'<span class="count"><span class="block round">'+k(".widget-filter__count #count").text()+"</span> Фото</span>":""),o=""!==t?'<span class="data">Дата съемки: <span class="block">'+("DESC"===t?"Сначала новые":"Сначала ранние")+"</span></span>":"",n=[];if(0<m.length)for(var r=0;r<m.length;r++)k(".widget-filter__grid--item").each(function(){k(this).attr("data-id")==m[r]&&n.push(k(this).find("span").text())});l=n.join('</span>, <span class="block">'),i=""!==l?'<span class="cat">Категории: <span class="block">'+l+"</span></span>":"",l='<div class="widget-filter__page--inner"><span class="title"><svg class="icon" viewBox="0 0 477.867 477.867"><g><g><path d="M460.8,221.867H185.31c-9.255-36.364-46.237-58.34-82.602-49.085c-24.116,6.138-42.947,24.969-49.085,49.085H17.067C7.641,221.867,0,229.508,0,238.934S7.641,256,17.067,256h36.557c9.255,36.364,46.237,58.34,82.602,49.085c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,221.867,460.8,221.867zM119.467,273.067c-18.851,0-34.133-15.282-34.133-34.133c0-18.851,15.282-34.133,34.133-34.133s34.133,15.282,34.133,34.133C153.6,257.785,138.318,273.067,119.467,273.067z"/></g></g><g><g><path d="M460.8,51.2h-53.623c-9.255-36.364-46.237-58.34-82.602-49.085C300.459,8.253,281.628,27.084,275.49,51.2H17.067C7.641,51.2,0,58.841,0,68.267s7.641,17.067,17.067,17.067H275.49c9.255,36.364,46.237,58.34,82.602,49.085c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,51.2,460.8,51.2z M341.334,102.4c-18.851,0-34.133-15.282-34.133-34.133s15.282-34.133,34.133-34.133s34.133,15.282,34.133,34.133S360.185,102.4,341.334,102.4z"/></g></g><g><g><path d="M460.8,392.534h-87.757c-9.255-36.364-46.237-58.34-82.602-49.085c-24.116,6.138-42.947,24.969-49.085,49.085H17.067C7.641,392.534,0,400.175,0,409.6s7.641,17.067,17.067,17.067h224.29c9.255,36.364,46.237,58.34,82.602,49.085c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,392.534,460.8,392.534zM307.2,443.734c-18.851,0-34.133-15.282-34.133-34.133s15.282-34.133,34.133-34.133c18.851,0,34.133,15.282,34.133,34.133S326.052,443.734,307.2,443.734z"/></g></g></svg></span>'+o+i+a+'<div class="reset">Сбросить <svg class="icon" viewBox="0 0 25 32"><path class="path1" d="M23.179 23.607q0 0.714-0.5 1.214l-2.429 2.429q-0.5 0.5-1.214 0.5t-1.214-0.5l-5.25-5.25-5.25 5.25q-0.5 0.5-1.214 0.5t-1.214-0.5l-2.429-2.429q-0.5-0.5-0.5-1.214t0.5-1.214l5.25-5.25-5.25-5.25q-0.5-0.5-0.5-1.214t0.5-1.214l2.429-2.429q0.5-0.5 1.214-0.5t1.214 0.5l5.25 5.25 5.25-5.25q0.5-0.5 1.214-0.5t1.214 0.5l2.429 2.429q0.5 0.5 0.5 1.214t-0.5 1.214l-5.25 5.25 5.25 5.25q0.5 0.5 0.5 1.214z"></path></svg></div></div>';k("#filter-init").remove(),k("#results").html(l),k.ajax({url:c,type:"post",data:{cpt:e,cat_IDs:m,order:t,action:"astrodj_widget_filter"},beforeSend:function(){k("body").addClass("placeholder__preloading"),k("#main").html(d),k("html, body").animate({scrollTop:0},100,"swing",function(){k(".main-navigation").removeClass("hide-menu")})},success:function(e){k("body").removeClass("placeholder__preloading"),k("#main").html(e),S(),s&&console.log("Ajax Widget Filter")},error:function(e,t,a){s&&console.log("error Widget Filter: "+a)}})}),k("#results, #filter-init").on("click",".reset",function(){k("#filter-init").remove(),k("#results").html(""),localStorage.removeItem("filter-check_order"),localStorage.removeItem("filter-check_cat"),localStorage.removeItem("filter-check_count");var e,t=k(".widget-filter__wrapper").data("cpt"),a=location.pathname.split("/").splice(1,1).toString(),a=location.href.replace(location.pathname+location.search,"/"+a+"/");history.replaceState(null,null,a),k(".widget-filter__btn.reset").trigger("click"),"portfolio"==t?e="/photo-gallery/":"stock"==t?e="/stock-photography/":"cats"==t&&(e="/cats-photography/"),k.ajax({url:c,type:"post",data:{paged:1,archive:e,action:"astrodj_archive_pagination"},beforeSend:function(){k("body").addClass("placeholder__preloading"),k("#main").html(d)},success:function(e){k("body").removeClass("placeholder__preloading"),k("#main").html(e),S(),s&&console.log("Ajax pagination")},error:function(e,t,a){s&&console.log("error Pagination: "+a)}})})})}(jQuery);