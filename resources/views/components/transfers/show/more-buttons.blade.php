@stack('button_group_start')

<x-dropdown id="dropdown-actions">
    <x-slot name="trigger">
        <span class="material-icons">more_horiz</span>
    </x-slot>

    @stack('edit_button_start')

    @can('update-banking-transfers')
        <x-dropdown.link href="{{ route('transfers.edit', $transfer->id) }}">
            {{ trans('general.edit') }}
        </x-dropdown.link>
    @endcan

    @stack('duplicate_button_start')

    @can('create-banking-transfers')
        <x-dropdown.link href="{{ route('transfers.duplicate', $transfer->id) }}">
            {{ trans('general.duplicate') }}
        </x-dropdown.link>
    @endcan

    @stack('duplicate_button_start')

    <x-dropdown.divider />

    @stack('print_button_start')

    <x-dropdown.link href="{{ route('transfers.print', $transfer->id) }}" target="_blank">
        {{ trans('general.print') }}
    </x-dropdown.link>

    @stack('print_button_end')

    @stack('pdf_button_start')

    <x-dropdown.link href="{{ route('transfers.pdf', $transfer->id) }}">
        {{ trans('general.download_pdf') }}
    </x-dropdown.link>

    @stack('pdf_button_end')

    <x-dropdown.divider />

    @stack('choose_button_start')

    <button type="button" class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap" @click="onTemplate">
        <span class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
            {{ trans('general.form.choose', ['field' => trans_choice('general.templates', 1)]) }}
        </span>
    </button>

    @stack('choose_button_end')

    <x-dropdown.divider />

    @stack('delete_button_start')

    @can('delete-banking-transfers')
        <x-delete-link :model="$transfer" route="transfers.destroy" />
    @endcan

    @stack('delete_button_end')
</x-dropdown>

@stack('button_group_end')
