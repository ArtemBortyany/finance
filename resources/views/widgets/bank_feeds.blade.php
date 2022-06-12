@if (! empty($module))
    {!! $module !!}
@else
    <div class="relative w-full lg:w-1/2 my-8 px-12">
        <div class="relative pb-2 my-4 lg:my-0 z-10">
            <div class="flex justify-between font-medium mb-2">
                <h2 class="text-black">
                    {{ trans('widgets.bank_feeds') }}
                </h2>
            </div>

            <span class="w-64 block mb-3 text-black-400 text-xs">{!! trans('widgets.description.bank_feeds') !!}</span>

            <a href="{{ $learn_more_url }}" target="_blank" class="font-light text-xs border-b transition-all hover:font-medium">
                {{ trans('modules.learn_more') }}
            </a>
        </div>

        <div class="relative flex justify-end -mt-28">
            <img src="https://assets.akaunting.com/software/admin/bank-feeds.png" alt="Bank Feeds" />
        </div>
    </div>
@endif
