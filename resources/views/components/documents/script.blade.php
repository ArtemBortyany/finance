@push('scripts_start')
    @php
        $document_items = 'false';
        $document_app_env = env('APP_ENV');

        if ($items) {
            $document_items = json_encode($items);
        } else if (old('items')) {
            $document_items = json_encode(old('items'));
        }
    @endphp

    <script type="text/javascript">
        var document_items = {!! $document_items !!};
        var document_default_currency = '{{ $currency_code }}';
        var document_currencies = {!! $currencies !!};
        var document_taxes = {!! $taxes !!};
        var document_app_env = '{{ $document_app_env }}';
    </script>
@endpush

<x-script :alias="$alias" :folder="$folder" :file="$file" />
