Vue.component('date-range-field', {
    name: 'dateRangeField',
    template: '<div class="date-range-field">' +
    '<div class="starting">' +
    '<label>starting</label>'+
    '<input type="text" class="filter-datepicker" v-model="min | properDateModel" placeholder="date">'+
    '</div>' +
    '<span class="dash">-</span>' +
    '<div class="ending">' +
    '<label>Ending</label>' +
    '<input type="text" class="filter-datepicker" v-model="max | properDateModel" placeholder="date">' +
    '</div>'+
    '</div>',
    props: ['min', 'max']
});