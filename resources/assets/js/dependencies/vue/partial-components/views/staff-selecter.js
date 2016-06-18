Vue.component('staff-selecter', {
    name: 'staffSelecter',
    template: '<select class="staff-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.staff-selecter').selectize({
            valueField: 'id',
            searchField: ['name', 'email'],
            create: false,
            placeholder: 'Name or email',
            render: {
                option: function(item, escape) {
                    // TODO ::: Add email info
                    return '<div class="single-name-option">' + escape(item.name) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-name">' + escape(item.name) + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/staff/search/' + encodeURI(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function(value) {
                self.name = value;
            }
        });
    }
});