Vue.component('power-table', {
    name: 'powerTable',
    template: '<div class="table-responsive">' +
    '<table class="table table-hover table-sort power-table">' +
    '<thead>' +
    '<tr>' +
    '<template v-for="header in headers">' +
    '<th v-if="header.sort"' +
    '    @click="changeSort(header.sort)"' +
    '    class="clickable"' +
    '    :class="{' +
    "       'active': sortField === header.sort," +
    "       'asc'   : sortOrder === 1," +
    "       'desc'  : sortOrder === -1" +
    '    }"' +
    '>' +
    '{{ header.label }}' +
    '</th>' +
    '<th v-else>' +
    '{{ header.label }}' +
    '</th>' +
    '</template>' +
    '</tr>' +
    '</thead>' +
    '<tbody>' +
    '<template' +
    '   v-for="item in data | orderBy sortField sortOrder"' +
    '>' +
    '<tr>' +
    '<td v-for="header in headers"> {{ parseItemValue(header, item) }}</td>' +
    '</tr>' +
    '' +
    '</template>' +
    '</tbody>' +
    '' +
    '</table>' +
    '</div>',
    data: function() {
        return {
            sortField: '',
            sortOrder: 1
        };
    },
    props: [
        'headers',
        'data',
        'filter'    // TO DO ::: Hook up way to filter data
    ],
    computed: {

    },
    methods: {
        parseItemValue: function(header, item) {
            var value;
            _.forEach(header.path, function (path, key) {
                value = (key === 0) ? item[path] : value[path];
            });
            return value;
        }
    },
    events: {

    },
    ready: function() {

    }
});