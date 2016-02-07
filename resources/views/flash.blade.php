@if(session()->has('flash_message'))
    <script>
        noty({
            text        : '{{ session('flash_message')['message'] }}',
            type        : '{{ session('flash_message.type') }}',
            dismissQueue: false,
            layout      : 'bottomCenter',
            theme       : 'customTheme'
        });
    </script>
@endif

<script>
function flashNotify(type, msg) {
    noty({
        text        : msg,
        type        : type,
        dismissQueue: false,
        layout      : 'bottomCenter',
        theme       : 'customTheme'
    });
}
</script>