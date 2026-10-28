@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.min.js') }}"></script>
    <script>
        jQuery(function ($) {
            $('#{{ $tableId }}').DataTable({
                order: [[0, 'asc']],
                pageLength: 10,
                lengthMenu: [10, 20, 50, 100],
                autoWidth: false,
                language: {
                    search: 'Cari:',
                    searchPlaceholder: @json($searchPlaceholder ?? 'Cari data...'),
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    zeroRecords: 'Data tidak ditemukan',
                    emptyTable: @json($emptyMessage ?? 'Belum ada data'),
                    paginate: { first: '«', previous: '‹', next: '›', last: '»' },
                },
            });
        });
    </script>
@endpush
