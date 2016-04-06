
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
//# sourceMappingURL=global.js.map
