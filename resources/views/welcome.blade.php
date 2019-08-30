<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="google-site-verification" content="h56ecF9DExm4zZ5PSDVRaUxO-UEgqCasmWx_Bkem2Ho" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>Dofuu Admin</title>
        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyANKviRikWWh8f9fbGuzh68I2Gl4T8yEEE"></script>
    </head>
    <body>
        <div id="app"></div>
        <script src="{{asset('js/app.js')}}"></script>
    </body>
</html>
