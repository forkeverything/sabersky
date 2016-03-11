new Vue({
    name: 'Settings',
    el: '#system-settings',
    data: {
        ajaxReady: true,
        modalTitle: '',
        modalBody: '',
        modalMode: '',
        modalFunction: function(){},
        settingsView: 'permissions',
        navLinks: [
            {
                label: 'Rules',
                component: 'rules'
            },
            {
                label: 'Permissions',
                component: 'permissions'
            }
        ]
    },
    components: {
        permissions: permissionsComponent,
        rules: rulesComponent
    },
    methods: {
        changeView: function(view) {
            this.settingsView = view;
        }
    }
});
