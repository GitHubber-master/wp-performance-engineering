<?php
/**
 * Plugin Name: WordPress Frontend & UX Optimization Hacks
 * Description: A collection of JS/CSS fixes: tablet detector, broken image handler, SEO title translator, and dynamic CID URL updates.
 * Version: 1.1.0
 * Author: Aleksandrs Fahrutdinovs
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action('wp_head', function() {
    ?>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    
    <style>
        /* Hide duplicate search on Astra mobile headers */
        .ast-mobile-header-wrap .ast-search-menu-icon,
        .ast-mobile-header-wrap .ast-header-search-wrap,
        .ast-mobile-header-wrap .ast-search-icon { 
            display: none !important; 
        }
        /* Base font and Font Awesome 6 core fixes */
        body, .button { font-family: 'Inter', Arial, sans-serif; }
        .fa-solid, .fas { font-family: "Font Awesome 6 Free" !important; font-weight: 900; }
    </style>
    <?php
});

add_action('wp_footer', function() {
    ?>
    <script>
    (function($) {
        $(document).ready(function() {
            var windowWidth = $(window).width();
            var isTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);

            // 1. Tablet in Desktop Mode Detector
            if (isTouch && windowWidth >= 992) {
                $('.ast-search-menu-icon, .ast-header-search-wrap, .dgwt-wcas-search-wrapp').first().remove();
            }

            // 2. Broken Image Handler (Fall-back to placeholder)
            $('img').on('error', function(){
                $(this).attr('src', '/wp-content/uploads/no-photo.jpg');
            });

            // 3. Dynamic Individual CID Logic for Categories
            function applyExpertCids() {
                $(".product-categories li").each(function() {
                    var $li = $(this);
                    var match = $li.attr('class').match(/cat-item-(\d+)/);
                    if (match && match[1]) {
                        var $link = $li.find('> a');
                        var href = $link.attr('href');
                        if (href && !href.includes('#')) {
                            var url = new URL(href, window.location.origin);
                            url.searchParams.set('cid', match[1]);
                            $link.attr('href', url.toString());
                        }
                    }
                });
            }
            applyExpertCids();
        });
    })(jQuery);
    </script>
    <?php
}, 100);
