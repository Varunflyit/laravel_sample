<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Callback</title>
        <script>
            window.opener.postMessage({!! json_encode($response) !!}, "*"); // TODO: Change with frontend url
            window.close();
        </script>
    </head>
    <body class="antialiased">
    </body>
</html>
