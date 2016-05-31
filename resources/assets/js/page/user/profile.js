Vue.component('user-profile', {
    name: 'userProfile',
    el: function() {
        return '#user-profile'
    },
    data: function() {
        return {
            ajaxReady: true,
            editingContact: false,
            editingBio: false,
            showProfilePhotoMenu: false
        };
    },
    props: [],
    computed: {},
    methods: {
        togglePhotoMenu: function() {
            this.showProfilePhotoMenu = !this.showProfilePhotoMenu;
        },
        toggleEditMode: function(section) {
            this['editing' + section] = ! this['editing' + section];
            this.editingSection = (this.editingSection ===  section) ? '' : section;
        },
        updateProfile: function(section) {
            var self = this;
            vueClearValidationErrors(self);
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/user/profile',
                method: 'PUT',
                data: {
                    "name": self.user.name,
                    "email": self.user.email,
                    "phone": self.user.phone,
                    "bio": self.user.bio
                },
                success: function(data) {
                   // success
                    self.toggleEditMode(section);
                    flashNotify('success', 'Updated profile');
                   self.ajaxReady = true;
                },
                error: function(response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        },
        showFileSelecter: function() {
            $(this.$els.fileInput).click();
        },
        uploadProfilePhoto: function() {
            $(this.$els.profilePhotoForm).submit();
        },
        removePhoto: function() {
            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/user/profile/photo',
                method: 'DELETE',
                success: function(data) {
                   self.ajaxReady = true;
                    flashNotifyNextRequest('success', 'Removed profile photo');
                    location.reload();
                },
                error: function(response) {
                    console.log(response);
                    flashNotify('error', 'Could not remove photo');
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {
        
    },
    mixins: [userCompany],
    ready: function() {
        var self = this;
        $(document).click(function (event) {
            if (!$(event.target).closest('.profile-popup').length && !$(event.target).is('.profile-popup')) {
                self.showProfilePhotoMenu = false;
            }
        });
    }
});