@if(session()->has('flash_message'))
    <script>
        $(document).ready(function () {
            {{--noty({--}}
                {{--text: "{{ session('flash_message')['message'] }}",--}}
                {{--type: "{{ session('flash_message.type') }}",--}}
                {{--dismissQueue: false,--}}
                {{--layout: 'bottomCenter',--}}
                {{--theme: 'customTheme',--}}
                {{--timeout: '5500',--}}
                {{--killer: 'true',--}}
                {{--animation: {--}}
                    {{--open: 'animated slideInUp',--}}
                    {{--close: 'animated slideOutDown',--}}
                    {{--speed: '350'--}}
                {{--}--}}
            {{--});--}}
           toastr.{{ session('flash_message.type') }}('{{ session('flash_message.message') }}', '{{ ucfirst(session('flash_message.type')) }}');
        });
    </script>
@endif

<script>
    // Global Helper Function to show msg
    function flashNotify(type, msg) {
//        noty({
//            text: msg,
//            type: type,
//            dismissQueue: false,
//            layout: 'bottomCenter',
//            theme: 'customTheme',
//            timeout: '5500',
//            killer: 'true',
//            animation: {
//                open: 'animated slideInUp',
//                close: 'animated slideOutDown',
//                speed: '350'
//            }
//        });
        if (arguments.length === 1) {
            msg = type;
            type = 'info';
        }
        toastr[type](msg, strCapitalize(type));
    }


    function readLocalFlash() {
        if (Cookies.get('ss_flash_type') && Cookies.get('ss_flash_message')) {
            flashNotify(Cookies.get('ss_flash_type'), Cookies.get('ss_flash_message'));
        }
        Cookies.remove('ss_flash_type');
        Cookies.remove('ss_flash_message');
    }

    $(document).ready(readLocalFlash);

    // Global func to set Flash msgs to Cookie
    function flashNotifyNextRequest(type, msg) {
        Cookies.set('ss_flash_type', type);
        Cookies.set('ss_flash_message', msg);
    }


</script>