<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{$website[0]->website_name}}</title>
        @vite(['resources/sass/app.scss','resources/js/app.js'])
        <meta name="folder-path" content="{{ $folderPath }}">
        <link rel="shortcut icon" type="image/x-icon" href="https://img.freepik.com/premium-vector/h-r-logo_168578-413.jpg?w=826">
        <script>
            window.folderPath = "{{ $folderPath }}";
        </script>
    </head>
    <body >
        {{-- <div id="app">
            <router-view></router-view>
        </div> --}}
        <div class="wrapper" id="app">
            
            <page-content/>
  
      </div>
          
    </body>
</html>
