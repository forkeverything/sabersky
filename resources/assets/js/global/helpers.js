
/**
 * Takes string and capitalizes the first letter
 * of each word.
 */
function strCapitalize(str) {
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

/**
 * Escapes html entities for a string to be inserted
 * into the DOM.
 *
 * @type {{&: string, <: string, >: string, ": string, ': string, /: string}}
 */
var entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;'
};

/**
 * Escapes a given string that has HTML elements.
 *
 * @param string
 * @returns {string}
 */
function escapeHtml(string) {
    return String(string).replace(/[&<>"'\/]/g, function (s) {
        return entityMap[s];
    });
}

/**
 * Takes an AJAX response and vue instance
 * and emits form errors to be caught by
 * 'form-errors' Vue Component.
 * 
 * @param response
 * @param vue
 */
function vueValidation(response, vue) {
    if(response.status === 422) {
        vue.$broadcast('new-errors', response.responseJSON);
    }
}

/**
 * Broadcasts clear errors event.
 *
 * @param vue
 */
function vueClearValidationErrors(vue) {
    vue.$broadcast('clear-errors');
}

/**
 * Takes an string and tells you if it's a valid email!
 *
 * @returns {boolean}
 * @param string
 */
function validateEmail(string) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(string);
}

/**
 * Returns whether given string is all
 * alphanumeric (no symbols).
 *
 * @returns {boolean}
 * @param string
 */
function alphaNumeric(string) {
    var re = /^[A-Za-z\d\s]+$/;
    return re.test(string);
}

/**
 * Retrieves the Query String Value by
 * Name
 * 
 * @param name
 * @param url
 * @returns {*}
 */
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)", "i"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

/**
 * Takes a 2 Strings (name, value) pair or an Object containing
 * several name-value pairs and updates the current query
 * and returns it.
 * 
 * @returns {string}
 */
function updateQueryString() {
    // Get and prep existing query so we can make changes to it
    var fullQuery = window.location.href.split('?')[1];         // into pairs
    var queryArray = fullQuery ? fullQuery.split('&') : [];     // into key-values
    var queryObj = {};                                          // empty object

    // Build up object
    queryArray.forEach(function (item) {
        var x = item.split('=');
        queryObj[x[0]] = x[1];
    });

    /**
     * Make Updates to query
     * TO DO ::: CHECK HERE
     */
    if (typeof arguments[0] === 'string' && arguments.length > 1) {
        // Only update single query name - set the new name and value
        queryObj[arguments[0]] = arguments[1];
    } else if(typeof arguments[0] === 'object'){
        // Received an object with key-value pairs of query names and value to update
        _.forEach(arguments[0], function (value, key) {
            if(value) {
                queryObj[key] = value;
            } else {
                delete queryObj[key];
            }
        });
    } else {
        // only received a key - delete from query
        delete queryObj[arguments[0]];
    }

    // Make new query to return
    var newQuery = '';
    // Go through object and add everything back as a string
    _.forEach(queryObj, function (value, name) {
        if(value.constructor === Array)  {
            value = _.map(value, function (i) { if(i) return encodeURIComponent(i); return i; }).join('+');
        } else {
            value = encodeURIComponent(value)
        }
        newQuery += name + '=' + value + '&';
    });
    // Finally - return our new string!
    return newQuery.substring(0, newQuery.length - 1);  // Trim last '&'
}

/**
 * When browser has pop-state (ie back / forward)
 * run this function to re-retrieve the data
 *
 * @param callback
 */
function onPopQuery(callback)
{
    window.onpopstate = function (e) {
        if (e.state) {
            callback(window.location.href.split('?')[1]);
        }
    }
}

/**
 * Takes a query string and if it is  different to
 * the current query string, it will update the
 * browsers state, so we can use nav buttons
 * 
 * @param query
 */
function pushStateIfDiffQuery(query) {
    if (query !== window.location.href.split('?')[1]) {
        window.history.pushState({}, "", '?' + query);
    }
}