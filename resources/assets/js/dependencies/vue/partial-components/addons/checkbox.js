Vue.component('checkbox', {
    name: 'styledCheckbox',
    template: '<div class="checkbox-component">'+
    '<div class="checkbox styled" :class="{' +
    "'with-label': label," +
    "'checked': model" +
    '}">' +
    '<label>' +
    '<i class="fa fa-check-square-o checked"></i>' +
    '<i class="fa fa-square-o empty"></i>' +
    '<input class="clickable hidden" type="checkbox" @change="callFunction" :checked="model">' +
    '</label>' +
    '</div>' +
    '<h4 v-if="label" class="no-wrap clickable checkbox-label" @click="callFunction">{{{ label }}}</h4>' +
    '</div>',
    props: ['model', 'label', 'change-function', 'function-params'],
    methods: {
        callFunction: function() {

            this.changeFunction.apply(this.changeFunction, this.functionParams);

            /*
             We wrap our function call because Vue doesn't let us pass parameters with our
             functions so we have to pass it in separately in functionParams (array)
             */

        }
    }
});