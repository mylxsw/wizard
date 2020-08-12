@push('stylesheet')
    <link rel="stylesheet" href="{{ cdn_resource('/assets/vendor/x-spreadsheet/xspreadsheet.css') }}">
    <style type="text/css">
        #x-spreadsheet {
            /*border: 1px dashed #159e92;*/
            margin-left: 10px;
        }

        .x-spreadsheet-icon-img.add {
            display: none;
        }
    </style>
@endpush

@push('script')
    <script src="{{ cdn_resource('/assets/vendor/x-spreadsheet/xspreadsheet.js') }}"></script>
    <script>
        $(function () {
            var savedContent = $('#x-spreadsheet-content').val();
            if (savedContent === '') {
                savedContent = "{}";
            }

            var sheetData = [];
            var tableHeight = 0;
            var tableWidth = 0;

            try {
                sheetData = JSON.parse(savedContent);
                tableHeight = (function (sheetData) {
                    var customHeightCount = 0;
                    var height = 0;

                    for (var i in sheetData[0].rows) {
                        if (!/^\d+$/.test(i)) {
                            continue;
                        }

                        if (sheetData[0].rows[i]["height"] !== undefined) {
                            customHeightCount++;
                            height += sheetData[0].rows[i]["height"];
                        }
                    }


                    for (var i = 0; i < sheetData[0].rows.len - customHeightCount; i++) {
                        height += 25;
                    }

                    return height + 46 + 35;
                })(sheetData);

                tableWidth = (function (sheetData) {
                    var width = 0;
                    var customWidthCount = 0;

                    for (var i in sheetData[0].cols) {
                        if (!/^\d+$/.test(i)) {
                            continue;
                        }

                        if (sheetData[0].cols[i]["width"] === undefined) {
                            continue;
                        }

                        width += sheetData[0].cols[i]["width"];
                        customWidthCount++;
                    }


                    for (var i = 0; i < sheetData[0].cols.len - customWidthCount; i++) {
                        width += 100;
                    }

                    var bottomToolLength = 61 + 87 + 66.5 * sheetData.length;
                    width = width + 61;
                    return width > bottomToolLength ? width : bottomToolLength;
                })(sheetData);
            } catch (e) {
                console.log(e);
            }

            var options = {
                mode: 'read',
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
        });
    </script>
@endpush