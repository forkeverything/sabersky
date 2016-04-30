Vue.component('user-projects-selecter', {
    name: 'userProjectsSelecter',
    template: '<select-picker :options="projects" ' +
    '               :name.sync="name"'+
    '               :placeholder="' + "'Pick a Project...'" + '">' +
    '           </select-picker>',
    data: function() {
        return {
            projects: []
        };
    },
    props: ['name'],
    ready: function() {
        var self = this;
        $.ajax({
            url: '/api/user/projects',
            method: 'GET',
            success: function (data) {
                // success
                self.projects = _.map(data, function (project) {
                    if (project.name) {
                        project.value = project.id;
                        project.label = strCapitalize(project.name);
                        return project;
                    }
                });
            },
            error: function (response) {
                console.log(response);
            }
        });
    }
});