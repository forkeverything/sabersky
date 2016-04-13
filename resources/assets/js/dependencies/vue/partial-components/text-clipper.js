Vue.component('text-clipper', {
    name: 'textClipper',
    template: '<div class="text-clipper"' +
    '               :class="{' +
    "                   'expanded': !clip" +
    '               }"' +
    '           >' +
    '               <div v-if="isClipped" class="clipped">' +
    '                   {{ text | limitString limit }}' +
    '                   <a class="btn-show-more-text" @click.prevent="unclip">' +
    '                       <span class="clickable">...</span>' +
    '                   </a>' +
    '               </div>' +
    '               <div v-else class="unclipped">' +
    '                   {{ text }}' +
    '               </div>' +
    '            </div>',
    data: function() {
        return {
            limit: 150,
            clip: true
        };
    },
    props: ['text'],
    computed: {
        isClipped: function() {
            return this.text.length > this.limit && this.clip;
        }
    },
    methods: {
        unclip: function() {
            // Set max-height dynamically - depending on amount of text
            $(this.$el).css('max-height', $(this.$el).height());
            // Playing it safe
            setTimeout(function() {
                this.clip = false;
            }.bind(this), 150);
        }
    },
    ready: function() {
        // If the data changes but we're still using the same Component instance
        this.$watch('text', function () {
            // Reset it - ie. clip text
            this.clip = true;
        });
    }
});