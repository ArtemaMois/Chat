@if (auth()->user())
    <div class="sidebar">
        <div class="sidebar__search">
            <input type="text" name="name" class="sidebar__input" placeholder="Search users...">
            <button class="sidebar__submit">
                <img src="{{ asset('storage/images/icons8-поиск.svg') }}" class="sidebar__icon" alt="">
            </button>
        </div>
        <div class="sidebar__back invisible"><img src="{{ asset('/storage/images/icons8-стрелка-25.png') }}" alt="">
        </div>
        @if (auth()->check())
            <div class="sidebar__chats">
                @foreach ($chats as $chat)
                    @include('components.user_contact', ['chat' => $chat])
                @endforeach
            </div>
        @endif
        <div class="sidebar__contacts invisible">
        </div>
    </div>
    <script>
        $('.sidebar__submit').on('click', function(event) {
            event.preventDefault();

            console.log($('.sidebar__input').val())
            $.ajax({
                url: "/search",
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    username: $('.sidebar__input').val()
                },
                success: function(response) {
                    let contactsContainer = document.querySelector('.sidebar__contacts');
                    contactsContainer.classList.remove('invisible');
                    document.querySelector('.sidebar__chats').classList.add('invisible');
                    response.forEach(element => {
                        let userContainer = document.createElement('div');
                        userContainer.classList.add('user-searched');
                        userContainer.id = element.id;
                        let userName = document.createElement('p');
                        userName.classList.add('user-searched__name');
                        userName.innerHTML = element.name;
                        userName.id = element.id
                        let userButton = document.createElement('button');
                        userButton.classList.add('user-searched__button');
                        userButton.innerHTML = "Добавить чат";
                        document.querySelector('.sidebar__back').classList.remove('invisible');
                        userContainer.appendChild(userName);
                        userContainer.appendChild(userButton);
                        contactsContainer.appendChild(userContainer);
                        $('.user-searched').on('click', function(event) {
                            event.preventDefault();
                            let userId = $(this).attr('id');
                            console.log(userId)
                            $.ajax({
                                url: "chat",
                                type: "POST",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    user_id: Number(userId)
                                },
                                success: (response) => {
                                    console.log(response)
                                    userContainer.classList.add('added');
                                    userButton.innerHTML = "Добавлено";
                                    userButton.classList.add('green');
                                }
                            })
                        })
                    });
                }
            })
        })
        $('.sidebar__back').on('click', function(event) {
            event.preventDefault();
            document.querySelector('.sidebar__contacts').classList.add('invisible');
            document.querySelector('.sidebar__back').classList.add('invisible');
            document.querySelector('.sidebar__chats').classList.remove('invisible');
            document.querySelector('.sidebar__input').value = '';
        })
    </script>
@endif
