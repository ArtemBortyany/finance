<x-form id="form-item-column" method="PATCH" route="modals.documents.item-columns.update">
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <x-form.group.invoice-text
            name="item_name"
            label="{{ trans('settings.invoice.item_name') }}"
            :options="$item_names"
            :selected="$item_name"
            input-name="item_name_input"
            :input-value="$item_name_input"
            form-group-class="sm:col-span-6 sm:gap-0"
        />

        <x-form.group.invoice-text
            name="price_name"
            label="{{ trans('settings.invoice.price_name') }}"
            :options="$price_names"
            :selected="$price_name"
            input-name="price_name_input"
            :input-value="$price_name_input"
            form-group-class="sm:col-span-6 sm:gap-0"
        />

        <x-form.group.invoice-text
            name="quantity_name"
            label="{{ trans('settings.invoice.quantity_name') }}"
            :options="$quantity_names"
            :selected="$quantity_name"
            input-name="quantity_name_input"
            :input-value="$quantity_name_input"
            form-group-class="sm:col-span-6 sm:gap-0"
        />

        <x-form.group.toggle name="hide_item_description" label="{{ trans('settings.invoice.hide.item_description') }}" :value="$hide_item_description" />

        <x-form.group.toggle name="hide_quantity" label="{{ trans('settings.invoice.hide.quantity') }}" :value="$hide_quantity" />
        <x-form.group.toggle name="hide_amount" label="{{ trans('settings.invoice.hide.amount') }}" :value="$hide_amount" not-required form-group-class="sm:col-span-6" />

        <x-form.input.hidden name="type" :value="$type" />
        <x-form.input.hidden name="enabled" value="1" />
    </div>
</x-form>
