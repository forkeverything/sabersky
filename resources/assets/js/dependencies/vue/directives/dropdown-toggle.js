Vue.directive('dropdown-toggle', {
    twoWay: true,
    bind: function () {

        this.className = $(this.el).attr('class').replace(' ', '.');

        var selector = '.' + this.className + ' button';

        $(document).on('click.' + this.className , selector, function (e) {
            this.set(true);
            $(document).on('click.checkHideDropdown', function(event) {
                if (!$(event.target).closest('.dropdown-container').length && !$(event.target).is('.dropdown-container')) {
                    this.set(false);
                    $(document).off('click.checkHideDropdown');
                }
            }.bind(this))
        }.bind(this));
    },
    unbind: function () {
        $(document).off('click.' + this.className)
    }
});
