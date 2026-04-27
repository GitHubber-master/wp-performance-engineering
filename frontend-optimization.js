/**
 * WordPress Frontend & UX Optimization Tools
 * Includes: Tablet detector, Broken image handler, and Dynamic CID URL updates.
 */

(function($) {
    $(document).ready(function() {
        var windowWidth = $(window).width();
        var isTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);

        // 1. Tablet in Desktop Mode Detector (Astra/Fibo Search Fix)
        if (isTouch && windowWidth >= 992) {
            $('.ast-search-menu-icon, .ast-header-search-wrap, .dgwt-wcas-search-wrapp').first().remove();
        }

        // 2. Broken Image Handler
        $('img').on('error', function(){
            $(this).attr('src', '/wp-content/uploads/no-photo.jpg');
        });

        // 3. Dynamic Individual CID Logic
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
        
        // Handling AJAX/Interval updates
        setInterval(applyExpertCids, 5000);
    });
})(jQuery);
