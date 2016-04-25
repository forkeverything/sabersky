Vue.filter('chunk', function (array, length) {
    if(! array) return;
    var totalChunks = [];
    var chunkLength = parseInt(length, 10);

    if (chunkLength <= 0) {
        return array;
    }

    for (var i = 0; i < array.length; i += chunkLength) {
        totalChunks.push(array.slice(i, i + chunkLength));
    }


    return totalChunks;
});