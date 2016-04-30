Vue.component('team-member-selecter', {
    name: 'teamMemberSelecter',
    template: '<select class="team-member-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.team-member-search-selecter').selectize({
            valueField: 'id',
            searchField: 'name',
            create: false,
            placeholder: 'Search for Team Member',
            render: {
                option: function(item, escape) {
                    return '<div class="single-name-option">' + escape(item.name) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-name">' + escape(item.name) + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/team/members/search/' + encodeURI(query),
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