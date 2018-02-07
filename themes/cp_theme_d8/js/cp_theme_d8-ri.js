(function ($) {
  'use strict';
  Drupal.behaviors.cp_theme_d8 = {
    attach: function(context, settings) {

    }
  };
}(jQuery));

/**
 * This method assume the field cp_page_ingress:
 * Text (plain, long) Machine name: field_cp_page_ingress
 */
function handleIngressAndTitle() {
	jQuery('.page-title').css({'padding':'0 0 1rem 0', 'border-bottom':'0.1rem dashed #c7c8ca'});
	jQuery('.field--name-field-cp-page-ingress .field__label').hide();
	jQuery('.field--name-field-cp-page-ingress .field__item').css({'padding':'0 0 3rem 0', 'font-size':'1.4rem', 'font-weight':'bold', 'text-transform':'uppercase', 'text-align':'center'});
}

/**
* Home page
* Tweets, Teasing blog and Station map on home page.
* The following methods assume an embedded Tweet, a Teased CP Blog and a iframe (Google Map) in a respectively block on main content.
* The methods also depends of a CP Event's at right sidebar.
*/
function fixHome() {
	jQuery('#block-tweets').css({'background-color':'#f6f6f2', 'width':480, 'height':440});
	jQuery('#block-tweets .content').css({'height':440, 'overflow-y':'scroll'});
	jQuery('#block-viewteasedcpblog').css({'background-color':'#f6f6f2', 'height':440, 'border-left':'20px solid #f6f6f2', 'border-right':'20px solid #f6f6f2'});
	jQuery('#block-viewteasedcpblog .teaser').css({'width':'100%'});
	jQuery('#block-viewteasedcpblog .content h2').css({'padding-top':20});

	jQuery('#block-stationmap').css({'border-top':'5px solid #f6f6f2', 'background-color':'#f6f6f2'});
    jQuery('#block-stationmap h2').css({'margin-left':'2rem'});
    jQuery('#block-stationmap > .content').css({'border-left':'20px solid #f6f6f2', 'border-right':'20px solid #f6f6f2'});
    jQuery('#block-stationmap .to_bigger_map').css({'background-color':'#f6f6f2'});
    jQuery('#block-stationmap .content .text-formatted').css({'height':0});
    jQuery('#block-stationmap #map').css({'height':480});
}

function fixHomeMax() {
	jQuery('#content').css({'min-height':'auto'});

	jQuery('#block-listofteasedcpnews').css({'height':780});
	jQuery('#block-listofteasedcpevents').css({'height':1045});

	jQuery('#block-tweets').css({'position':'relative', 'top':5});

	jQuery('#block-viewteasedcpblog').css({'position':'relative', 'bottom':455, 'left':500});
	jQuery('#block-viewteasedcpblog').css({'width':444});

    jQuery('#block-stationmap').css({'position':'relative', 'bottom':415, 'width':940, 'height':565});

}

function fixHomeMin() {
	jQuery('#content').css({'min-height':2300});

	jQuery('#block-listofteasedcpnews').css({'height':'auto'});
	jQuery('#block-listofteasedcpevents').css({'height':'auto'});

	jQuery('#block-tweets').css({'position':'relative', 'top':0});

	jQuery('#block-viewteasedcpblog').css({'position':'relative', 'bottom':0, 'left':0});
	jQuery('#block-viewteasedcpblog').css({'width':480});

	jQuery('#block-stationmap').css({'position':'relative', 'bottom':-20, 'width':480, 'height':440});

}


(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');



ga('create', 'UA-53530911-2', 'auto');

ga('send', 'pageview');
