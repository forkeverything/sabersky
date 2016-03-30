Vue.filter('percentage', {
    read: function(val) {
        return (val * 100);
    },
    write: function(val, oldVal){
        val = val.replace(/[^0-9.]/g, "");
        return val / 100;
    }
});