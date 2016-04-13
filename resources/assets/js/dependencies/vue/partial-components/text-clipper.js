Vue.component('text-clipper', {
    name: 'textClipper',
    template: '<div class="text-clipper"' +
    '               :class="{' +
    "                   'expanded': !clip" +
    '}">' +
    '<div v-if="clipped" class="clipped">' +
    '{{ text | limitString limit }}' +
    '<a class="btn-show-more-text" @click.prevent="unclip">' +
    '<span>...</span>' +
    '</a>' +
    '</div>' +
    '<div v-else class="unclipped">{{ text }}</div>' +
    '</div>',
    data: function() {
        return {
            limit: 150,
            clip: true
        };
    },
    props: ['text'],
    computed: {
        clipped: function() {
            return this.text.length > this.limit && this.clip;
        }
    },
    methods: {
        unclip: function() {
            $(this.$el).css('max-height', $(this.$el).height());
            setTimeout(function() {
                this.clip = false;
            }.bind(this), 150);
        }
    }
});