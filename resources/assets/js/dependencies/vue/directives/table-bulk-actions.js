Vue.directive('table-bulk-actions', function () {

    var $this = $(this.el),
        $parent = $($(this.el).parents('tr'));


    function setWidthToParent() {

        var parentWidth = $parent.width();

        // Do we have a width? (els might not have loaded)
        if (parentWidth > 0) {
            $this.width(parentWidth);
        } else {
            setTimeout(function () {
                setWidthToParent();
            }, 200);
        }
    }

    $(window).on("resize.resizeBulkActionsDiv", _.debounce(setWidthToParent, 50, {
        leading: true
    }));

    setWidthToParent();
});