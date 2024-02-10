<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    @vite(['resources/css/navbar.css', '/resources/css/navbar.css', '/resources/css/register.css'])
</head>

<body>
    <main>
        @include('components.navbar')
        <section>
            <div class="register">
                <h1 class="regiter__header">Register</h1>
                <form action="{{ route('register') }}" class="register__form" method="POST">
                    @csrf
                    <input type="text" name="name" class="register__input" placeholder="Username">
                    <input type="email" name="email" class="register__input" placeholder="Email Address">
                    <input type="password" name="password" class="register__input" placeholder="Password">
                    <button type="submit" class="register__submit">Register</button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>
