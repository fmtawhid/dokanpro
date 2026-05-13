<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Found - 404</title>
</head>
<style>
    body,
    html {
        height: 100%;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    img {
        max-width: 100%;
        max-height: 100%;
        display: block;
        margin: auto;
    }
</style>

<body>
    <div class="container">
        <img src="{{ asset('errors/404.svg') }}" alt="Centered Image">
    </div>
</body>

</html>
