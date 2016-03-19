
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
//# sourceMappingURL=global.js.map
