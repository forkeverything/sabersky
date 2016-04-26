Vue.component('company-search-selecter', {
    name: 'companySearchSelecter',
    template: '<select class="company-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.company-search-selecter').selectize({
            valueField: 'id',
            searchField: ['name'],
            create: false,
            placeholder: 'Search by Company Name',
            render: {
                option: function (item, escape) {

                    var optionClass = 'class="option company-single-option ',
                        connectionSpan;

                    switch (item.connection) {
                        case 'pending':
                            optionClass += 'disabled"';
                            connectionSpan = '<span class="vendor-connection pending">pending</span>';
                            break;
                        case 'verified':
                            optionClass += 'disabled"';
                            connectionSpan = '<span class="vendor-connection verified">verified</span>';
                            break;
                        default:
                            optionClass += '"';
                            connectionSpan = '';
                    }


                    return '<div ' + optionClass +'>' +
                        '       <span class="name">' + escape(item.name) + '</span>' +
                        connectionSpan +
                        '   </div>'
                },
                item: function (item, escape) {

                    var selectedClass = 'class="company-selected ',
                        connectionSpan;

                    switch (item.connection) {
                        case 'pending':
                            selectedClass += 'disabled"';
                            connectionSpan = '<span class="connection pending"> <em>pending</em></span>';
                            break;
                        case 'verified':
                            selectedClass += 'disabled"';
                            connectionSpan = '<span class="connection verified"> <i class="fa fa-check"></i> <em>verified</em></span>';
                            break;
                        default:
                            selectedClass += '"';
                            connectionSpan = '';
                    }

                    return '<div ' + selectedClass + '>' +
                        '           <label>Selected Company</label>' +
                        '           <div class="name">' + escape(item.name) +
                        connectionSpan +
                        '           </div>' +
                        '           <span class="description">' + escape(item.description) + '</span>' +
                        '       </div>' +
                        '</div>'
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/company/search/' + encodeURIComponent(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function (value) {
                self.name = value;
            }
        });
    }
});