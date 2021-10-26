<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
<meta name="viewport" content="width=device-width"/>
<link rel="icon" href="{{ $config->get('app.icon', config('app.icon')) }}">
<title>@yield('title') | {{ $config->get('app.name', config('app.name')) }}</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" type="text/css"
      rel="stylesheet">
<link href="{{asset('vendor/halfmoon/css/halfmoon-variables.css')}}" rel="stylesheet"/>
<script src="https://kit.fontawesome.com/7bc3a55a74.js" crossorigin="anonymous"></script>
<script src="{{asset('js/app.js')}}"></script>
<style>
    p img {
        max-width: 100%;
    }
</style>
