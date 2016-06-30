Vue.component('landing', {
    name: 'LandingPage',
    el: function() {
        return '#landing'
    },
    data: function() {
        return {
        
        };
    },
    props: [],
    computed: {
        
    },
    methods: {
        clickedJoin: function() {
            vueEventBus.$emit('clicked-join-button');
        }
    },
    events: {
        
    },
    ready: function() {
        
    }
});