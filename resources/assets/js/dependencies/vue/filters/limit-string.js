Vue.filter('limitString', function (val, limit) {
    if (val && val.length > limit) {
        var trimmedString = val.substring(0, limit);
        trimmedString = trimmedString.substr(0, Math.min(trimmedString.length, trimmedString.lastIndexOf(" ")));
        return trimmedString
    }

    return val;
});