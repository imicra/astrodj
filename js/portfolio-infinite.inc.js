!function(v){"use strict";v(document).ready(function(){var c=main_data.debug,p=portfolio_data.rest_url,f=portfolio_data.ajax_url,u="&astrodj_api_key="+main_data.api_key+"&astrodj_api_secret="+main_data.api_secret;function _(){var l=v("#loader_container"),n=l.offset().top-v(window).outerHeight();v(window).on("scroll",function(t){var i,a,e,o,s,r;function d(t){var a,i,e=t.featured_media;i=t.cmb2.astrodj_portfolio_meta_metabox.astrodj_radio_orientation,i='<article id="post-'+t.id+'" class="astrodj-portfolio '+i+'">'+(0===e?"":(e=(i=t._embedded["wp:featuredmedia"][0]).media_details.sizes.full.width,a=i.media_details.sizes.full.height,'<a href="'+t.link+'" data-hash-id="'+t.id+'"><figure class="astrodj-lqip" data-alt="'+i.alt_text+'" data-src="'+i.media_details.sizes.full.source_url+'" ><div class="aspect-ratio-fill" style="padding-bottom: 66.7%;width: 100%;height: 0;"></div><div class="astrodj-lqip__wrap"><img width="'+e+'" height="'+a+'" alt="" class="placeholder" src="'+i.media_details.sizes.astrodj_lqip.source_url+'"></div><div class="astrodj-lqip__wrap"><img width="'+e+'" height="'+a+'" class="lazy"></div><figcaption><h2 class="entry-title">'+t.title.rendered+"</h2></figcaption></figure></a>"))+"</article>",o.prev(".portfolio-content").append(i),o.attr("data-previous-id",t.previous_post_ID),_()}n>v(window).scrollTop()||(v(document).width()<576?(s=(o=l).attr("data-previous-id"),r=o.find("svg"),s=p+s+"?_embed=true"+u,r.show(),v.ajax({dataType:"json",url:s}).done(function(t){r.hide(),d(t),h()}).fail(function(){c&&console.log("error"),r.hide(),o.prepend('<article class="astrodj-post-navigation__end"><p>Снимков больше нет...</p></article>')})):(s=(i=l).attr("data-this-id"),a=i.data("cpt"),e=i.find("svg"),c&&console.log(s),v.ajax({url:f,type:"post",data:{post_id:s,post_type:a,action:"astrodj_portfolio_infinite"},beforeSend:function(){e.show()},success:function(t){var a;e.hide(),0==t?i.prepend('<article class="astrodj-post-navigation__end"><p>Снимков больше нет...</p></article>'):(i.prev(".portfolio-content").append(t),h(),t=v("#loader_wrapper"),a=t.find(".astrodj-portfolio").last().data("id"),t.find("#loader_container").attr("data-this-id",a),_()),c&&console.log("Ajax Portfolio Infinite")},error:function(t,a,i){c&&console.log("error Portfolio Infinite: "+i)}})),v(this).off(t))})}function h(){var o,t=[].slice.call(document.querySelectorAll("#loader_wrapper img.lazy"));"IntersectionObserver"in window&&(o=new IntersectionObserver(function(t,a){t.forEach(function(t){var a,i,e;t.isIntersecting&&(t=(a=t.target).closest(".astrodj-lqip__wrap").previousElementSibling,i=t.firstElementChild,e=a.closest("figure"),t.classList.add("bg"),setTimeout(function(){i.classList.add("visible"),a.alt=e.hasAttribute("data-alt")?e.dataset.alt:"",a.src=e.dataset.src,a.srcset=e.hasAttribute("data-srcset")?e.dataset.srcset:"",a.sizes=e.hasAttribute("data-sizes")?e.dataset.sizes:"",a.addEventListener("load",function(t){t.target.classList.add("visible")}),o.unobserve(a)},300))})},{threshold:.25}),t.forEach(function(t){o.observe(t)})),v("img.lazy").each(function(t){var a=parseInt(v(this).attr("width")),i=parseInt(v(this).attr("height")),i=i<a?100/(a/i):100*(i/a);v(this).parents(".astrodj-lqip__wrap").prevAll(".aspect-ratio-fill").attr("style","padding-bottom: "+i+"%;width: 100%;")})}_()})}(jQuery);