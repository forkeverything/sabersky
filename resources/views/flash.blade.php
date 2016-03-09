@if(session()->has('flash_message'))
    <script>
        $(document).ready(function () {
            noty({
                text: "{{ session('flash_message')['message'] }}",
                type: "{{ session('flash_message.type') }}",
                dismissQueue: false,
                layout: 'bottomCenter',
                theme: 'customTheme',
                timeout: '5500',
                killer: 'true',
                animation: {
                    open: 'animated fadeInUp',
                    close: 'animated fadeOutDown',
                    speed: '350'
                }
            });
        });
    </script>
@endif

<script>
    function flashNotify(type, msg) {
        noty({
            text: msg,
            type: type,
            dismissQueue: false,
            layout: 'bottomCenter',
            theme: 'customTheme',
            timeout: '5500',
            killer: 'true',
            animation: {
                open: 'animated fadeInUp',
                close: 'animated fadeOutDown',
                speed: '350'
            }
        });
    }
</script>