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
                label: 'Permissions',
                component: 'permissions'
            },
            {
                label: 'Rules',
                component: 'rules'
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
