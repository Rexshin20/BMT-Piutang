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
            function exportCellText(data, column, node, separator) {
                if (column === 0 && node) return node.textContent || data;
                if (!node) return data;

                var items = node.querySelectorAll('li');

                if (items.length) {
                    return Array.prototype.map.call(items, function (li) {
                        return (li.textContent || '').trim();
                    }).filter(Boolean).join(separator);
                }

                return (node.textContent || '').replace(/\s+/g, ' ').trim();
            }

            $('#{{ $tableId }}').DataTable({
                order: @json($order ?? [[0, 'asc']]),
                pageLength: 10,
                lengthMenu: [10, 20, 50, 100],
                autoWidth: false,
                drawCallback: function () {
                    this.api().rows({ order: 'applied', search: 'applied' }).nodes().each(function (node, index) {
                        var cell = node.querySelector('td:first-child');
                        if (cell) cell.textContent = index + 1;
                    });
                },
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
                            format: {
                                body: function (data, row, column, node) {
                                    return exportCellText(data, column, node, ', ');
                                },
                            },
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
                            format: {
                                body: function (data, row, column, node) {
                                    return exportCellText(data, column, node, '\n');
                                },
                            },
                        },
                        customize: function (doc) {
                            var widths = @json($pdfWidths ?? []);
                            var aligns = @json($pdfAlign ?? []);
                            var subtitle = @json($pdfSubtitle ?? ('BMT Cinde Barokah • Diunduh ' . now()->format('d/m/Y')));
                            var footerTitle = @json($exportTitle ?? 'Data');
                            var totalColumn = @json($pdfTotalColumn ?? null);
                            var columnOrder = @json($pdfColumnOrder ?? null);

                            doc.pageMargins = [28, 32, 28, 36];
                            doc.defaultStyle = { fontSize: 8.5, color: '#1f2937' };
                            doc.styles.title = {
                                fontSize: 14,
                                bold: true,
                                color: '#14532d',
                                alignment: 'left',
                                margin: [0, 0, 0, 2],
                            };
                            doc.styles.subtitle = {
                                fontSize: 8.5,
                                color: '#4b5563',
                                alignment: 'left',
                                margin: [0, 0, 0, 10],
                            };
                            doc.styles.tableHeader = {
                                bold: true,
                                fontSize: 8.5,
                                color: '#ffffff',
                                fillColor: '#15803d',
                            };

                            var tableItem = null;
                            doc.content.forEach(function (item) {
                                if (item.table) tableItem = item;
                            });
                            var table = tableItem.table;

                            doc.content.splice(doc.content.indexOf(tableItem), 0, { text: subtitle, style: 'subtitle' });

                            if (columnOrder && columnOrder.length) {
                                table.body = table.body.map(function (row) {
                                    return columnOrder.map(function (index) { return row[index]; });
                                });
                                if (aligns.length) aligns = columnOrder.map(function (index) { return aligns[index]; });
                                if (widths.length) widths = columnOrder.map(function (index) { return widths[index]; });
                                totalColumn = columnOrder.indexOf(totalColumn);
                            }

                            table.headerRows = 1;
                            table.dontBreakRows = true;
                            if (widths.length) table.widths = widths;
                            table.layout = {
                                hLineWidth: function () { return 0.5; },
                                vLineWidth: function () { return 0; },
                                hLineColor: function () { return '#d1fae5'; },
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
                                    if (rowIndex > 0 && rowIndex % 2 === 0) node.fillColor = '#f0fdf4';
                                    row[cellIndex] = node;
                                });
                            });

                            for (var nr = 1; nr < table.body.length; nr++) {
                                table.body[nr][0].text = String(nr);
                            }

                            if (totalColumn !== null && totalColumn >= 0 && table.body.length > 1) {
                                var totalValue = 0;
                                for (var r = 1; r < table.body.length; r++) {
                                    var cellText = table.body[r][totalColumn] ? table.body[r][totalColumn].text : '';
                                    totalValue += parseInt(String(cellText).replace(/\D/g, '') || '0', 10);
                                }
                                var totalRow = table.body[0].map(function (_, c) {
                                    return {
                                        text: '',
                                        alignment: aligns[c] || 'center',
                                        bold: true,
                                        color: '#ffffff',
                                        fillColor: '#15803d',
                                    };
                                });
                                var labelColumn = totalColumn > 0 ? totalColumn - 1 : totalColumn;
                                totalRow[labelColumn].text = 'Total';
                                totalRow[labelColumn].alignment = 'right';
                                totalRow[totalColumn].text = 'Rp ' + totalValue.toLocaleString('id-ID');
                                totalRow[totalColumn].alignment = 'right';
                                table.body.push(totalRow);
                            }

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
