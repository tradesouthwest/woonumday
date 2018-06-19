jQuery.noConflict();
/* woonumday plugin */
        
jQuery(window).load(function() {
jQuery(".accordion h2").on('click', function() {
    jQuery('.accordion').addClass('open');
});
jQuery(".accordion-close").on('click', function() {
    jQuery(".accordion").removeClass('open');
});
})();
