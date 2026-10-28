@extends('layouts.app')

@section('title', 'Chats')

@section('main-class', 'app-content-fluid')

@section('hide-footer', true)

@section('content')
    <section class="flex h-full gap-6">
        {{-- Conversation list --}}
        <div class="flex w-full flex-col gap-2 sm:w-56 lg:w-72 2xl:w-80">
            <div class="sticky top-0 z-10 -mx-4 bg-background px-4 pb-3 sm:static sm:mx-0 sm:p-0">
                <div class="flex items-center justify-between py-2">
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold">Inbox</h1>
                        <svg class="icon icon-lg text-muted-foreground"><use href="#icon-messages-square"></use></svg>
                    </div>
                    <button type="button" class="btn btn-ghost btn-icon" data-dialog-open="new-chat-dialog" aria-label="Start a new conversation">
                        <svg class="icon icon-lg"><use href="#icon-pencil"></use></svg>
                    </button>
                </div>

                <label class="flex h-10 w-full items-center gap-2 rounded-md border border-border ps-2 focus-within:ring-1 focus-within:ring-ring">
                    <svg class="icon icon-sm text-muted-foreground"><use href="#icon-search"></use></svg>
                    <span class="sr-only">Search chats</span>
                    <input type="text" data-chat-search class="w-full flex-1 bg-transparent pe-2 text-sm outline-none" placeholder="Search chat...">
                </label>
            </div>

            <div data-chat-list class="min-h-0 flex-1 space-y-1 overflow-y-auto p-1">
                @foreach ($conversations as $conversation)
                    @php $lastMessage = $conversation['messages'][count($conversation['messages']) - 1]; @endphp
                    <button type="button" class="chat-list-item" data-chat-target="{{ $conversation['id'] }}" data-name="{{ strtolower($conversation['full_name']) }}">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-muted text-xs font-semibold">{{ $conversation['initials'] }}</span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate font-medium">{{ $conversation['full_name'] }}</span>
                            <span class="line-clamp-2 text-xs text-muted-foreground">
                                @if ($lastMessage['sender'] === 'You')
                                    You: {{ $lastMessage['message'] }}
                                @else
                                    {{ $lastMessage['message'] }}
                                @endif
                            </span>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Chat area --}}
        <div class="chat-right relative" data-chat-right>
            <div data-chat-empty class="flex flex-1 flex-col items-center justify-center gap-6 p-6 text-center">
                <div class="flex size-16 items-center justify-center rounded-full border-2 border-border">
                    <svg class="icon icon-xl"><use href="#icon-messages-square"></use></svg>
                </div>
                <div class="space-y-2">
                    <h2 class="text-xl font-semibold">Your messages</h2>
                    <p class="text-sm text-muted-foreground">Send a message to start a chat.</p>
                </div>
                <button type="button" class="btn btn-primary" data-dialog-open="new-chat-dialog">Send message</button>
            </div>

            @foreach ($conversations as $conversation)
                <div data-chat-panel="{{ $conversation['id'] }}" class="hidden min-h-0 flex-1 flex-col flex">
                    <div class="flex flex-none items-center justify-between border-b bg-card p-4">
                        <div class="flex items-center gap-3">
                            <button type="button" class="btn btn-ghost btn-icon -ms-2 sm:hidden" data-chat-back aria-label="Back to inbox">
                                <svg class="icon"><use href="#icon-arrow-left"></use></svg>
                            </button>
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-muted text-xs font-semibold lg:h-11 lg:w-11">{{ $conversation['initials'] }}</span>
                            <div>
                                <span class="block text-sm font-medium lg:text-base">{{ $conversation['full_name'] }}</span>
                                <span class="block max-w-32 truncate text-xs text-muted-foreground lg:max-w-none lg:text-sm">{{ $conversation['title'] }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 lg:gap-2">
                            <button type="button" class="btn btn-ghost btn-icon hidden rounded-full sm:inline-flex" aria-label="Start video call">
                                <svg class="icon icon-lg text-muted-foreground"><use href="#icon-video"></use></svg>
                            </button>
                            <button type="button" class="btn btn-ghost btn-icon hidden rounded-full sm:inline-flex" aria-label="Start voice call">
                                <svg class="icon icon-lg text-muted-foreground"><use href="#icon-phone"></use></svg>
                            </button>
                            <button type="button" class="btn btn-ghost btn-icon" aria-label="Conversation options">
                                <svg class="icon text-muted-foreground"><use href="#icon-ellipsis"></use></svg>
                            </button>
                        </div>
                    </div>

                    <div data-chat-messages class="flex min-h-0 flex-1 flex-col gap-2 overflow-y-auto px-4 py-4">
                        @php $currentDate = null; @endphp
                        @foreach ($conversation['messages'] as $message)
                            @if ($currentDate !== $message['date'])
                                @php $currentDate = $message['date']; @endphp
                                <div class="my-2 text-center text-xs text-muted-foreground">{{ $message['date'] }}</div>
                            @endif
                            <div class="chat-bubble {{ $message['sender'] === 'You' ? 'chat-bubble-out' : 'chat-bubble-in' }}">{{ $message['message'] }}<span class="chat-time">{{ $message['time'] }}</span></div>
                        @endforeach
                    </div>

                    <form data-chat-form class="flex w-full flex-none gap-2 border-t p-3">
                        <div class="flex flex-1 items-center gap-2 rounded-md border border-input bg-card px-2 py-1 focus-within:ring-1 focus-within:ring-ring">
                            <div class="flex items-center gap-1">
                                <button type="button" class="btn btn-ghost btn-icon h-8" aria-label="Attach">
                                    <svg class="icon text-muted-foreground"><use href="#icon-plus"></use></svg>
                                </button>
                                <button type="button" class="btn btn-ghost btn-icon hidden h-8 lg:inline-flex" aria-label="Add image">
                                    <svg class="icon text-muted-foreground"><use href="#icon-image-plus"></use></svg>
                                </button>
                                <button type="button" class="btn btn-ghost btn-icon hidden h-8 lg:inline-flex" aria-label="Attach file">
                                    <svg class="icon text-muted-foreground"><use href="#icon-paperclip"></use></svg>
                                </button>
                            </div>
                            <label class="flex-1">
                                <span class="sr-only">Message</span>
                                <input type="text" name="message" class="h-8 w-full bg-transparent outline-none" placeholder="Type your messages...">
                            </label>
                            <button type="submit" class="btn btn-ghost btn-icon hidden sm:inline-flex" aria-label="Send message">
                                <svg class="icon"><use href="#icon-send"></use></svg>
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary sm:hidden">
                            <svg class="icon"><use href="#icon-send"></use></svg> Send
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </section>

    {{-- New chat dialog --}}
    <div class="dialog" id="new-chat-dialog" role="dialog" aria-modal="true" aria-labelledby="new-chat-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel max-w-md">
            <button type="button" class="dialog-close" data-dialog-close aria-label="Close">
                <svg class="icon"><use href="#icon-x"></use></svg>
            </button>
            <div class="dialog-header">
                <h2 id="new-chat-title" class="dialog-title">New message</h2>
                <p class="dialog-description">Select a teammate to start a conversation.</p>
            </div>
            <div class="max-h-80 space-y-1 overflow-y-auto">
                @foreach ($conversations as $conversation)
                    <button type="button" class="chat-list-item" data-new-chat-user="{{ $conversation['id'] }}">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-muted text-xs font-semibold">{{ $conversation['initials'] }}</span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate font-medium">{{ $conversation['full_name'] }}</span>
                            <span class="block truncate text-xs text-muted-foreground">{{ $conversation['title'] }}</span>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>
@endsection
