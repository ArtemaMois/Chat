<div class="user-contact" id="{{ $chat['chat_name'] }}">
    <div class="user-contact__name">{{ $chat['user']['name'] }}</div>
    <div class="user-chat__last-message" id="{{ $chat['user']['id'] }}">
        @if (!@empty($chat['last_message']))
            @if (Str::length($chat['last_message']['body']) > 15)
                {{ Str::limit(strip_tags($chat['last_message']['body']), 15) }}
            @else
                {{ $chat['last_message']['body'] }}
            @endif
        @endif
    </div>
</div>

{{-- {{ dd($chat) }} --}}
