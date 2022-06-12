<x-layouts.portal>
    <x-slot name="title">
        {{ trans_choice('general.payments', 1) }}
    </x-slot>

    <x-slot name="buttons">
        @stack('button_pdf_start')
        <x-link href="{{ route('portal.payments.pdf', $payment->id) }}" class="px-3 py-1.5 mb-3 sm:mb-0 bg-green hover:bg-green-700 text-white font-medium rounded-xl text-sm leading-6">
            {{ trans('general.download') }}
        </x-link>
        @stack('button_pdf_end')
        
        @stack('button_print_start')
        <x-link href="{{ route('portal.payments.print', $payment->id) }}" target="_blank" class="px-3 py-1.5 mb-3 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-medium leading-6">
            {{ trans('general.print') }}
        </x-link>
        @stack('button_print_end')
    </x-slot>

    <x-slot name="content">
        <div class="flex flex-col lg:flex-row my-10 lg:space-x-24 rtl:space-x-reverse space-y-12 lg:space-y-0">
            <div class="w-full lg:w-5/12 space-y-8">
                <div class="border-b-2 border-gray-200 pb-1">
                    <div class="relative w-full text-left group">
                        <span class="font-medium border-b border-transparent transition-all group-hover:border-black">
                            {{ trans('portal.received_date') }}
                        </span>
                    </div>

                    <div class="relative overflow-hidden transition-all duration-700">
                        <span class="text-sm">
                            <x-date date="{{ $payment->paid_at }}" />
                        </span>
                    </div>
                </div>

                <!--Status partials-->
                @if ($payment->document)
                <div class="border-b-2 border-gray-200 pb-1">
                    <div class="relative w-full ltr:text-left rtl:text-right">
                        <span class="font-medium">
                            {{ trans('portal.payments') }}
                        </span>
                    </div>

                    <span class="text-sm">
                        {{ trans('portal.payment_detail.description', ['date' => date($payment->paid_at), 'amount' => money($payment->amount, $payment->currency_code, true)]) }}
                    </span>
                </div>
                @endif
            </div>

            <div class="w-full lg:w-7/12">
                <x-transactions.show.template
                    type="payment"
                    :transaction="$payment"
                    transaction-template="{{ setting('payment.template', 'default') }}"
                    hide-payment-methods
                />
            </div>
        </div>
    </x-slot>

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush
</x-layouts.portal>