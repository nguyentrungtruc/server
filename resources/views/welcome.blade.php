<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dofuu Admin</title>
        <script>
           window.Laravel = <?php echo json_encode([
               'csrfToken' => csrf_token(),
                    ]); ?>
          </script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="app">
        </div>
         
        <script src="{{asset('js/app.js')}}"></script>
        {{-- <script src="http://maps.google.com/maps/api/js?key=AIzaSyD8YR1-ZSzJf1G38n6FX76EhQrnyK0rccE&language=vi&region=VN"></script> --}}
    </body>
</html>