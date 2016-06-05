@if(session()->has('flash_message'))
    <script>
        $(document).ready(function () {
            toastr.{{ session('flash_message.type') }}('{{ session('flash_message.message') }}', '{{ ucfirst(session('flash_message.type')) }}');
        });
    </script>
@endif

<script>
    // Global Helper Function to show msg
    function flashNotify(type, msg) {
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