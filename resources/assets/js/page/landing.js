
// Initialize our event bus
var vueEventBus = new Vue();

// root Vue instance
new Vue({
    el: '#app-layout',
    data: {
        showNavDropdown: false
    },
    events: {},
    methods: {
        hideOverlays: function() {
            this.showNavDropdown = false;
        },
        toggleNavDropdown: function() {
            this.showNavDropdown = !this.showNavDropdown;
        }
    },
    ready: function () {
        $(document).ready(function () {
            var $ipad = $('.img-ipad');
            var $screenWrap = $('.screen-wrap');
            var position = parseInt($screenWrap.css('top'));
            var height = parseInt($screenWrap.css('height'));

            function setIpadDimensions() {

                var ipadHeight = $ipad.height();
                var ipadWidth = $ipad.width();

                $screenWrap.css({
                    top: 0.055 * ipadHeight + "px",
                    left: 0.225 * ipadWidth + "px",
                    height: 0.732 * ipadHeight + "px",
                    width: 0.555 * ipadWidth + "px"
                });

                position = 0.055 * ipadHeight;
                height = 0.732 * ipadHeight;

                $('#hero_div').css({
                    opacity: 1
                });

            }



            function moveScrollIpad() {
                var scrolled = $('#body-content').scrollTop();
                $screenWrap.css({
                    top: position - (0.2 * scrolled) + "px",
                    height: height + (0.2 * scrolled) + "px"
                });
            }

            $(window).load(function () {
                setIpadDimensions();
            });

            $(window).on('resize', setIpadDimensions);

            $('#body-content').on('scroll', moveScrollIpad);
        });
    }
});
