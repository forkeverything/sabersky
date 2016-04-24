Vue.component('vendors-add-new', {
    name: 'addNewVendor',
    el: function() {
        return '#vendors-add-new'
    },
    data: function() {
        return {
            navLinks: [ 'search', 'custom'],
            currentTab: 'search'
        };
    },
    props: [],
    computed: {

    },
    methods: {
        changeTab: function (tab) {
            this.currentTab = tab;
        }
    },
    events: {

    },
    ready: function() {

    }
});