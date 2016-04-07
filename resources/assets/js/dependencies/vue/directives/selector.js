Vue.directive('selectpicker', {
    twoWay: true,
    bind: function () {
        $(this.el).addClass('bootstrap-select-el');

        $(this.el).selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-check'
        }).on("change", function (e) {
            this.set($(this.el).val());
        }.bind(this));

        $(this.el).on('option:loaded', function () {
            $(this.el).selectpicker('refresh')
        }.bind(this));
    },
    update: function (newVal, oldVal) {
        $(this.el).selectpicker('refresh').trigger("change");
    },
    unbind: function () {
        $(this.el).off().selectpicker('destroy');
    }
});

Vue.directive('selectoption', {
    twoWay: true,
    bind: function () {
        Vue.nextTick(function () {
            $('.bootstrap-select-el').trigger("option:loaded");
        });
    }
});