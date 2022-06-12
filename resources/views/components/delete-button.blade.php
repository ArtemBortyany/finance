<x-button
    type="button"
    class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer index-actions"
    @click="onDeleteViaConfirmation('delete-{{ $modelTable }}-{{ $id }}')"
    override="class"
>
    @if ($slot->isNotEmpty())
        {!! $slot !!}
    @else
        <span class="material-icons-outlined text-purple text-lg">delete</span>
        <div class="inline-block absolute invisible z-10 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm opacity-0 whitespace-nowrap tooltip-content -top-10 -left-2" data-tooltip-placement="top">
            <span>{!! $label !!}</span>
            <div class="absolute w-2 h-2 -bottom-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-t-0 before:border-l-0" data-popper-arrow></div>
        </div>
    @endif

    <x-form.input.hidden
        name="delete-{{ $modelTable }}-{{ $id }}"
        id="delete-{{ $modelTable }}-{{ $id }}"
        data-field="delete"
        data-action="{{ $action }}"
        data-title="{!! $title !!}"
        data-message="{!! $message !!}"
        data-cancel="{!! $cancelText !!}"
        data-delete="{!! $deleteText !!}"
    />
</x-button>
