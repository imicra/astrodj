!function(i){wp.customize("blogname",function(t){t.bind(function(t){i(".site-title a").text(t)})}),wp.customize("blogdescription",function(t){t.bind(function(t){i(".site-description").text(t)})}),wp.customize("header_textcolor",function(t){t.bind(function(t){"blank"===t?i(".site-title, .site-description").css({clip:"rect(1px, 1px, 1px, 1px)",position:"absolute"}):(i(".site-title, .site-description").css({clip:"auto",position:"relative"}),i(".site-title a, .site-description").css({color:t}))})}),wp.customize("frontpage_avatar_img",function(t){t.bind(function(t){i(".front-page__hero .avatar").find("img").attr("src",t)})}),wp.customize("frontpage_username",function(t){t.bind(function(t){i(".front-page__hero .avatar .user").text(t)})}),wp.customize("portfolio_header_img",function(t){t.bind(function(t){i(".site-header__img").css("background-image","url("+t+")")})}),wp.customize("portfolio_title",function(t){t.bind(function(t){i(".archive-portfolio .archive-title, .archive-portfolio .site-title span").text(t)})}),wp.customize("portfolio_subtitle",function(t){t.bind(function(t){i(".archive-portfolio .site-description").text(t)})}),wp.customize("stock_title",function(t){t.bind(function(t){i(".archive-stock .site-title span, .archive-portfolio .archive-title").text(t)})}),wp.customize("stock_subtitle",function(t){t.bind(function(t){i(".archive-stock .site-description").text(t)})}),wp.customize("cats_title",function(t){t.bind(function(t){i(".archive-stock .site-title span, .archive-portfolio .archive-title").text(t)})}),wp.customize("cats_subtitle",function(t){t.bind(function(t){i(".archive-stock .site-description").text(t)})})}(jQuery);