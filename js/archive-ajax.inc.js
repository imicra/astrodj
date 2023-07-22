!function(j){"use strict";j(document).ready(function(){var t,n=main_data.debug,r=archive_ajax.ajax_url,o=(archive_ajax.is_home,archive_ajax.site_url),i=archive_ajax.site_name,a=archive_ajax.site_description,c=j("body.archive-view").length,l='<div id="placeholder__content"><div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div><div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div><div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div><div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div></div>',e='<svg viewBox="0 -36 512 511"><path d="m231.898438 198.617188c28.203124 0 51.152343-22.945313 51.152343-51.148438 0-28.207031-22.949219-51.152344-51.152343-51.152344-28.203126 0-51.148438 22.945313-51.148438 51.152344 0 28.203125 22.945312 51.148438 51.148438 51.148438zm0-72.300782c11.664062 0 21.152343 9.488282 21.152343 21.152344 0 11.660156-9.488281 21.148438-21.152343 21.148438-11.660157 0-21.148438-9.488282-21.148438-21.148438 0-11.664062 9.488281-21.152344 21.148438-21.152344zm0 0"/><path d="m493.304688.5h-474.609376c-10.308593 0-18.695312 8.386719-18.695312 18.695312v401.726563c0 10.308594 8.386719 18.695313 18.695312 18.695313h474.609376c10.308593 0 18.695312-8.386719 18.695312-18.695313v-401.726563c0-10.308593-8.386719-18.695312-18.695312-18.695312zm-11.304688 30v237.40625l-94.351562-94.355469c-6.152344-6.140625-16.15625-6.136719-22.304688.011719l-133.441406 133.441406-85.238282-85.234375c-2.980468-2.984375-6.945312-4.628906-11.164062-4.628906-4.214844 0-8.175781 1.640625-11.15625 4.621094l-94.34375 94.34375v-285.605469zm-452 379.117188v-51.085938l105.5-105.5 85.234375 85.234375c2.984375 2.984375 6.949219 4.632813 11.167969 4.632813 4.210937 0 8.175781-1.644532 11.152344-4.625l133.445312-133.445313 105.503906 105.503906v99.285157zm0 0"/></svg>',d='<div id="placeholder__gallery"><div class="placeholder__gallery-item">'+e+'</div><div class="placeholder__gallery-item">'+e+'</div><div class="placeholder__gallery-item">'+e+'</div><div class="placeholder__gallery-item">'+e+'</div><div class="placeholder__gallery-item">'+e+"</div></div>";function s(){var e=document.title,t=C(j(".nav-links .page-numbers:last-child").clone()),a=j(".page-limit").first().data("page"),t=j(".load-more__btn").length||t?j(".load-more__btn").length?j(".load-more__btn").data("page"):t:1,i=location.href.replace(location.origin+"/",o);history.replaceState({data_paged:t,data_archive:a,page_title:e},"",i),history.pushState({data_paged:t,data_archive:a,page_title:e},"",null),n&&console.log("Current State: "+JSON.stringify(history.state))}function p(e,t){t=t||j(".page-limit").first().data("page"),j.ajax({url:r,type:"post",data:{paged:e,archive:t,action:"astrodj_archive_pagination"},beforeSend:function(){j("body").addClass("placeholder__preloading"),c?j("#main").html(l):j("#main").html(d),j("html, body").animate({scrollTop:0},100,"swing",function(){j(".main-navigation").removeClass("hide-menu")})},success:function(e){j("body").removeClass("placeholder__preloading"),j("#main").html(e),history.replaceState(null,"",j(".page-limit").data("page")),y(),j(".fancybox").fancybox(),S(),n&&console.log("Ajax pagination")},error:function(e,t,a){n&&console.log("error Pagination: "+a)}})}window.addEventListener("popstate",function(e){n&&console.log("Popstate State: "+JSON.stringify(e.state)),document.title=e.state.page_title,p(e.state.data_paged,e.state.data_archive)},!1),j(document).on("click",".load-more__btn",function(){var e=j(this),t=e.attr("data-page"),a=e.attr("data-archive"),i=j(".pagination-wrapper"),o=i.find(".loader");void 0===a&&(a=0),e.hide(),o.show(),j.ajax({url:r,type:"post",data:{page:t,archive:a,load_more_btn:!0,action:"astrodj_archive_pagination"},success:function(e){0==e?j(".load-more").before("<div><p>Больше нет постов для загрузки...</p></div>"):(i.replaceWith(e),e=j(".pagination-wrapper").prev(".page-limit").find("article").first(),c&&j("html, body").animate({scrollTop:e.offset().top},500,"swing"),y(),j(".fancybox").fancybox(),S(),n&&console.log("Ajax Load More Button"))}})}),j(window).on("scroll",function(e){t=!0}),setInterval(function(){var e;t&&(e=j(window).scrollTop(),Math.abs(+e)>.1*j(window).height()&&j(".page-limit").each(function(e){if(t=j(this),a=j(window).scrollTop(),i=j(window).height(),o=j(t).offset().top,t=j(t).height(),a<o+t-.25*t&&o<a+.5*i)return history.replaceState(null,null,j(this).data("page")),!1;var t,a,i,o}),t=!1)},250),j(document).on("click",".nav-links a",function(e){e.preventDefault();var e=j(this).data("page"),t=document.title,a=C(j(".nav-links .page-numbers:last-child").clone()),a=j(".load-more__btn").length?j(".load-more__btn").data("page"):a,i=1!==a?j(".page-limit").last().data("page").replace(/\d/,e):-1!==j(".page-limit").last().data("page").indexOf("?s=")?"/page/"+e+j(".page-limit").last().data("page"):j(".page-limit").last().data("page")+"page/"+e+"/";history.replaceState({data_paged:a,page_title:t},"",i),history.pushState({data_paged:a,page_title:t},"",null),n&&console.log("Current State: "+JSON.stringify(history.state)),!c&&j("header#filter-init").length&&j("#results").append(j("header#filter-init").html()),p(e)}),j(document).on("click",".archive-view:not(.error404) .cat-links a, .archive-view:not(.error404) .widget_categories a, .archive-view:not(.error404) .widget_tag_cloud a",function(e){e.preventDefault();var e=j(this).attr("href"),t=j(this).text();s(),document.title=t+" | "+a+" — "+i,j("body").hasClass("opened-sidebar")&&j("body").removeClass("opened-sidebar"),j(".site").attr("style",""),j(".header-search .search-form, .widget_search .search-form").find("input").val(""),j.ajax({url:r,type:"post",data:{link:e,action:"astrodj_archive_links"},beforeSend:function(){j("body").addClass("placeholder__preloading"),j("#main").html(l),j("html, body").animate({scrollTop:0},100,"swing",function(){j(".main-navigation").removeClass("hide-menu")})},success:function(e){j("body").removeClass("placeholder__preloading"),j("#main").html(e),history.replaceState(null,null,j(".page-limit").data("page")),y(),n&&console.log("Ajax Links")},error:function(e,t,a){n&&console.log("error Links: "+a)}})}),j(document).on("submit",".archive-view:not(.error404) .search-form",function(e){e.preventDefault();var t=j(this),a=t.find("input").val();s(),document.title="Результаты поиска «"+a+"» — "+i,j("body").hasClass("opened-sidebar")&&j("body").removeClass("opened-sidebar"),j(".site").attr("style",""),j.ajax({type:"post",url:r,data:{value:a,action:"astrodj_search_form"},beforeSend:function(){j("body").addClass("placeholder__preloading"),j("#main").html(l),j(".header-search .search-form, .widget_search .search-form").find("input").val(a),j("html, body").animate({scrollTop:0},100,"swing",function(){j(".main-navigation").removeClass("hide-menu")})},success:function(e){j("body").removeClass("placeholder__preloading"),j("#main").html(e),history.replaceState(null,"",j(".page-limit").data("page")),y(),n&&console.log("Search Form Ajax Results")},error:function(e,t,a){n&&console.log("error Search Form: "+a)}}).done(function(){t.find("input").blur()})});var h,g,v=j("#filter-widget-area"),_=j(".widget-filter__count #count").text(),m=(j(window).on("click",function(){v.removeClass("opened"),j("body").removeClass("opened-filter"),j(".widget-filter__select .options").fadeOut(150)}),j("#menu-filter").on("click",function(e){e.stopPropagation(),v.toggleClass("opened"),j("body").addClass("opened-filter")}),v.on("click",function(e){e.stopPropagation(),j(".widget-filter__select .options").fadeOut(150)}),j(".widget-filter__btn.close").on("click",function(){v.removeClass("opened"),j("body").removeClass("opened-filter")}),j(".widget-filter__select .selected").find("span").text()),f='<svg id="icon-angle-down" viewBox="0 0 21 32"><path class="path1" d="M19.196 13.143q0 0.232-0.179 0.411l-8.321 8.321q-0.179 0.179-0.411 0.179t-0.411-0.179l-8.321-8.321q-0.179-0.179-0.179-0.411t0.179-0.411l0.893-0.893q0.179-0.179 0.411-0.179t0.411 0.179l7.018 7.018 7.018-7.018q0.179-0.179 0.411-0.179t0.411 0.179l0.893 0.893q0.179 0.179 0.179 0.411z"></path></svg>',u=(j(".widget-filter__select").on("click",".selected",function(e){e.stopPropagation(),j(this).next(".options").fadeToggle(150)}),j(".widget-filter__select .options").on("click",".options-item",function(){var e=j(this).text(),t=j(this).data("order");j(this).parent().fadeOut(150),j(this).siblings(".select").removeClass("select"),j(this).addClass("select"),j(this).parent().prev().find("span").text(e).attr("data-order",t),j(this).parent().prev().find(".icon-wrap").addClass("close"),j(this).parent().prev().find("svg").attr("class","icon icon-close").html('<svg id="icon-close" viewBox="0 0 25 32"><path class="path1" d="M23.179 23.607q0 0.714-0.5 1.214l-2.429 2.429q-0.5 0.5-1.214 0.5t-1.214-0.5l-5.25-5.25-5.25 5.25q-0.5 0.5-1.214 0.5t-1.214-0.5l-2.429-2.429q-0.5-0.5-0.5-1.214t0.5-1.214l5.25-5.25-5.25-5.25q-0.5-0.5-0.5-1.214t0.5-1.214l2.429-2.429q0.5-0.5 1.214-0.5t1.214 0.5l5.25 5.25 5.25-5.25q0.5-0.5 1.214-0.5t1.214 0.5l2.429 2.429q0.5 0.5 0.5 1.214t-0.5 1.214l-5.25 5.25 5.25 5.25q0.5 0.5 0.5 1.214z"></path></svg>'),h=!0,w()}),j(".widget-filter__select .selected").on("click",".icon-wrap.close",function(e){e.stopPropagation(),j(this).removeClass("close"),j(this).parent().next().fadeOut(150),j(this).find("svg").attr("class","icon icon-angle-down").html(f),j(this).prev("span").attr("data-order","").text(m),j(this).parent().next().find(".options-item").removeClass("select"),h=!1,w()}),[]);function w(){g||h?j(".widget-filter__btn:not(.close)").show():j(".widget-filter__btn:not(.close)").hide()}j(".widget-filter__grid--item").on("click","img, span",function(e){if(j(this).parent().toggleClass("active"),j(this).parent().hasClass("active"))u.push(j(this).parent().attr("data-id")),n&&console.log(u);else{for(var t=0;t<u.length;t++)j(this).parent().attr("data-id")==u[t]&&(u[t]="");u=jQuery.grep(u,function(e){return""!=e}),n&&console.log(u)}g=0!=u.length,w();var a=j(".widget-filter__wrapper").data("cpt");j.ajax({url:r,type:"post",data:{cpt:a,cat_IDs:u,action:"astrodj_widget_filter_count"},beforeSend:function(){j(".widget-filter__count #count").html('<svg viewBox="0 0 32 32"><path d="M16,5.2c5.9,0,10.8,4.8,10.8,10.8S21.9,26.8,16,26.8S5.2,21.9,5.2,16H0c0,8.8,7.2,16,16,16s16-7.2,16-16S24.8,0,16,0V5.2z"/></svg>')},success:function(e){j(".widget-filter__count #count").html(e)},error:function(e,t,a){n&&console.log("error Widget Filter Count: "+a)}})});var x=[];if(localStorage.getItem("filter-check_cat")&&""!==location.search){x=localStorage.getItem("filter-check_cat").split(","),g=!0,w();for(var u=x.concat(u),b=0;b<x.length;b++)j(".widget-filter__grid--item").each(function(e){j(this).attr("data-id")==x[b]&&j(this).addClass("active")})}function y(){var o,e=[].slice.call(document.querySelectorAll("img.lazy"));"IntersectionObserver"in window&&(o=new IntersectionObserver(function(e,t){e.forEach(function(e){var t,a,i;e.isIntersecting&&(e=(t=e.target).closest(".astrodj-lqip__wrap").previousElementSibling,a=e.firstElementChild,i=t.closest("figure"),e.classList.add("bg"),setTimeout(function(){a.classList.add("visible"),t.alt=i.hasAttribute("data-alt")?i.dataset.alt:"",t.src=i.dataset.src,t.srcset=i.hasAttribute("data-srcset")?i.dataset.srcset:"",t.sizes=i.hasAttribute("data-sizes")?i.dataset.sizes:"",t.addEventListener("load",function(e){e.target.classList.add("visible")}),o.unobserve(t)},300))})},{threshold:.25}),e.forEach(function(e){o.observe(e)})),j("img.lazy").each(function(e){var t=parseInt(j(this).attr("width")),a=parseInt(j(this).attr("height")),a=a<t?100/(t/a):100*(a/t);j(this).parents(".astrodj-lqip__wrap").prevAll(".aspect-ratio-fill").attr("style","padding-bottom: "+a+"%;width: 100%;")})}function C(e){return e.find("span").remove(),parseInt(e.html())}function S(){var a=[],t=(j("img[data-exif-id]").each(function(e){var t=j(this).parents(".astrodj-lqip"),t=(t.find(".image-exif__overlay").remove(),t.find(".lds-ring").remove(),t.find(".image-exif__container").remove(),t.find(".image-exif__button").remove(),t.find(".image-exif__wrapper")),a=t.contents();t.replaceWith(a)}),j("img[data-exif-id]").each(function(e){a.push(j(this).attr("data-exif-id"));for(var t=0;t<a.length;t++)j(this).attr("data-exif-id")==a[t]&&j(this).parents(".astrodj-lqip").prepend("<div class='image-exif__overlay'></div>").append('<div class="lds-ring"><div></div><div></div><div></div><div></div></div><div class="image-exif__container"></div><div class="image-exif__button" data-exif-id="'+a[t]+'"><span>exif</span></div>').wrapInner("<div class='image-exif__wrapper'></div>")}),main_data.debug),i=main_data.rest_media,o="?astrodj_api_key="+main_data.api_key+"&astrodj_api_secret="+main_data.api_secret,e=j(".image-exif__wrapper"),c=e.find(".image-exif__overlay"),l=e.find(".image-exif__container"),s=e.find(".image-exif__button");c.on("click",function(){j(this).parent(e).removeClass("show")}),l.on("click",function(){j(this).parent(e).removeClass("show")}),s.on("click",function(){j(this).parent(e).toggleClass("show")}),s.one("click",function(){var d=j(this),e=d.attr("data-exif-id"),e=i+e+o;d.prevAll(".lds-ring").show(),j.ajax({dataType:"json",url:e}).done(function(e){var t="",a="",i="",o="",c="",l="",s="",n="",r=(""!==e.astrodj_exif.media_author&&(t="<div><span>Автор: </span>"+e.astrodj_exif.media_author+"</div>"),""!==e.astrodj_exif.media_datetime&&(a="<div><span>Снято: </span>"+e.astrodj_exif.media_datetime+"</div>"),""!==e.astrodj_exif.media_camera&&(r=e.astrodj_exif.media_camera.replace("EOS","EOS<br>"),i="<span>"+r+"</span>"),"0"!==e.astrodj_exif.media_lens&&(r=e.astrodj_exif.media_lens.replace("f/","<br>f/"),o="<span>"+r+"</span>"),""!==e.astrodj_exif.media_aperture&&(c='<div class="media-exif__content--item"><div class="icon"><svg id="icon-camera-aperture" viewBox="0 0 512 512"><path d="m289.257812 2.410156c-.722656-.148437-1.40625-.277344-2.109374-.320312-10.242188-1.257813-20.589844-2.089844-31.148438-2.089844-141.164062 0-256 114.835938-256 256s114.835938 256 256 256 256-114.835938 256-256c0-129.855469-97.28125-237.207031-222.742188-253.589844zm162.261719 144.597656h-221.480469l64.359376-111.464843c67.523437 11.730469 124.757812 53.652343 157.121093 111.464843zm-151.082031 185.898438h-88.875l-44.4375-76.949219 44.4375-76.949219h88.875l44.4375 76.949219zm-44.4375-300.90625c1.152344 0 2.261719.148438 3.390625.171875l-110.71875 191.785156-64.40625-111.507812c41.132813-49.109375 102.828125-80.449219 171.734375-80.449219zm-192.019531 109.269531 110.652343 191.636719h-128.746093c-8.832031-24.019531-13.886719-49.855469-13.886719-76.90625 0-41.960938 11.820312-81.128906 31.980469-114.730469zm-3.542969 223.636719h221.523438l-64.402344 111.550781c-67.542969-11.753906-124.78125-53.695312-157.121094-111.550781zm195.5625 115.09375c-1.152344 0-2.28125-.148438-3.433594-.171875l110.761719-191.871094 64.425781 111.574219c-41.128906 49.128906-102.824218 80.46875-171.753906 80.46875zm192.042969-109.3125-110.675781-191.679688h128.703124c8.851563 24.042969 13.929688 49.921876 13.929688 76.992188 0 41.941406-11.796875 81.089844-31.957031 114.6875zm0 0"></path></svg></div><span>ƒ/'+e.astrodj_exif.media_aperture+"</span></div>"),""!==e.astrodj_exif.media_focal&&(l='<div class="media-exif__content--item"><div class="icon"><svg id="icon-camera-focus" viewBox="0 0 512 512"><path d="M509,476.09c-1.25-.58-31.1-16.3-31.1-16.3-5.3-2.8-9.7-5.2-9.9-5.3a77.57,77.57,0,0,1,3.3-7.9c4.4-9.7,15.57-40.82,17.6-47.1,3.21-9.92,4-13.7,5.31-18.09,1.84-6.31,3.37-11.64,5.09-20.11,2.72-13.46,7.9-42.6,9.5-61.8.7-7.7,1.7-23.27,2.2-31.36.8-16.12-.7-41.54-2.6-59.14,0,0-4.82-34.16-5-35.8-1.1-7.4-5.4-26.6-8.8-38.9l-2.87-10.73c-.69-1.59-4.78-15.75-5.43-17.87-.1-.1-2.8-7.1-5.9-15.5s-7.3-18.8-9.2-22.9a67.42,67.42,0,0,1-3.2-7.8c.2-.2,9.7-5.1,21.2-11.1s21-11.4,21.3-12-1.2-4.4-3.2-8.4c0,0-14.4-27-15-27S.85,256.69.85,257.09s4.8,3.3,10.8,6.3,15.3,7.9,20.9,10.8c0,0,37.55,19.55,47.9,24.9,0,0,57.6,30.1,65.7,34.4l51.7,26.7c12.1,6.2,41,21.4,41,21.4,18,9.23,66.9,34.81,78.3,40.7l58.3,30.5,105.8,55.1c5.3,2.8,10.2,5.1,10.9,5.1s5.4-8.1,10.4-17.9l9.3-17.9Zm-227.8-315.4c-5.4,17.5-11.66,51.34-13.56,70.54.06-2.36-1,15.13-1,22.42.31,7.85.31,14,.6,19.55.7,13.9,2.7,32.4,4.5,42l.8,4.8L279,356.38,250.65,341l-80.1-41.3c-11.6-6-59.3-30.9-59.3-30.9-11.8-6.1-21.4-11.4-21.4-11.8s192.6-100.9,192.7-101S281.95,158.09,281.25,160.69ZM358.69,313.1A153,153,0,0,1,348.55,344c-6.06,13.22-16.89,30.54-16.89,30.54l-7-19.25-3.1-9.6a331.83,331.83,0,0,1-9.3-37.6s-1.73-10-2.31-14.86-1.26-16.34-1.59-22.75c-.37-7.25.23-44.07,1.34-52.05,1.36-9.75,3.18-20.95,5.73-32.43,2-9.21,14.09-42.87,18.93-52.71l1.6-3.1,1.8,3.6c1,2,3.2,7,4.9,11.2s9.1,26.8,9.1,27.3c.4.4,2.2,6.2,3.8,12.8,3.1,12.5,8.15,43.17,8.8,52.53C366.84,273.11,364.17,288.52,358.69,313.1Zm72.25,118.59-2.7,2.1-22.1-11.4-33.36-17.28s6.46-16.32,9.16-23.12c8.5-21,17.35-58.25,19.3-70.7s3.11-28.52,3.9-43.1A302.47,302.47,0,0,0,403,218c-2.22-16.63-4.57-27.31-7.3-39.7a187.52,187.52,0,0,0-7.5-26.3c-6.18-17.07-16.4-40.3-16.4-41.5,0-.8,57.41-32,59.31-32.2.22-.07,7,16.1,9.09,21.4,5,12.7,19.6,64.4,21,71.3,2.4,11.8,4.58,18.82,6.6,42.1s1.8,39.9,1.8,39.9c.2,9.6-.56,24.29-.9,34.7a239.35,239.35,0,0,1-6.1,48.5C454.85,370.45,438.25,425.89,430.95,431.69Z"></path></svg></div><span>'+e.astrodj_exif.media_focal+" mm</span></div>"),""!==e.astrodj_exif.media_time&&(s='<div class="media-exif__content--item"><div class="icon"><svg id="icon-camera-timer-2" viewBox="0 0 238.18 274.01"><path d="M119.05,35.81c65.43,0,119,53.41,119.13,119S184.39,274.19,119,274,0,220.24,0,154.84,53.59,35.85,119.05,35.81ZM226.53,155.12a107.44,107.44,0,1,0-214.88-.64h0c-.38,59.24,47.64,107.59,107.12,107.85C178.09,262.6,226.32,214.61,226.53,155.12Z"></path><path d="M119,18.53H101c-4.39,0-6.78-2.14-6.77-5.74S96.63,7.13,101,7.11q18-.06,35.93,0c4.42,0,6.86,2,7,5.57s-2.38,5.83-7,5.87C131,18.57,125,18.53,119,18.53Z"></path><path d="M221.14,59.69c-2-1.25-3.69-1.92-4.88-3.06-4.23-4-8.35-8.21-12.42-12.41-2.83-2.92-2.91-6.12-.41-8.57,2.32-2.28,5.69-2.27,8.35.28,4.4,4.23,8.88,8.42,12.85,13a7.88,7.88,0,0,1,1.27,6.38C225.39,57,223,58.15,221.14,59.69Z"></path><path d="M114.81,167.72c-10-2.9-15.33-11.6-13.65-20.19a17,17,0,0,1,18.92-13.89,5.13,5.13,0,0,0,5.31-2.12c10.05-12.24,20.21-24.4,30.34-36.59.64-.77,1.24-1.57,1.91-2.3,2.75-3,6-3.44,8.58-1.15s2.72,5.49.14,8.67c-5.19,6.39-10.49,12.7-15.75,19S140,132,134.49,138.36a4.19,4.19,0,0,0-.73,5.25c3,6.63,2.08,13-2.72,18.49A16.34,16.34,0,0,1,114.81,167.72ZM112.68,149a5.72,5.72,0,1,0,7.18-3.76,5.77,5.77,0,0,0-7.18,3.76Z"></path><path class="cls-1" d="M118.52,144.56a5.72,5.72,0,1,1-5.79,5.65v0A5.82,5.82,0,0,1,118.52,144.56Z"></path></svg></div><span>'+e.astrodj_exif.media_time+"с</span></div>"),""!==e.astrodj_exif.media_iso&&(n='<div class="media-exif__content--item"><div class="icon"><svg id="icon-camera-iso-1" viewBox="0 -64 512 512"><path d="m453.332031 0h-394.664062c-32.363281 0-58.667969 26.304688-58.667969 58.667969v266.664062c0 32.363281 26.304688 58.667969 58.667969 58.667969h394.664062c32.363281 0 58.667969-26.304688 58.667969-58.667969v-266.664062c0-32.363281-26.304688-58.667969-58.667969-58.667969zm26.667969 325.332031c0 14.699219-11.96875 26.667969-26.667969 26.667969h-394.664062c-14.699219 0-26.667969-11.96875-26.667969-26.667969v-266.664062c0-14.699219 11.96875-26.667969 26.667969-26.667969h394.664062c14.699219 0 26.667969 11.96875 26.667969 26.667969zm0 0"></path><path d="m389.332031 106.667969h-32c-20.585937 0-37.332031 16.746093-37.332031 37.332031v96c0 20.585938 16.746094 37.332031 37.332031 37.332031h32c20.589844 0 37.335938-16.746093 37.335938-37.332031v-96c0-20.585938-16.746094-37.332031-37.335938-37.332031zm5.335938 133.332031c0 2.945312-2.390625 5.332031-5.335938 5.332031h-32c-2.941406 0-5.332031-2.386719-5.332031-5.332031v-96c0-2.945312 2.390625-5.332031 5.332031-5.332031h32c2.945313 0 5.335938 2.386719 5.335938 5.332031zm0 0"></path><path d="m117.332031 106.667969c-8.832031 0-16 7.167969-16 16v138.664062c0 8.832031 7.167969 16 16 16s16-7.167969 16-16v-138.664062c0-8.832031-7.167969-16-16-16zm0 0"></path><path d="m224 138.667969h37.332031c8.832031 0 16-7.167969 16-16s-7.167969-16-16-16h-37.332031c-29.398438 0-53.332031 23.914062-53.332031 53.332031 0 28.265625 21.929687 48 53.332031 48 4.992188 0 21.332031 1.152344 21.332031 16 0 11.777344-9.578125 21.332031-21.332031 21.332031h-37.332031c-8.832031 0-16 7.167969-16 16s7.167969 16 16 16h37.332031c29.398438 0 53.332031-23.914062 53.332031-53.332031 0-28.265625-21.929687-48-53.332031-48-4.992188 0-21.332031-1.152344-21.332031-16 0-11.777344 9.578125-21.332031 21.332031-21.332031zm0 0"></path></svg></div><span>'+e.astrodj_exif.media_iso+"</span></div>"),t+a+'<div class="media-exif__wrapper block" style="text-align: left;"><div class="media-exif__content"><div class="media-exif__content--item media-exif__content--item-camera"><div class="media-exif__content--icon"><div class="icon"><svg id="icon-camera-dslr" viewBox="0 0 510.338 510.338"><g><path d="M246.825,97.303c-4.142,0-7.5,3.358-7.5,7.5s3.358,7.5,7.5,7.5h116.312c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5   H246.825z"></path><path d="M510.338,196.338c-0.866-23.259-16.279-43.535-37.979-51.837c-13.372-7.415-24.87-17.327-34.183-29.464   c-2.521-3.286-7.229-3.907-10.516-1.385c-3.286,2.521-3.906,7.229-1.385,10.516c10.594,13.809,23.681,25.079,38.902,33.503   c0.424,0.264,0.88,0.489,1.364,0.668c16.32,6.038,27.284,21.172,27.931,38.557l0.58,15.551h-71.079   c-26.333-43.225-73.914-72.148-128.124-72.148c-54.21,0-101.791,28.923-128.124,72.148H18.335l12.67-33.678   c3.895-10.352,12.187-18.178,22.749-21.471c0.159-0.05,0.316-0.105,0.47-0.164l106.954-29.857   c22.128-6.177,40.134-21.776,49.4-42.798c9.009-20.437,29.262-33.642,51.596-33.642h91.246c22.575,0,42.915,13.405,51.816,34.151   l5.233,12.198c1.633,3.806,6.043,5.569,9.85,3.935c3.807-1.633,5.568-6.044,3.936-9.85l-5.233-12.197   c-11.271-26.265-37.021-43.236-65.602-43.236h-91.246c-28.276,0-53.916,16.718-65.322,42.592   c-5.123,11.621-13.569,21.181-24.13,27.633l-1.627-5.786c-3.668-13.044-17.266-20.674-30.311-17.004l-37.044,10.417   C90.697,97.357,83.069,110.955,86.737,124l2.213,7.867l-39.571,11.046c-0.349,0.097-0.686,0.218-1.01,0.361   c-14.567,4.841-25.982,15.805-31.402,30.212L0.481,217.306C0.166,218.14,0,219.056,0,219.947v162.696c0,4.142,3.358,7.5,7.5,7.5   c4.142,0,7.5-3.358,7.5-7.5V227.447h80.123v232.054H37.5c-12.407,0-22.5-10.093-22.5-22.5v-24.358c0-4.142-3.358-7.5-7.5-7.5   c-4.142,0-7.5,3.358-7.5,7.5v24.358c0,20.678,16.822,37.5,37.5,37.5h435.338c20.678,0,37.5-16.822,37.5-37.5V196.338z    M101.177,119.939c-0.692-2.462-0.385-5.046,0.867-7.277c1.251-2.23,3.296-3.84,5.758-4.533l37.044-10.417   c5.084-1.431,10.382,1.543,11.811,6.625l2.237,7.953c-0.582,0.181-1.158,0.376-1.748,0.541l-53.748,15.004L101.177,119.939z    M442.861,260.81c14.233,2.217,25.16,14.556,25.16,29.402c0,14.845-10.928,27.185-25.16,29.401   c1.899-9.511,2.903-19.341,2.903-29.401C445.764,280.151,444.76,270.321,442.861,260.81z M295.851,155.298   c74.392,0,134.913,60.522,134.913,134.913s-60.521,134.913-134.913,134.913c-74.391,0-134.913-60.522-134.913-134.913   S221.459,155.298,295.851,155.298z M472.838,459.501H110.123V227.447h49.605c-8.844,19.104-13.79,40.366-13.79,62.765   c0,82.663,67.25,149.913,149.913,149.913c67.085,0,124.018-44.294,143.094-105.173c24.366-0.367,44.077-20.289,44.077-44.74   c0-24.452-19.711-44.374-44.077-44.741c-1.94-6.19-4.277-12.206-6.971-18.024h63.365v209.554   C495.338,449.408,485.244,459.501,472.838,459.501z"></path><path d="M295.851,407.836c64.858,0,117.625-52.767,117.625-117.625s-52.767-117.625-117.625-117.625   c-20.543,0-40.767,5.377-58.486,15.55c-3.592,2.063-4.832,6.646-2.77,10.239c2.063,3.592,6.646,4.833,10.239,2.77   c15.45-8.87,33.092-13.559,51.018-13.559c56.588,0,102.625,46.038,102.625,102.625s-46.037,102.625-102.625,102.625   c-56.587,0-102.625-46.038-102.625-102.625c0-27.412,10.675-53.184,30.058-72.567c2.929-2.929,2.929-7.678,0-10.607   c-2.929-2.929-7.678-2.929-10.606,0c-22.216,22.216-34.452,51.754-34.452,83.173C178.225,355.07,230.992,407.836,295.851,407.836z"></path><path d="M354.966,290.211c0-32.596-26.519-59.115-59.115-59.115c-32.597,0-59.115,26.519-59.115,59.115   s26.519,59.115,59.115,59.115C328.447,349.327,354.966,322.808,354.966,290.211z M251.735,290.211   c0-24.325,19.79-44.115,44.115-44.115c24.325,0,44.115,19.79,44.115,44.115c0,24.325-19.79,44.115-44.115,44.115   C271.525,334.327,251.735,314.537,251.735,290.211z"></path><path d="M60.987,181.466c0,13.547,11.022,24.568,24.568,24.568c13.547,0,24.568-11.021,24.568-24.568   c0-13.547-11.021-24.568-24.568-24.568C72.008,156.898,60.987,167.919,60.987,181.466z M95.123,181.466   c0,5.276-4.292,9.568-9.568,9.568c-5.276,0-9.568-4.293-9.568-9.568c0-5.276,4.292-9.568,9.568-9.568   C90.831,171.898,95.123,176.19,95.123,181.466z"></path></g></svg></div></div><div class="media-exif__content--inner">'+i+o+"</div></div>"+c+l+s+n+"</div></div>");d.parent(".image-exif__wrapper").find(".image-exif__container").html(r),d.prevAll(".lds-ring").hide()}).fail(function(){t&&console.log("REST Exif error")})})}localStorage.getItem("filter-check_order")&&""!==location.search&&j(".widget-filter__select .options .options-item[data-order="+localStorage.getItem("filter-check_order")+"]").trigger("click"),localStorage.getItem("filter-check_count")&&""!==location.search&&j(".widget-filter__count #count").html(localStorage.getItem("filter-check_count")),j(".widget-filter__btn.reset").on("click",function(){j(".widget-filter__grid--item").removeClass("active"),u=[],n&&console.log(u),j(".widget-filter__select .icon-wrap").removeClass("close"),j(".widget-filter__select .icon-close").attr("class","icon icon-angle-down").html(f),j(".widget-filter__select .selected span").attr("data-order","").text(m),j(".widget-filter__select .options-item").removeClass("select"),j(".widget-filter__btn:not(.close)").hide(),j(".widget-filter__count #count").html(_)}),j(".widget-filter__btn.success").on("click",function(){v.removeClass("opened"),j("body").removeClass("opened-filter");var e=j(".widget-filter__wrapper").data("cpt"),t=(n&&console.log(e),n&&console.log(u),j(".widget-filter__select .selected").find("span").attr("data-order"));n&&console.log(t),localStorage.setItem("filter-check_order",t),localStorage.setItem("filter-check_cat",u),localStorage.setItem("filter-check_count",j(".widget-filter__count #count").text());var a="/?"+[0<u.length?"terms="+u.join().replaceAll(",","%2C"):"",""!==t?"order="+t:""].filter(function(e){return""!==e}).join("&"),a=location.pathname.split("/").splice(1,1).toString()+a,a=location.href.replace(location.pathname+location.search,"/"+a),i=document.title,o=C(j(".nav-links .page-numbers:last-child").clone()),o=j(".load-more__btn").length||o?j(".load-more__btn").length?j(".load-more__btn").data("page"):o:1,c="/"+location.pathname.split("/").splice(1,1).toString()+"/",a=(history.replaceState({data_paged:o,data_archive:c,page_title:i},null,a),history.pushState({data_paged:o,data_archive:c,page_title:i},null,null),n&&console.log("Widget Filter: "+JSON.stringify(history.state)),0<u.length?'<span class="count"><span class="block round">'+j(".widget-filter__count #count").text()+"</span> Фото</span>":""),o=""!==t?'<span class="data">Дата съемки: <span class="block">'+("DESC"===t?"Сначала новые":"Сначала ранние")+"</span></span>":"",l=[];if(0<u.length)for(var s=0;s<u.length;s++)j(".widget-filter__grid--item").each(function(){j(this).attr("data-id")==u[s]&&l.push(j(this).find("span").text())});c=l.join('</span>, <span class="block">'),i=""!==c?'<span class="cat">Категории: <span class="block">'+c+"</span></span>":"",c='<div class="widget-filter__page--inner"><span class="title"><svg class="icon" viewBox="0 0 477.867 477.867"><g><g><path d="M460.8,221.867H185.31c-9.255-36.364-46.237-58.34-82.602-49.085c-24.116,6.138-42.947,24.969-49.085,49.085H17.067C7.641,221.867,0,229.508,0,238.934S7.641,256,17.067,256h36.557c9.255,36.364,46.237,58.34,82.602,49.085c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,221.867,460.8,221.867zM119.467,273.067c-18.851,0-34.133-15.282-34.133-34.133c0-18.851,15.282-34.133,34.133-34.133s34.133,15.282,34.133,34.133C153.6,257.785,138.318,273.067,119.467,273.067z"/></g></g><g><g><path d="M460.8,51.2h-53.623c-9.255-36.364-46.237-58.34-82.602-49.085C300.459,8.253,281.628,27.084,275.49,51.2H17.067C7.641,51.2,0,58.841,0,68.267s7.641,17.067,17.067,17.067H275.49c9.255,36.364,46.237,58.34,82.602,49.085c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,51.2,460.8,51.2z M341.334,102.4c-18.851,0-34.133-15.282-34.133-34.133s15.282-34.133,34.133-34.133s34.133,15.282,34.133,34.133S360.185,102.4,341.334,102.4z"/></g></g><g><g><path d="M460.8,392.534h-87.757c-9.255-36.364-46.237-58.34-82.602-49.085c-24.116,6.138-42.947,24.969-49.085,49.085H17.067C7.641,392.534,0,400.175,0,409.6s7.641,17.067,17.067,17.067h224.29c9.255,36.364,46.237,58.34,82.602,49.085c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,392.534,460.8,392.534zM307.2,443.734c-18.851,0-34.133-15.282-34.133-34.133s15.282-34.133,34.133-34.133c18.851,0,34.133,15.282,34.133,34.133S326.052,443.734,307.2,443.734z"/></g></g></svg></span>'+o+i+a+'<div class="reset">Сбросить <svg class="icon" viewBox="0 0 25 32"><path class="path1" d="M23.179 23.607q0 0.714-0.5 1.214l-2.429 2.429q-0.5 0.5-1.214 0.5t-1.214-0.5l-5.25-5.25-5.25 5.25q-0.5 0.5-1.214 0.5t-1.214-0.5l-2.429-2.429q-0.5-0.5-0.5-1.214t0.5-1.214l5.25-5.25-5.25-5.25q-0.5-0.5-0.5-1.214t0.5-1.214l2.429-2.429q0.5-0.5 1.214-0.5t1.214 0.5l5.25 5.25 5.25-5.25q0.5-0.5 1.214-0.5t1.214 0.5l2.429 2.429q0.5 0.5 0.5 1.214t-0.5 1.214l-5.25 5.25 5.25 5.25q0.5 0.5 0.5 1.214z"></path></svg></div></div>';j("#filter-init").remove(),j("#results").html(c),j.ajax({url:r,type:"post",data:{cpt:e,cat_IDs:u,order:t,action:"astrodj_widget_filter"},beforeSend:function(){j("body").addClass("placeholder__preloading"),j("#main").html(d),j("html, body").animate({scrollTop:0},100,"swing",function(){j(".main-navigation").removeClass("hide-menu")})},success:function(e){j("body").removeClass("placeholder__preloading"),j("#main").html(e),y(),n&&console.log("Ajax Widget Filter")},error:function(e,t,a){n&&console.log("error Widget Filter: "+a)}})}),j("#results, #filter-init").on("click",".reset",function(){j("#filter-init").remove(),j("#results").html(""),localStorage.removeItem("filter-check_order"),localStorage.removeItem("filter-check_cat"),localStorage.removeItem("filter-check_count");var e,t=j(".widget-filter__wrapper").data("cpt"),a=location.pathname.split("/").splice(1,1).toString(),a=location.href.replace(location.pathname+location.search,"/"+a+"/");history.replaceState(null,null,a),j(".widget-filter__btn.reset").trigger("click"),"portfolio"==t?e="/photo-gallery/":"stock"==t?e="/stock-photography/":"cats"==t&&(e="/cats-photography/"),j.ajax({url:r,type:"post",data:{paged:1,archive:e,action:"astrodj_archive_pagination"},beforeSend:function(){j("body").addClass("placeholder__preloading"),j("#main").html(d)},success:function(e){j("body").removeClass("placeholder__preloading"),j("#main").html(e),y(),n&&console.log("Ajax pagination")},error:function(e,t,a){n&&console.log("error Pagination: "+a)}})}),S()})}(jQuery);