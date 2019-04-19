@push('stylesheet')
    <link rel="stylesheet" href="/assets/vendor/x-spreadsheet/xspreadsheet.css">
    <style type="text/css">
        #x-spreadsheet {
            border: 1px dashed #159e92;
            margin-left: 10px;
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

        var options = {
            showToolbar: false,
            showGrid: true,
            showContextmenu: false,
            view: {
                // height: () => document.documentElement.clientHeight - 20,
                width: () => $('#x-spreadsheet').width(),
            }
        };

        // x.spreadsheet.locale('zh-cn');

        var sheet = x.spreadsheet('#x-spreadsheet', options);

        var sheetData = {};

        try {
            sheetData = JSON.parse(savedContent);
        } catch (e) {console.log(e);}

        sheet.loadData(sheetData);
        // sheet.change(function (data) {
        //     console.log(data);
        // });

    });
</script>
@endpush