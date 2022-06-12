<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.edit', ['type' => trans_choice('general.items', 1)]) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="item" method="PATCH" :route="['items.update', $item->id]" :model="$item">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('items.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.radio
                            name="type"
                            label="{{ trans_choice('general.types', 1) }}"
                            :options="[
                                'product' => trans_choice('general.products', 1),
                                'service' => trans_choice('general.services', 1)
                            ]"
                            checked="{{ $item->type }}"
                            @input="onType($event)"
                        />

                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <x-form.group.category type="item" not-required />

                        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('items.billing') }}" description="{{ trans('items.form_description.billing') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.checkbox name="sale_information" id="item-sale-information" :options="['sale' => trans('items.sale_information')]" @input="onInformation($event, 'sale')" form-group-class="sm:col-span-3" checkbox-class="sm:col-span-6" />

                        <x-form.group.checkbox name="purchase_information" id="item-purchase-information" :options="['sale' => trans('items.purchase_information')]" @input="onInformation($event, 'purchase')" form-group-class="sm:col-span-3" checkbox-class="sm:col-span-6" />

                        <x-form.group.text name="sale_price" label="{{ trans('items.sale_price') }}" v-bind:disabled="sale_information" />

                        <x-form.group.text name="purchase_price" label="{{ trans('items.purchase_price') }}" v-bind:disabled="purchase_information" />

                        <x-form.group.select multiple add-new name="tax_ids" label="{{ trans_choice('general.taxes', 1) }}" :options="$taxes" :selected="$item->tax_ids" not-required :path="route('modals.taxes.create')" :field="['key' => 'id', 'value' => 'title']" form-group-class="sm:col-span-3 el-select-tags-pl-38" />
                    </x-slot>
                </x-form.section>

                <x-form.group.switch name="enabled" label="{{ trans('general.enabled') }}" />

                @can('update-common-items')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="items.index" />
                    </x-slot>
                </x-form.section>
                @endcan
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="common" file="items" />
</x-layouts.admin>
