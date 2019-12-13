@push('stylesheet')
    <link rel="stylesheet" href="/assets/vendor/x-spreadsheet/xspreadsheet.css">
    <style type="text/css">
        #x-spreadsheet {
            border: 1px dashed #159e92;
            margin-left: 10px;
        }

        .wz-spreadsheet-mode-clipboard {
            border: 1px solid #159e92!important;
        }

        .x-spreadsheet-overlayer {
            display: none;
        }

        .wz-spreadsheet-control {
            padding-right: 5px;
        }

    </style>
@endpush

@push('script')
    <script src="/assets/vendor/x-spreadsheet/xspreadsheet.js"></script>
    <script>
        $(function () {
            var savedContent = $('#x-spreadsheet-content').val();
            if (savedContent === '') {
                savedContent = "{}";
            }

            var sheetData = {};
            var tableHeight = 0;
            var tableWidth = 0;

            try {
                sheetData = JSON.parse(savedContent);
                tableHeight = (function (sheetData) {
                    var customHeightCount = 0;
                    var height = 0;

                    for (var i in sheetData.rows) {
                        if (!/^\d+$/.test(i)) {
                            continue;
                        }

                        if (sheetData.rows[i]["height"] !== undefined) {
                            customHeightCount++;
                            height += sheetData.rows[i]["height"];
                        }
                    }


                    for (var i = 0; i < sheetData.rows.len - customHeightCount; i++) {
                        height += 25;
                    }

                    return height + 46;
                })(sheetData);

                tableWidth = (function (sheetData) {
                    var width = 0;
                    var customWidthCount = 0;

                    for (var i in sheetData.cols) {
                        if (!/^\d+$/.test(i)) {
                            continue;
                        }

                        if (sheetData.cols[i]["width"] === undefined) {
                            continue;
                        }

                        width += sheetData.cols[i]["width"];
                        customWidthCount++;
                    }


                    for (var i = 0; i < sheetData.cols.len - customWidthCount; i++) {
                        width += 100;
                    }

                    return width + 61;
                })(sheetData);
            } catch (e) {
                console.log(e);
            }

            var options = {
                showToolbar: false,
                showGrid: true,
                showContextmenu: false,
                view: {
                    height: () => {
                        return tableHeight;
                    },
                    width: () => {
                        var sheetSelector = $('#x-spreadsheet');
                        var boxWidth = sheetSelector.width();
                        var width = boxWidth > tableWidth ? tableWidth : boxWidth;
                        sheetSelector.width(width);
                        $('.wz-spreadsheet').width(width + 18);
                        return width;
                    },
                }
            };

            // x.spreadsheet.locale('zh-cn');
            var sheet = x.spreadsheet('#x-spreadsheet', options);
            sheet.loadData(sheetData);
            sheet.change(function (data) {
            });


            // 表格的只读模式设置
            $('.wz-spreadsheet .wz-spreadsheet-mode').on('click', function (e) {
                e.preventDefault();

                if ($(this).data('mode') === 'photo') {
                    $(this).children('i')
                        .removeClass('fa-clipboard')
                        .addClass('fa-photo');
                    $(this).data('mode', 'clipboard');
                    $(this).parents('div')
                        .find('.x-spreadsheet-overlayer')
                        .css('display', 'block');

                    $('#x-spreadsheet').addClass('wz-spreadsheet-mode-clipboard');
                } else {
                    $(this).children('i')
                        .removeClass('fa-photo')
                        .addClass('fa-clipboard');
                    $(this).data('mode', 'photo');
                    $(this).parents('div')
                        .find('.x-spreadsheet-overlayer')
                        .css('display', 'none');

                    $('#x-spreadsheet').removeClass('wz-spreadsheet-mode-clipboard');
                }
            });

        });
    </script>
@endpush