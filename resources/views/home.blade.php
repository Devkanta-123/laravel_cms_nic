<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $website[0]->title }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <meta name="folder-path" content="{{ $folderPath }}">
    
    {{-- Dynamically load favicon --}}
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . $website[0]->logo) }}">

    <script>
        window.folderPath = "{{ $folderPath }}";
        console.log(website);
    </script>
</head>
<body>
    <div class="wrapper" id="app">
        <page-content />
    </div>
</body>
</html>
