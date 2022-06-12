<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => trans_choice('general.currencies', 1)]) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans('general.title.new', ['type' => trans_choice('general.currencies', 1)]) }}"
        icon="paid"
        route="currencies.create"
    ></x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="currency" route="currencies.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('currencies.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <x-form.group.select name="code" label="{{ trans('currencies.code') }}" :options="$codes" change="onChangeCode" />

                        <x-form.group.text name="rate" label="{{ trans('currencies.rate') }}" @input="onChangeRate" />

                        <x-form.group.select name="precision" label="{{ trans('currencies.precision') }}" :options="$precisions" model="form.precision" />

                        <x-form.group.text name="symbol" label="{{ trans('currencies.symbol.symbol') }}" />

                        <x-form.group.select name="symbol_first" label="{{ trans('currencies.symbol.position') }}" :options="['1' => trans('currencies.symbol.before'), '0' => trans('currencies.symbol.after')]" not-required model="form.symbol_first" />

                        <x-form.group.text name="decimal_mark" label="{{ trans('currencies.decimal_mark') }}" />

                        <x-form.group.text name="thousands_separator" label="{{ trans('currencies.thousands_separator') }}" not-required />

                        <x-form.group.toggle name="default_currency" label="{{ trans('currencies.default') }}" :value="false" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="currencies.index" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="currencies" />
</x-layouts.admin>
