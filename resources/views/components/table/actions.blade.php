@php
    $count_buttons = 1;
    $more_actions = [];
@endphp

<div class="absolute ltr:right-12 rtl:left-12 -top-4 hidden items-center group-hover:flex">
    @foreach ($actions as $action)
        @if (! empty($action['permission']))
            @can($action['permission'])
        @endif

        @if ($count_buttons > 3 && $loop->count > 4)
            @break
        @endif

        @php
            $type = ! empty($action['type']) ? $action['type'] : 'link';
        @endphp

        @switch($type)
            @case('button')
                <button type="button" class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer index-actions group" {!! $action['attributes'] ?? null !!}>
                    <span class="material-icons-outlined text-purple text-lg">
                        {{ $action['icon'] }}
                    </span>

                    <div class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm whitespace-nowrap opacity-0 tooltip-content -top-10 -left-2" data-tooltip-placement="top">
                        <span>{{ $action['title'] }}</span>
                        <div class="absolute w-2 h-2 -bottom-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-t-0 before:border-l-0" data-popper-arrow></div>
                    </div>
                </button>
                @break

            @case('delete')
                @php
                    $title = $action['title'] ?? null;
                    $modelId = ! empty($action['model-id']) ? $action['model-id'] : 'id';
                    $modelName = ! empty($action['model-name']) ? $action['model-name'] : 'name';
                @endphp

                <x-delete-button :model="$action['model']" :route="$action['route']" :text="$title" :model-id="$modelId" :model-name="$modelName" />
                @break

            @default
                <a href="{{ $action['url'] }}" class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer index-actions" {!! $action['attributes'] ?? null !!}>
                    <span class="material-icons-outlined text-purple text-lg">
                        {{ $action['icon'] }}
                    </span>

                    <div class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm whitespace-nowrap opacity-0 tooltip-content -top-10 -left-2" data-tooltip-placement="top">
                        <span>{{ $action['title'] }}</span>
                        <div class="absolute w-2 h-2 -bottom-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-t-0 before:border-l-0" data-popper-arrow></div>
                    </div>
                </a>
        @endswitch

        @php
            array_shift($actions);

            $count_buttons++;
        @endphp

        @if (! empty($action['permission']))
            @endcan
        @endif
    @endforeach

    @foreach ($actions as $action)
        @if (! empty($action['permission']))
            @can($action['permission'])
            @php $more_actions[] = $action; @endphp
            @endcan
        @else
            @php $more_actions[] = $action; @endphp
        @endif
    @endforeach

    @if ($more_actions)
        @php $divider = false; @endphp

        <div class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer index-actions">
            <button type="button" data-dropdown-toggle="dropdown-actions-{{ $loop->index }}" data-dropdown-placement="left" class="material-icons-outlined text-purple text-lg">more_horiz</button>

            <div id="dropdown-actions-{{ $loop->index }}" data-dropdown-actions class="absolute py-2 bg-white rounded-md border border-gray-200 shadow-xl z-20 hidden !mt-[50px]" style="left:auto; min-width:10rem;">    
                @foreach ($more_actions as $action)
                    @php
                        $type = ! empty($action['type']) ? $action['type'] : 'link';
                    @endphp

                    @switch($type)
                        @case('button')
                            @php $divider = false; @endphp

                            <button type="button" class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap" {!! $action['attributes'] ?? null !!}>
                                <span class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
                                    {{ $action['title'] }}
                                </span>
                            </button>
                            @break

                        @case('delete')
                            @php $divider = false; @endphp

                            @php
                                $title = $action['title'] ?? null;
                                $modelId = ! empty($action['model-id']) ? $action['model-id'] : 'id';
                                $modelName = ! empty($action['model-name']) ? $action['model-name'] : 'name';
                            @endphp
                            <x-delete-link :model="$action['model']" :route="$action['route']" :text="$title" :model-id="$modelId" :model-name="$modelName" />
                            @break

                        @case('divider')
                            @if (! $divider)
                                @php $divider = true; @endphp
                                <div class="py-2 px-2">
                                    <div class="w-full border-t border-gray-200"></div>
                                </div>
                            @endif
                            @break

                        @default
                            @php $divider = false; @endphp

                            <a href="{{ $action['url'] }}" class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap" {!! $action['attributes'] ?? null !!}>
                                <span class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
                                    {{ $action['title'] }}
                                </span>
                            </a>
                    @endswitch
                @endforeach
            </div>
        </div>
    @endif
</div>
