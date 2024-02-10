<nav>
    <div class="navbar">
        <div class="navbar__container">
            <div class="navbar__logo"><span class="chill">Neon </span>Messenger</div>
            <ul class="navbar__list">
                @if (auth()->user())
                <li><a href="{{ route('account.update.form') }}" class="navbar__item">Account</a></li>
                <li><a href="{{ route('chat.index') }}" class="navbar__item">Chat</a></li>
                <form action="{{ route('account.logout') }}" method="POST">
                        @csrf
                        <li><div type="submit" class="navbar__logout">Logout</div></li>
                    </form>
                @else
                    <li><a href="{{ route('index') }}" class="navbar__item">Register</a></li>
                    <li><a href="{{ route('signInForm') }}" class="navbar__item">Sign In</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
