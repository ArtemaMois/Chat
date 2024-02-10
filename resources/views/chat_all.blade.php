{{-- @extends('main')

@section('content')
    <div class="chat">
        <div class="chat__container">
            <h1 class="chat__header">Chat</h1>
            <div class="chat__form">
                <input type="text" name="body" class="chat__input">
                <button class="chat__submit">Send</button>
            </div>
            <div class="chat__list">
            </div>
        </div>
    </div>

    <script type="module">
        $(document).ready(function() {
            $.ajax({
                url: "/messages",
                type: "GET",
                success: function(data) {
                    data.forEach(message => {
                        let chat = document.querySelector('.chat__list');
                        let messageItem = document.createElement('div');
                        messageItem.classList.add('message');
                        console.log(typeof "{{ auth()->user()->id }}")
                        if (message.userId == Number("{{ auth()->user()->id }}")) {
                            messageItem.classList.add('myself');
                        }
                        let messageUser = document.createElement('p');
                        messageUser.classList.add('message__user');
                        messageUser.innerHTML = message.username;
                        let messageBody = document.createElement('p');
                        messageBody.classList.add('message__body');
                        messageBody.innerHTML = message.message;
                        let messageTime = document.createElement('p');
                        messageTime.classList.add('message__time');
                        messageTime.innerHTML = message.created_at;
                        messageItem.appendChild(messageUser);
                        messageItem.appendChild(messageBody);
                        messageItem.appendChild(messageTime);
                        chat.appendChild(messageItem);
                    })
                }
            })
        });
        $('.chat__submit').on('click', function() {
            let body = $('.chat__input').val();
            $('.chat__input').val('');
            $.ajax({
                url: "/chat",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "body": body,
                },
                success: function(message) {

                }
            });
        })
        Echo.channel(`chat`)
            .listen('MessageSent', (message) => {
                let chat = document.querySelector('.chat__list');
                let messageItem = document.createElement('div');
                messageItem.classList.add('message');
                if (message.message.userId == Number("{{ auth()->user()->id }}")) {
                    messageItem.classList.add('myself');
                }
                let messageUser = document.createElement('p');
                messageUser.classList.add('message__user');
                messageUser.innerHTML = message.message.username;
                let messageBody = document.createElement('p');
                messageBody.classList.add('message__body');
                messageBody.innerHTML = message.message.message;
                let messageTime = document.createElement('p');
                messageTime.classList.add('message__time');
                messageTime.innerHTML = message.message.created_at;
                messageItem.appendChild(messageUser);
                messageItem.appendChild(messageBody);
                messageItem.appendChild(messageTime);
                chat.insertBefore(messageItem, chat.firstChild);
            });
    </script>
@endsection --}}
