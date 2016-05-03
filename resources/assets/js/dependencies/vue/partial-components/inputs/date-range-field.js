Vue.component('date-range-field', {
    name: 'dateRangeField',
    template: '<div class="date-range-field">'+
    '<input type="text" class="filter-datepicker" v-model="min | properDateModel">'+
    '<span class="dash">-</span>'+
    '<input type="text" class="filter-datepicker" v-model="max | properDateModel">' +
    '</div>',
    props: ['min', 'max']
});