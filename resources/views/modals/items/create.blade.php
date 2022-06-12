<x-form id="item" route="items.store">
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

        <x-form.group.select multiple name="tax_ids" label="{{ trans_choice('general.taxes', 1) }}" :options="$taxes" :selected="(setting('default.tax')) ? [setting('default.tax')] : null" not-required />

        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" not-required />

        <x-form.group.text name="sale_price" label="{{ trans('items.sale_price') }}" />

        <x-form.group.text name="purchase_price" label="{{ trans('items.purchase_price') }}" />

        <x-form.group.category type="item" not-required without-add-new />

        <x-form.input.hidden name="enabled" value="1" />
    </div>
</x-form>
