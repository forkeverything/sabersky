$('.table-purchase-requests tbody tr').click(function () {
    window.document.location = $(this).data("href");
});