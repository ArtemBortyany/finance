<body>
    <div class="max-w-7xl m-auto h-screen flex flex-col sm:flex-row items-center justify-center sm:justify-between">
        <div class="flex flex-col items-start gap-y-4 mb-10 sm:mb-0 sm:-mt-24">
            <span class="text-lg">
                {{ trans('maintenance.message') }}
            </span>
        </div>
        <img src="{{ asset('public/img/empty_pages/transactions.png') }}" alt="{{ trans('maintenance.message') }}" />
    </div>
</body>
