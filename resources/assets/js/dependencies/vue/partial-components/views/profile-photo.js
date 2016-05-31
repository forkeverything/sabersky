Vue.component('profile-photo', {
    name: 'ProfilePhoto',
    template: '<div class="profile-photo" v-el:profile-photo>' +
    '<div class="image-container" v-if="user.photo.thumbnail_path">' +
    '<img :src="user.photo.thumbnail_path" alt="User Profile Image" class="img-circle">' +
    '</div>' +
    '<div class="initials" v-else>' +
    '<span>{{ initials }}</span>' +
    '</div>' +
    '</div>',
    data: function() {
        return {

        };
    },
    props: ['user'],
    computed: {
        initials: function() {
            if(! this.user.name) return;
            var names = this.user.name.split(' ');
            return names.map(function (name, index) {
                if(index === 0 || index === names.length - 1) return name.charAt(0);
            }).join('');
        }
    },
    methods: {
        resizeInitials: function() {
            var $el = $(this.$els.profilePhoto);
            var width = $el.width();
            $el.height(width);
            $el.children('.initials').css('font-size', width / 2);
        }
    },
    events: {

    },
    ready: function() {
        this.$nextTick(function() {
            this.resizeInitials();
        });
        $(window).on('resize', _.debounce(this.resizeInitials, 150));
    }
});