Vue.directive('dropdown-toggle', {
    twoWay: true,
    bind: function () {

        var self = this;

        self.className = $(self.el).attr('class').replace(' ', '.');

        var selector = '.' + self.className + ' button';

        $(document).on('click.' + self.className, selector, function (e) {
            e.stopPropagation();
            $(document).trigger('hideAllDropdowns');

            self.set(true);
            $(document).on('click.checkHideDropdown', function (event) {
                if (!$(event.target).closest('.dropdown-container').length && !$(event.target).is('.dropdown-container')) {
                    self.set(false);
                    $(document).off('click.checkHideDropdown');
                }
            })
        });

        $(document).on('hideAllDropdowns', function () {
            self.set(false);
        });
    },
    unbind: function () {
        $(document).off('click.' + this.className);
        $(document).off('hideAllDropdowns');
    }
});
