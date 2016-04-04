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
    "       'asc'   : sortAsc === true," +
    "       'desc'  : sortAsc === false," +
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
            sortAsc: true
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
                this.sortAsc = !this.sortAsc;
            } else {
                this.sortField = field;
                this.sortAsc = true;
            }
        }
    },
    events: {

    },
    ready: function() {

    }
});