Vue.component('number-input', {
    name: 'numberInput',
    template: '<input type="text" :class="class" v-model="inputVal" :placeholder="placeholder">',
    props: ['model', 'placeholder', 'decimal', 'currency', 'class'],
    computed: {
        precision: function() {
            return this.decimal || 0;
        },
        inputVal: {
            get: function() {
                if(this.model === 0) return 0;
                if(! this.model) return;
                if(this.currency) return accounting.formatMoney(this.model, this.currency + ' ', this.precision);
                return this.model;
            },
            set: function(newVal) {
                // Acts like a 2 way filter
                var decimal = this.decimal || 0;
                this.model = accounting.toFixed(newVal, this.precision);
            }
        }
    }
});