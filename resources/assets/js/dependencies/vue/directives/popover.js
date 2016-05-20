/**
 * Bootstrap popover
 */
Vue.directive('popover', function () {
    var self = this;
    $(self.el).popover({
        html: true,
        content: function() {
            return $(this).siblings('.popover-content').html();
        }
    });
});


