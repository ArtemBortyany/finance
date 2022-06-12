<x-form id="form-share">
    <div class="text-center">
        <div class="my-3">
            {{ trans('transactions.share.' . $transaction->type . '.show_link') }} :
        </div>

        <div class="my-3">
            <x-form.input.text
                name="share"
                value="{{ $signedUrl }}"
                ref="clone"
                @click="onCopyLink()"
                class="bg-gray-100 appearance-none border-2 border-gray-100 rounded w-full py-2 px-4 text-gray-700 leading-tight cursor-pointer focus:outline-none focus:ring-transparent focus:border-transparent"
            />
            <x-form.input.hidden name="hidden-share" value="{{ $signedUrl }}" />

            <div data-copied class="hidden h-10 items-center justify-center">
                <span
                    class="px-2.5 py-1 ltr:ml-2 rtl:mr-2 text-xs font-medium rounded-xl bg-orange-300 text-orange-700"
                >
                    {{ trans('general.copied') }}
                </span>
            </div>
        </div>

        <div class="my-3">
            {{ trans('transactions.share.' . $transaction->type . '.copy_link') }}
        </div>

        <div class="my-3">
            <x-link href="{{ $previewUrl }}" class="flex items-center justify-center gap-x-2 text-purple" override="class" target="_blank">
                {{ trans('general.preview_in_window') }} <x-icon icon="open_in_new" />
            </x-link>
        </div>
    </div>
</x-form>
