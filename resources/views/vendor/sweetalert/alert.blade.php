@if (Session::has('alert.config'))
    <script type="module">
        @if (Session::has('alert.config'))
            let propertiesJSON = {!!Session::pull('alert.config')!!}
            window.Swal.fire(window.notification(propertiesJSON.title, propertiesJSON.icon));
        @endif
    </script>
@endif

