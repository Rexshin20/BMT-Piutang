@extends('layouts.error')

@section('title', $code . ' - ' . $title)

@section('content')
    <div class="m-auto flex h-full w-full flex-col items-center justify-center gap-2 text-center">
        <h1 class="text-[7rem] font-bold leading-tight">{{ $code }}</h1>
        <span class="font-medium">{{ $title }}</span>
        <p class="max-w-md text-center text-muted-foreground">{{ $description }}</p>
        <div class="mt-6 flex flex-wrap justify-center gap-4">
            @if (in_array('back', $actions))
                <button type="button" class="btn btn-outline" onclick="window.history.back()">Go Back</button>
            @endif
            @if (in_array('home', $actions))
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Home</a>
            @endif
            @if (in_array('learn-more', $actions))
                <a href="{{ route('help.index') }}" class="btn btn-outline">Learn more</a>
            @endif
        </div>
    </div>
@endsection
