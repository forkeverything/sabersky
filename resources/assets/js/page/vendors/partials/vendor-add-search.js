Vue.component('vendor-add-search', {
    name: 'vendorAddSearchCompany',
    el: function () {
        return '#vendor-add-search'
    },
    data: function () {
        return {
            ajaxReady: true,
            linkedCompanyID: ''
        };
    },
    props: ['currentTab'],
    computed: {},
    methods: {
        addCompanyAsNewVendor: function() {
            var self = this;
            vueClearValidationErrors(self);
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/link',
                method: 'POST',
                data: {
                    "linked_company_id": self.linkedCompanyID
                },
                success: function(data) {
                   // success
                    flashNotifyNextRequest('success', 'Sent request to link Company as a Vendor');
                    location.href = "/vendors";
                   self.ajaxReady = true;
                },
                error: function(response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {},
    ready: function () {
        var self = this;

        $('#vendor-search-company-selecter').selectize({
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
                self.linkedCompanyID = value;
            }
        });
    }
});