<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In</title>
    @vite(['/resources/css/navbar.css', '/resources/css/signIn.css'])
</head>

<body>
    <main>
        @include('components.navbar')
        <section>
            <div class="signin">
                <h1 class="signin__header">Sign In</h1>
                <form action="{{ route('signIn') }}" class="signin__form" method="POST">
                    @csrf
                    <input type="email" name="email" class="signin__input" placeholder="Email Address">
                    @error('email')
                        <div class="error__message">
                            {{ $message }}
                        </div>
                    @enderror
                    <input type="password" name="password" class="signin__input" placeholder="Password">
                    @error('password')
                        <div class="error__message">
                            {{ $message }}
                        </div>
                    @enderror
                    <button type="submit" class="signin__submit">Sign In</button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>
