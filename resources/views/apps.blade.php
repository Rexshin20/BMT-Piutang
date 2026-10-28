@extends('layouts.app')

@section('title', 'Apps')

@php
    $brandColors = [
        'telegram' => '#229ED9',
        'figma' => '#F24E1E',
        'trello' => '#0079BF',
        'slack' => '#611F69',
        'zoom' => '#2D8CFF',
        'stripe' => '#635BFF',
        'gmail' => '#EA4335',
        'skype' => '#00AFF0',
        'docker' => '#2496ED',
        'gitlab' => '#FC6D26',
        'discord' => '#5865F2',
        'whatsapp' => '#25D366',
    ];
@endphp

@section('content')
    <div>
        <h1 class="text-2xl font-bold tracking-tight">App Integrations</h1>
        <p class="text-muted-foreground">Here's a list of your apps for the integration!</p>
    </div>

    <div class="my-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-col gap-3 sm:flex-row">
            <div class="relative">
                <svg class="icon icon-sm absolute left-2.5 top-1/2 -translate-y-1/2 text-muted-foreground"><use href="#icon-search"></use></svg>
                <input type="text" data-apps-search class="input h-9 ps-8 sm:w-64" placeholder="Filter apps...">
            </div>
            <select data-apps-type class="select h-9 sm:w-36">
                <option value="all">All Apps</option>
                <option value="connected">Connected</option>
                <option value="notConnected">Not Connected</option>
            </select>
        </div>

        <div class="dropdown">
            <button type="button" class="btn btn-outline h-9 w-16 justify-center" data-dropdown-trigger aria-expanded="false" aria-label="Sort apps">
                <svg class="icon"><use href="#icon-sliders-horizontal"></use></svg>
            </button>
            <div data-dropdown class="dropdown-menu hidden w-44">
                <button type="button" class="dropdown-item" data-apps-sort-option="asc">
                    <svg class="icon"><use href="#icon-arrow-up-a-z"></use></svg> Ascending
                </button>
                <button type="button" class="dropdown-item" data-apps-sort-option="desc">
                    <svg class="icon"><use href="#icon-arrow-down-a-z"></use></svg> Descending
                </button>
            </div>
        </div>
    </div>

    <input type="hidden" data-apps-sort value="asc">

    <div class="-mx-4 border-t sm:mx-0"></div>

    <div data-apps-grid class="faded-bottom grid gap-4 pb-16 pt-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($apps as $app)
            <div data-app
                data-name="{{ strtolower($app['name']) }}"
                data-desc="{{ strtolower($app['desc']) }}"
                data-connected="{{ $app['connected'] ? 'true' : 'false' }}"
                class="no-scrollbar rounded-lg border bg-card p-4 transition-shadow hover:shadow-md">
                <div class="mb-8 flex items-center justify-between">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-muted p-2">
                        <svg class="icon icon-lg" style="color: {{ $brandColors[$app['brand']] ?? 'currentColor' }}"><use href="#brand-{{ $app['brand'] }}"></use></svg>
                    </div>
                    <button type="button" class="btn btn-outline btn-sm app-connect" data-connect-app data-connected="{{ $app['connected'] ? 'true' : 'false' }}">
                        {{ $app['connected'] ? 'Connected' : 'Connect' }}
                    </button>
                </div>
                <div>
                    <h2 class="mb-1 font-semibold">{{ $app['name'] }}</h2>
                    <p class="line-clamp-2 text-muted-foreground">{{ $app['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div data-apps-empty class="hidden py-16 text-center text-muted-foreground">No apps found.</div>
@endsection

@push('scripts')
    <script>
        (function () {
            var sortSelect = document.querySelector('[data-apps-sort]');
            document.querySelectorAll('[data-apps-sort-option]').forEach(function (button) {
                button.addEventListener('click', function () {
                    if (sortSelect) {
                        sortSelect.value = button.dataset.appsSortOption;
                        sortSelect.dispatchEvent(new Event('change'));
                    }
                    App.closeMenus();
                    App.showToast('Sorted ' + (button.dataset.appsSortOption === 'asc' ? 'ascending' : 'descending') + '.');
                });
            });
        })();
    </script>
@endpush
