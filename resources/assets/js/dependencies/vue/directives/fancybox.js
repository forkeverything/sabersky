Vue.directive('fancybox', function() {
    // Init fancy box on elements that may be loaded dynamically using Vue
    $(this.el).fancybox();
});