@push('script')
<script>
    $(function () {
        $('[wz-doc-compare-submit]').on('click', function (e) {
            e.preventDefault();

            var compareUrl = '{{ route('doc:compare') }}';

            var doc1url = $(this).data('doc1');
            var doc2url = $(this).data('doc2');

            axios.all([
                axios.get(doc1url),
                axios.get(doc2url)
            ]).then(axios.spread(function (resp1, resp2) {

                var layerId = 'wz-frame-' + resp1.data.id + '-' + resp2.data.id;

                $.wz.dialogOpen(layerId, '@lang('document.document_differ')', function (iframeId) {
                    $.wz.dynamicFormSubmit(
                        'wz-compare-' + resp1.data.id + '-' + resp2.data.id,
                        'post',
                        compareUrl,
                        {
                            doc1: resp1.data.content,
                            doc2: resp2.data.content,
                            doc1title: '@lang('document.latest_document')',
                            doc2title: '@lang('document.history_document')',
                            noheader: 1
                        },
                        iframeId
                    );
                });
            }));
        });
    });
</script>
@endpush