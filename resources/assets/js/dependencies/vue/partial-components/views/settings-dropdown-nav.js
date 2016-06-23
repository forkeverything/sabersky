Vue.component('settings-dropdown-nav', {
    name: 'settingsDropdownNav',
    template: '<div id="settings-mobile-nav">' +
    '<select class="themed-select visible-sm visible-xs" v-selectpicker="selectedLink">' +
    '<option  v-for="link in links" :selected="isSelected(link)" class="capitalize">{{ link }}</option>' +
    '</select>' +
    '</div>',
    data: function() {
        return {
            selectedLink: '',
            links: [
                'company', 'roles', 'purchasing'
            ]
        };
    },
    props: ['page'],
    computed: {},
    methods: {
        isSelected: function(link) {
            return link === this.page;
        }
    },
    events: {},
    ready: function() {
        this.$watch('selectedLink', function () {
            location.href = "/settings/" + this.selectedLink;
        }.bind(this));
    }
});