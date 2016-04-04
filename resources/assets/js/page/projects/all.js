Vue.component('projects-all', {
    name: 'projectsAll',
    el: function () {
        return '#projects-all'
    },
    data: function () {
        return {
            projects: [],
            popupVisible: true,
            projectToDelete: {},
            ajaxReady: true
        };
    },
    props: [],
    computed: {},
    methods: {
        deleteProject: function (project) {
            this.projectToDelete = project;

            var settings = {
                title: 'Confirm Delete ' + project.name,
                body: 'Deleting a Project is permanent and cannot be reversed. Deleting a project will mean Team Members (staff) who are a part of the project will no longer receive notifications or perform actions for the Project. If you started the Project again, you will have to re-add all Team Members individually.',
                buttonText: 'Permanently Remove ' + project.name,
                buttonClass: 'btn btn-danger',
                callbackEventName: 'remove-project'
            };
            this.$broadcast('new-modal', settings);
        }
    },
    events: {
        'remove-project': function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/projects/' + self.projectToDelete.id,
                method: 'DELETE',
                success: function (data) {
                    // success
                    self.projects = _.reject(self.projects, self.projectToDelete);
                    flashNotify('success', 'Permanently Deleted ' + self.projectToDelete.name);
                    self.projectToDelete = {};
                    self.ajaxReady = true;
                },
                error: function (response) {
                    self.ajaxReady = true;
                }
            });
        }
    },
    ready: function () {

        // Fetch projects
        var self = this;
        $.ajax({
            url: '/api/projects',
            method: 'GET',
            success: function(data) {
               // success
               self.projects = data;
            },
            error: function(response) {
            }
        });

        // Popup Stuff
            // Bind click
            $(document).on('click', '.button-project-dropdown', function (e) {
                e.stopPropagation();

                $('.button-project-dropdown.active').removeClass('active');
                $(this).addClass('active');

                $('.project-popup').hide();
                $(this).next('.project-popup').show();
            });

            // To hide popup
            $(document).click(function (event) {
                if (!$(event.target).closest('.project-popup').length && !$(event.target).is('.project-popup')) {
                    $('.button-project-dropdown.active').removeClass('active');
                    $('.project-popup').hide();
                }
            });

    }
});