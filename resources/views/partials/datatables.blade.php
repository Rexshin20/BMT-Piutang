@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables/buttons/buttons.dataTables.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendor/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('vendor/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendor/pdfmake/vfs_fonts.js') }}"></script>
    <script>
        jQuery(function ($) {
            $('#{{ $tableId }}').DataTable({
                order: @json($order ?? [[0, 'asc']]),
                pageLength: 10,
                lengthMenu: [10, 20, 50, 100],
                autoWidth: false,
                layout: {
                    topStart: ['pageLength', 'buttons'],
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: @json($exportTitle ?? 'Data'),
                        exportOptions: {
                            columns: @json($exportColumns ?? ':visible'),
                        },
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        title: @json($exportTitle ?? 'Data'),
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: @json($exportColumns ?? ':visible'),
                        },
                        customize: function (doc) {
                            var widths = @json($pdfWidths ?? []);
                            var aligns = @json($pdfAlign ?? []);
                            var subtitle = @json($pdfSubtitle ?? ('BMT Cinde Barokah • Diunduh ' . now()->format('d/m/Y')));
                            var footerTitle = @json($exportTitle ?? 'Data');

                            doc.pageMargins = [28, 32, 28, 36];
                            doc.defaultStyle = { fontSize: 8.5, color: '#1f2937' };
                            doc.styles.title = {
                                fontSize: 14,
                                bold: true,
                                color: '#052e16',
                                alignment: 'left',
                                margin: [0, 0, 0, 2],
                            };
                            doc.styles.subtitle = {
                                fontSize: 8.5,
                                color: '#6b7280',
                                alignment: 'left',
                                margin: [0, 0, 0, 10],
                            };
                            doc.styles.tableHeader = {
                                bold: true,
                                fontSize: 8.5,
                                color: '#052e16',
                                fillColor: '#dcfce7',
                            };

                            var tableItem = null;
                            doc.content.forEach(function (item) {
                                if (item.table) tableItem = item;
                            });
                            var table = tableItem.table;

                            doc.content.splice(doc.content.indexOf(tableItem), 0, { text: subtitle, style: 'subtitle' });

                            table.headerRows = 1;
                            table.dontBreakRows = true;
                            if (widths.length) table.widths = widths;
                            table.layout = {
                                hLineWidth: function () { return 0.5; },
                                vLineWidth: function () { return 0; },
                                hLineColor: function () { return '#e5e7eb'; },
                                paddingLeft: function () { return 6; },
                                paddingRight: function () { return 6; },
                                paddingTop: function () { return 4; },
                                paddingBottom: function () { return 4; },
                            };

                            table.body.forEach(function (row, rowIndex) {
                                row.forEach(function (cell, cellIndex) {
                                    var value = cell;
                                    if (value !== null && typeof value === 'object') {
                                        value = value.text === undefined ? '' : value.text;
                                    }
                                    var node = {
                                        text: value === null || value === undefined ? '' : String(value),
                                        alignment: aligns[cellIndex] || 'center',
                                    };
                                    if (rowIndex === 0) node.style = 'tableHeader';
                                    if (rowIndex > 0 && rowIndex % 2 === 0) node.fillColor = '#f9fafb';
                                    row[cellIndex] = node;
                                });
                            });

                            doc.footer = function (currentPage, pageCount) {
                                return {
                                    margin: [28, 0, 28, 16],
                                    columns: [
                                        { text: footerTitle + ' • BMT Cinde Barokah', fontSize: 7.5, color: '#9ca3af' },
                                        { text: 'Halaman ' + currentPage + ' dari ' + pageCount, alignment: 'right', fontSize: 7.5, color: '#9ca3af' },
                                    ],
                                };
                            };
                        },
                    },
                ],
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
