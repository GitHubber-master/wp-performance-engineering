<?php
/**
 * Plugin Name: WooCommerce CID URL Handler
 * Description: Manages custom 'cid' parameters in URLs, ensures description visibility and updates category links via JS.
 * Version: 1.0.0
 * Author: Aleksandrs Fahrutdinovs
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Register custom query variable 'cid'
 */
add_filter('query_vars', function($vars) {
    $vars[] = 'cid';
    return $vars;
});

/**
 * 2. Force display category/product descriptions (CSS Fix)
 * Prevents theme scripts from hiding descriptions when 'cid' is present.
 */
add_action('wp_head', function() {
    ?>
    <style>
        .term-description, 
        .woocommerce-product-details__short-description, 
        .woocommerce-Tabs-panel--description { 
            display: block !important; 
            visibility: visible !important; 
            opacity: 1 !important; 
        }
    </style>
    <?php
});

/**
 * 3. Update Category Links via JavaScript
 * Appends 'cid' parameter to category links in sidebar and shop grids.
 */
add_action('wp_footer', function() {
    if ( is_product() ) {
        return;
    }
    ?>
    <script>
    jQuery(document).ready(function($) {
        function afaUpdateCategoryLinks() {
            // Sidebar categories
            $('.product-categories li').each(function() {
                var match = $(this).attr('class') ? $(this).attr('class').match(/cat-item-(\d+)/) : null;
                if (match && match[1]) {
                    var cid = match[1];
                    $(this).find('a').each(function() {
                        var href = $(this).attr('href');
                        if (href && !href.includes('cid=')) {
                            var sep = href.indexOf('?') !== -1 ? '&' : '?';
                            $(this).attr('href', href + sep + 'cid=' + cid);
                        }
                    });
                }
            });

            // Shop grid tiles
            $('.product-category.product').each(function() {
                var cid = $(this).find('img').attr('post-id');
                if (cid) {
                    var $a = $(this).find('a');
                    var href = $a.attr('href');
                    if (href && !href.includes('cid=')) {
                        var sep = href.indexOf('?') !== -1 ? '&' : '?';
                        $a.attr('href', href + sep + 'cid=' + cid);
                    }
                }
            });
        }

        // Run updates to catch theme AJAX loads
        afaUpdateCategoryLinks();
        setTimeout(afaUpdateCategoryLinks, 500);
        setTimeout(afaUpdateCategoryLinks, 1500);
    });
    </script>
    <?php
}, 100);
