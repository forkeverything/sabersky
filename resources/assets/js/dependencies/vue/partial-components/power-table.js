Vue.component('power-table', {
    name: 'powerTable',
    template: '<div class="table-responsive">' +
    '<table class="table power-table"' +
    '       :class="{' +
    "           'table-hover': hover" +
    '       }"' +
    '>' +
    '<thead>' +
    '<tr>' +
    '<template v-for="header in headers">' +
    '<th v-if="header.sort"' +
    '    @click="changeSort(header.sort)"' +
    '    :class="{' +
    "       'active': sortField === header.sort," +
    "       'asc'   : sortAsc === 1," +
    "       'desc'  : sortAsc === -1," +
    "       'clickable'  : sort" +
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
    '   v-for="item in data | orderBy sortField sortAsc"' +
    '>' +
    '<tr>' +
    '<td v-for="header in headers" ' +
    '    @click="clickEvent(item, field, parseItemValue(header, item))"' +
    '    :class="{' +
    "       'clickable': header.click === true" +
    '    }"' +
    '> {{ parseItemValue(header, item) }}</td>' +
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
            sortAsc: 1
        };
    },
    props: [
        'headers',
        'data',
        'filter',    // TO DO ::: Hook up way to filter data
        'sort',
        'hover'     // Set table-hover class
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
        },
        changeSort: function(field) {
            if(! this.sort) return;

            if(this.sortField === field) {
                this.sortAsc = (this.sortAsc === 1) ? -1 : 1;
            } else {
                this.sortField = field;
                this.sortAsc = 1;
            }
        },
        clickEvent: function(item, field, value) {
            this.$dispatch('click-table-cell', {
                item: item,
                field: field,
                value: value
            });
        }
    },
    events: {

    },
    ready: function() {

    }
});