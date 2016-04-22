$(document).ready(function () {
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        startDate: 'today',
        language: 'en'      // TODO ::: Change according to client Lang
    });

    $('.filter-datepicker').datepicker({
        format: "dd/mm/yyyy",
        language: 'en'      // TODO ::: Change according to client Lang
    });
});