@extends('main')

@section('content')
    <div class="chat invisible">
        <div class="chat__subscriber"></div>
        <div class="chat__container">
            <div class="chat__list"></div>
        </div>
        <div class="chat__form">
            <div class="chat__text">
                <input type="text" name="message" class="chat__input" placeholder="Input message...">
                <div class="chat__buttons">
                    <button class="chat__button" id="{{ auth()->user()->id }}"><img
                            src="{{ asset('/storage/images/white_send.svg') }}" alt="send">
                    </button>
                    <button class="chat__edit invisible">
                        <img src="{{ asset('/storage/images/white_galochka.png') }}" alt="">
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        $('.user-contact').click(function(event) {
            event.preventDefault()
            let userId = $(this).attr('id');
            let userContactsCollection = document.querySelectorAll('.user-contact');
            let userContacts = Array.from(userContactsCollection)
            for (let element of userContacts) {
                element.classList.remove('selected');
            };
            $(this).addClass('selected');
            $.ajax({
                url: "chat/" + userId,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: (response) => {
                    console.log(response);
                    let chat = document.querySelector('.chat');
                    chat.classList.remove('invisible');
                    document.querySelector('.chat__subscriber').innerHTML = response[0].name;
                    document.querySelector('.chat__subscriber').id = response[0].id;
                    document.querySelector('.chat').id = 'chat.' + response[1].id;
                    document.querySelector('.chat__button').id = response[1].id;
                    document.querySelector('.chat__list').innerHTML = '';
                    response[2].forEach(element => {
                        let messageContainer = document.createElement('div');
                        messageContainer.classList.add('message__container');
                        messageContainer.id = "message-" + element.id;
                        let messageItem = document.createElement('div');
                        messageItem.classList.add('message__item');
                        let messageBody = document.createElement('p');
                        messageBody.classList.add('message__body');
                        messageBody.innerHTML = element.message;
                        let messageOptions = document.createElement('div');
                        messageOptions.classList.add('message__options');
                        let messageEdit = document.createElement('div');
                        messageEdit.classList.add('message__edit');
                        let messageEditImg = document.createElement('img');
                        messageEditImg.src =
                            "{{ asset('/storage/images/icons8-карандаш-25.png') }}";
                        messageEditImg.alt = "Редактировать";
                        messageEdit.appendChild(messageEditImg);
                        let messageTime = document.createElement('p');
                        messageTime.classList.add('message__time');
                        messageTime.innerHTML = element.created_at;
                        messageTime.id = element.time;
                        messageItem.appendChild(messageBody);
                        messageItem.appendChild(messageTime);
                        let messageDelete = document.createElement('div');
                        messageDelete.classList.add('message__delete');
                        let messageDeleteImg = document.createElement('img');
                        messageDeleteImg.src =
                            "{{ asset('/storage/images/icons8-корзина-32.png') }}";
                        messageDeleteImg.alt = "Удалить сообщение";
                        messageDelete.appendChild(messageDeleteImg);
                        messageOptions.appendChild(messageDelete);
                        messageOptions.appendChild(messageEdit);
                        messageContainer.appendChild(messageItem);
                        if (element.userId == Number("{{ auth()->user()->id }}")) {
                            messageContainer.classList.add('myself');
                            messageContainer.appendChild(messageOptions);
                        }
                        let messageList = document.querySelector('.chat__list');
                        if (messageList.lastChild) {
                            let time = messageList.lastChild.childNodes[0].childNodes[1].id;
                            let currenttime = element.time;
                            if (time !== currenttime) {
                                let dateSeparator = document.createElement('div')
                                dateSeparator.classList.add('chat__date');
                                dateSeparator.innerHTML = element.time;
                                messageList.appendChild(dateSeparator);
                            }
                        } else {
                            let dateSeparator = document.createElement('div')
                            dateSeparator.classList.add('chat__date');
                            dateSeparator.innerHTML = element.time;
                            messageList.appendChild(dateSeparator);
                        }
                        messageList.appendChild(messageContainer);
                    });
                    $('.message__delete').bind('click', function(event) {
                        event.preventDefault();
                        let messageId = $(this).parent().parent().attr('id').split('-')[1];
                        $.ajax({
                            url: '/message',
                            type: "DELETE",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                message_id: messageId
                            },
                            success: (response) => {
                                if (response['status'] == "success") {
                                    $(this).parent().parent().remove();
                                }
                            }
                        })

                    })
                    $('.message__edit').bind('click', function(event) {
                        let messagesContainer = document.querySelectorAll(
                            '.message__container');
                        event.preventDefault();
                        let msgContainer = $(this).parent().siblings('.message__item').children(
                            '.message__body');
                        console.log(msgContainer);
                        let editingContainer = $('.editing');
                        editingContainer.removeClass('editing');
                        $(this).parent().parent().addClass('editing');
                        $('.chat__input').val(msgContainer.text());
                        if (!$('.chat__input').hasClass('invisible')) {
                            $('.chat__button').addClass('invisible');
                        }
                        $('.chat__edit').removeClass('invisible');
                        let messageId = $(this).parent().parent().attr('id').split('-')[1];
                        document.querySelector('.chat__edit').id = messageId;

                        $('.chat__edit').bind('click', function(event) {
                            event.preventDefault();

                            let newMessageBody = $('.chat__input').val();
                            let messageId = $(this).attr('id');
                            console.log(newMessageBody, messageId);
                            $.ajax({
                                url: '/message',
                                type: "PATCH",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    message_id: Number(messageId),
                                    body: newMessageBody,
                                },
                                success: (response) => {
                                    $('.chat__input').val('');
                                    // let messageParent = document.getElementById('message.' + response['id']);
                                    let messageParent = $('#message-' +
                                        response['id']);
                                    messageParent.removeClass('editing');
                                    messageParent.children('.message__item')
                                        .children('.message__body').text(
                                            response['body']);
                                    $('.chat__edit').addClass('invisible');
                                    $('.chat__button').removeClass(
                                        'invisible');
                                }
                            })

                        })
                    });
                    let channel = $('.chat').attr('id');
                    Echo.private(channel)
                        .listen('MessageSent', (message) => {
                            console.log(message.message);
                            let messageContainer = document.createElement('div');
                            messageContainer.classList.add('message__container');
                            messageContainer.id = 'message-' + message.message.id;
                            let messageItem = document.createElement('div');
                            messageItem.classList.add('message__item');
                            let messageBody = document.createElement('p');
                            messageBody.classList.add('message__body');
                            messageBody.innerHTML = message.message.message;
                            let messageTime = document.createElement('p');
                            messageTime.classList.add('message__time');
                            messageTime.innerHTML = message.message.created_at;
                            messageItem.appendChild(messageBody);
                            messageItem.appendChild(messageTime);
                            let messageOptions = document.createElement('div');
                            messageOptions.classList.add('message__options');
                            let messageEdit = document.createElement('div');
                            messageEdit.classList.add('message__edit');
                            let messageEditImg = document.createElement('img');
                            messageEditImg.src =
                                "{{ asset('/storage/images/icons8-карандаш-25.png') }}";
                            messageEditImg.alt = "Редактировать";
                            messageEdit.appendChild(messageEditImg);
                            let messageDelete = document.createElement('div');
                            messageDelete.classList.add('message__delete');
                            let messageDeleteImg = document.createElement('img');
                            messageDeleteImg.src =
                                "{{ asset('/storage/images/icons8-корзина-32.png') }}";
                            messageDeleteImg.alt = "Удалить сообщение";
                            messageDelete.appendChild(messageDeleteImg);
                            messageOptions.appendChild(messageDelete);
                            messageOptions.appendChild(messageEdit);
                            messageContainer.appendChild(messageItem);
                            if (message.message.userId == Number("{{ auth()->user()->id }}")) {
                                messageContainer.classList.add('myself');
                                messageContainer.appendChild(messageOptions);
                            }
                            let messageList = document.querySelector('.chat__list');
                            messageList.appendChild(messageContainer);
                            $('.message__delete').bind('click', function(event) {
                                event.preventDefault();
                                let messageId = $(this).parent().parent().attr('id').split(
                                    '-')[1];
                                $.ajax({
                                    url: '/message',
                                    type: "DELETE",
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        message_id: messageId
                                    },
                                    success: (response) => {
                                        if (response['status'] == "success") {
                                            $(this).parent().parent().remove();
                                        }
                                    }
                                })

                            })
                            $('.message__edit').bind('click', function(event) {
                                let messagesContainer = document.querySelectorAll(
                                    '.message__container');
                                event.preventDefault();
                                let msgContainer = $(this).parent().siblings(
                                    '.message__item').children(
                                    '.message__body');
                                console.log(msgContainer);
                                let editingContainer = $('.editing');
                                editingContainer.removeClass('editing');
                                $(this).parent().parent().addClass('editing');
                                $('.chat__input').val(msgContainer.text());
                                if (!$('.chat__input').hasClass('invisible')) {
                                    $('.chat__button').addClass('invisible');
                                }
                                $('.chat__edit').removeClass('invisible');
                                let messageId = $(this).parent().parent().attr('id').split(
                                    '-')[1];
                                document.querySelector('.chat__edit').id = messageId;

                                $('.chat__edit').bind('click', function(event) {
                                    event.preventDefault();

                                    let newMessageBody = $('.chat__input').val();
                                    let messageId = $(this).attr('id');
                                    console.log(newMessageBody, messageId);
                                    $.ajax({
                                        url: '/message',
                                        type: "PATCH",
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            message_id: Number(messageId),
                                            body: newMessageBody,
                                        },
                                        success: (response) => {
                                            $('.chat__input').val('');
                                            // let messageParent = document.getElementById('message.' + response['id']);
                                            let messageParent = $(
                                                '#message-' +
                                                response['id']);
                                            messageParent.removeClass(
                                                'editing');
                                            messageParent.children(
                                                    '.message__item')
                                                .children(
                                                    '.message__body')
                                                .text(
                                                    response['body']);
                                            $('.chat__edit').addClass(
                                                'invisible');
                                            $('.chat__button')
                                                .removeClass(
                                                    'invisible');
                                        }
                                    })

                                })
                            });
                        })

                }
            })
        });
        $('.chat__button').bind('click', function(event) {
            event.preventDefault();

            let chatId = $('.chat__button').attr('id');
            let message = $('.chat__input').val();
            let userId = $('.chat__subscriber').attr('id');
            let fileSource = document.querySelector('#filesform');
            // let formData = new FormData(fileSource);
            // formData.append('uploads[]', fileSource.files);
            // console.log(formData);
            // let uploads = [];
            // for (let file in fileSource.files) {
            //     if (file == 'length') {
            //         break;
            //     }
            //     uploads.push(fileSource.files[file]);
            // }
            $('.chat__input').val('');
            $.ajax({
                url: "/message",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    body: message,
                    chat_id: chatId,
                    user_id: Number(userId),
                },
                success: function(response) {
                    console.log(response[0]['message'])
                    if (response[0]['message'].length > 15) {
                        let text = response[0]['message'].slice(0, 15);
                        $('[class=user-chat__last-message][id=' + userId + ']').text(text);
                    } else {
                        $('[class=user-chat__last-message][id=' + userId + ']').text(response[0][
                            'message'
                        ]);
                    }
                }
            })
        });
        // document.querySelector('.chat__file-input').addEventListener('change', function(event) {
        //     event.preventDefault();

        //     document.querySelector('.chat__files-container').classList.remove('invisible');
        //     let filesContainer = document.querySelector('.chat__file-selected');
        //     if (document.querySelector('.chat__files').childNodes.length > 10) {
        //         alert("Максимальное количество файлов 10");
        //     } else {
        //         for (let i = 0; i < this.files.length; i++) {
        //             // console.log(this.files[i]);
        //             let chatFile = document.createElement('div');
        //             chatFile.classList.add('chat__file');
        //             let chatFileText = document.createElement('div');
        //             chatFileText.classList.add('chat__file-text');
        //             chatFileText.innerHTML = this.files[i].name;
        //             let chatFileDelete = document.createElement('div');
        //             chatFileDelete.classList.add('chat__file-delete');
        //             chatFileDelete.id = this.files[i].name;
        //             let deleteImg = document.createElement('img');
        //             deleteImg.src = "{{ asset('images/white-closecross.svg') }}";
        //             deleteImg.alt = "Удалить файл";
        //             chatFileDelete.appendChild(deleteImg);
        //             chatFile.appendChild(chatFileText);
        //             chatFile.appendChild(chatFileDelete);
        //             document.querySelector('.chat__files').appendChild(chatFile);
        //             filesContainer.files[this.files[i].name] = this.files[i];
        //             chatFileDelete.addEventListener('click', function(event) {
        //                 event.preventDefault();

        //                 this.parentNode.remove();
        //                 delete filesContainer.files[this.id];
        //                 console.log(filesContainer.files);
        //                 let filesCount = 0;
        //                 for (let file in filesContainer.files) {
        //                     if (file == 'length') {
        //                         break;
        //                     }
        //                     filesCount++;
        //                 }
        //                 if (filesCount == 0) {
        //                     $('.chat__files-container').addClass('invisible');
        //                 }
        //             });
        //         }
        //         // for(let file in filesContainer.files){
        //         //     if(file == 'length'){
        //         //         break;
        //         //     }
        //         //     console.log(filesContainer.files[file]);
        //         // }
        //     }
        //     console.log(filesContainer.files.length);
        // })
    </script>
@endsection
