<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Account</title>
    @vite(['/resources/css/account.css', '/resources/css/navbar.css', '/resources/js/avatar_preview.js'])
</head>

<body>
    <main>
        @include('components.navbar')
        <div class="account">
            <div class="account__avatar">
                <div class="account__avatar-img">
                    @empty($avatar->path)
                        <img src="{{ asset('/storage/images/default_avatar.svg') }}" alt="Аватар">
                    @else
                        <img src="{{ asset('/storage/' . $avatar->path) }}" alt="Аватар">
                        @endif
                    </div>
                    <div class="account__upload-form">
                        <div class="account__avatar-container">
                            <form action="{{ route('account.avatar') }}" method="POST" class="account__avatar-form"
                                enctype="multipart/form-data">
                                @csrf
                                <label for="avatar" class="account__avatar-label">
                                    <div class="account__avatar-label-img">
                                        <img src="{{ asset('storage/images/white_account.svg') }}" alt="">
                                    </div>
                                    <div class="account__avatar-label-text">Новый аватар</div>
                                </label>
                                <input type="file" name="image" id="avatar" class="account__avatar-input">
                                <div class="account__avatar-name invisible"></div>
                                <div type="submit" class="account__avatar-delete invisible">
                                    <img src="{{ asset('/storage/images/white-closecross.svg') }}" alt="">
                                </div>
                                <input type="hidden" value="1" name="is_main">
                                <button type="submit" class="account__avatar-submit invisible">Добавить</button>
                            </form>
                        </div>
                        <div class="account__image-container">
                            <form action="{{ route('account.avatar') }}" method="POST" class="account__image-form"
                                enctype="multipart/form-data">
                                @csrf
                                <label for="image" class="account__image-label">
                                    <div class="account__image-label-img">
                                        <img src="{{ asset('storage/images/white_plus.svg') }}" alt="">
                                    </div>
                                    <div class="account__image-label-text">Новая фотография</div>
                                </label>
                                <input type="file" name="image" id="image" class="account__image-input">
                                <div class="account__image-name invisible"></div>
                                <div class="account__image-delete invisible">
                                    <img src="{{ asset('/storage/images/white-closecross.svg') }}" alt="">
                                </div>
                                <input type="hidden" value="0" name="is_main">
                                <button type="submit" class="account__image-submit invisible">Добавить</button>
                            </form>
                        </div>
                    </div>
                    <div class="account__images">
                        <div class="account__images-list">
                            @foreach ($images as $image)
                                <div class="account__image">
                                    <img src="{{ asset('/storage/' . $image->path) }}" alt="Картинка">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="account__form">
                    <div class="account__container">
                        <label class="account__label">Email</label>
                        <input type="email" class="account__input" id="email" value="{{ $user->email }}">
                    </div>
                    <div class="account__container">
                        <label class="account__label">Username</label>
                        <input type="text" class="account__input" id="name" value="{{ $user->name }}">
                    </div>
                    <div class="account__submit">
                        <div class="account__warning"></div>
                        <div class="account__button">Save</div>
                    </div>
                </div>
            </div>
            {{-- <div class="avatar__preview">
            <div class="avatar__back">
                <div class="avatar__back-button">
                </div>
            </div>
            <div class="avatar__preview-container">
                <div class="avatar__preview-rectangle"></div>
                <div class="avatar__preview-img">
                </div>
            </div>
            <div class="avatar__submit">
                <div class="avatar__submit-text">Добавить</div>
            </div>
        </div> --}}
        </main>
        <script>
            function validateEmail(email) {
                var re =
                    /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
                return re.test(String(email).toLowerCase());
            }

            $('.account__button').bind('click', function(event) {
                event.preventDefault();
                $('.account__warning').removeClass('green');
                $('.account__warning').removeClass('red');
                $('#email').removeClass('invalid');
                let userName = $('#name').val();
                let userEmail = $('#email').val();
                console.log(userName, userEmail)
                if (validateEmail(userEmail)) {
                    $.ajax({
                        url: "/account/update",
                        type: 'PATCH',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            email: userEmail,
                            name: userName,
                        },
                        success: (response) => {
                            console.log(response);
                            $('#name').val(response['name']);
                            $('#email').val(response['email']);
                        }
                    })
                    $('.account__warning').addClass('green');
                    $('.account__warning').text("Account details updated");
                } else {
                    $('.account__warning').addClass("red");
                    if (userEmail.length > 15) {
                        $('.account__warning').text("Your email is not valid");
                    } else {
                        $('.account__warning').text(userEmail + " is not valid email");
                    }
                    $('#email').addClass('invalid');
                }
            });
        </script>
    </body>

    </html>
