@stack('button_group_start')

@if (! $hideActionsDropdown)
    <x-dropdown id="dropdown-more-actions">
        <x-slot name="trigger">
            <span class="material-icons">more_horiz</span>
        </x-slot>

        @stack('delete_button_start')

        @if (! $hideButtonDuplicate)
            @can($permissionCreate)
                <x-dropdown.link href="{{ route($routeButtonDuplicate, $contact->id) }}">
                    {{ trans('general.duplicate') }}
                </x-dropdown.link>
            @endcan
        @endif

        <x-dropdown.divider />

        @stack('delete_button_start')

        @if (! $hideButtonDelete)
            @can($permissionDelete)
                <x-delete-link :model="$contact" :route="$routeButtonDelete" :text="$textDeleteModal" />
            @endcan
        @endif

        @stack('delete_button_end')
    </x-dropdown>
@endif

@stack('button_group_end')
