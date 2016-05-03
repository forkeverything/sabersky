Vue.component('state-selecter', {
    name: 'stateSelecter',
    template: '<select class="state-selecter"><option></option></select>',
    data: function () {
        return {};
    },
    props: ['name', 'listen', 'event', 'default'],
    computed: {},
    methods: {},
    ready: function () {
        var xhr,
            select_state,
            self = this,
            listenEvent = self.listen || 'selected-country';

        $select_state = $(self.$el).selectize({
            valueField: 'name',
            labelField: 'name',
            searchField: ['name'],
            placeholder: 'State',
            create: true,
            onChange: function (value) {
                self.name = value;
                var selectedEventName = self.event || 'selected-state';
                vueEventBus.$emit(selectedEventName);
            }
        });

        select_state = $select_state[0].selectize;

        window.select_state = $select_state[0].selectize;


        vueEventBus.$on(listenEvent, function (value) {
            select_state.disable();
            select_state.clearOptions();
            select_state.load(function (callback) {
                // Jump queue
                if (!_.isEmpty(xhr) && xhr.readyState != 4) xhr.abort();
                // Fire req
                xhr = $.ajax({
                    url: '/countries/' + value + '/states',
                    success: function (results) {
                        select_state.enable();
                        callback(results);
                    },
                    error: function () {
                        callback();
                    }
                })
            });
        });

        self.$watch('default', function (state) {
            select_state.createItem(state);
        });
    }
});