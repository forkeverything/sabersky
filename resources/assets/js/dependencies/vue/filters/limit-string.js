Vue.filter('limitString', function (val, limit) {
    if (val) {
        var trimmedString = val.substring(0, limit);
        trimmedString = trimmedString.substr(0, Math.min(trimmedString.length, trimmedString.lastIndexOf(" "))) + '...';
        return trimmedString
    }

    return val;
});