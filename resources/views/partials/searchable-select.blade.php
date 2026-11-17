@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-theme.css') }}?v=2">
@endpush

@push('scripts')
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
    <script>
        jQuery(function ($) {
            $('.select-search').select2({
                language: {
                    noResults: function () { return 'Tidak ditemukan'; },
                    searching: function () { return 'Mencari...'; },
                },
            });
        });
    </script>
@endpush
