<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <title>Chat</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    @vite(['/resources/css/navbar.css', '/resources/css/register.css', '/resources/css/signIn.css', '/resources/css/chat.css', '/resources/css/sidebar.css', '/resources/css/main.css', 'resources/js/app.js'])
</head>

<body>
    <main>
        <div class="navbar">
            @include('components.navbar')
        </div>
        @if (auth()->user())
            @include('sidebar', ['chats' => $chats])
        @endif
        <div class="content">
            @yield('content')
        </div>
    </main>
    <footer></footer>
</body>

</html>
