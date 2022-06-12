<x-layouts.admin>
    <x-slot name="metaTitle">
        {{ $dashboard->name }}
    </x-slot>

    <x-slot name="title">
        @if ($user_dashboards->count() > 1)
            <div class="flex items-center relative cursor-pointer">
                <x-dropdown id="dashboard-list">
                    <x-slot name="trigger" class="flex items-center" override="class">
                        <span class="text-2xl xl:text-5xl text-black font-light truncate border-b border-transparent transition-all hover:text-black-700 hover:border-black">
                            {{ $dashboard->name }}
                        </span>

                        <svg class="w-5 h-5 mt-1 text-gray-400" x-description="Heroicon name: solid/selector" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </x-slot>

                    @foreach ($user_dashboards as $user_dashboard)
                        <x-dropdown.link href="{{ route('dashboards.switch', $user_dashboard->id) }}">
                            {{ $user_dashboard->name }}
                        </x-dropdown.link>
                    @endforeach
                </x-dropdown>
            </div>
        @else
            {{ $dashboard->name }}
        @endif
    </x-slot>

    <x-slot name="buttons">
        <!--Dashboard General Filter-->
        <el-date-picker
            v-model="filter_date"
            type="daterange"
            align="right"
            unlink-panels
            format="yyyy-MM-dd"
            value-format="yyyy-MM-dd"
            @change="onChangeFilterDate"
            range-separator="-"
            start-placeholder="{{ $date_picker_shortcuts[trans('reports.this_year')]['start'] }}"
            end-placeholder="{{ $date_picker_shortcuts[trans('reports.this_year')]['end'] }}"
            popper-class="dashboard-picker"
            :picker-options="{
                shortcuts: [
                    {
                        text: '{{ trans("reports.this_year") }}',
                        onClick(picker) {
                            const start = new Date('{{ $date_picker_shortcuts[trans("reports.this_year")]["start"] }}');
                            const end = new Date('{{ $date_picker_shortcuts[trans("reports.this_year")]["end"] }}');

                            picker.$emit('pick', [start, end]);
                        }
                    },
                    {
                        text: '{{ trans("reports.previous_year") }}',
                        onClick(picker) {
                            const start = new Date('{{ $date_picker_shortcuts[trans("reports.previous_year")]["start"] }}');
                            const end = new Date('{{ $date_picker_shortcuts[trans("reports.previous_year")]["end"] }}');

                            picker.$emit('pick', [start, end]);
                        }
                    },
                    {
                        text: '{{ trans("reports.this_quarter") }}',
                        onClick(picker) {
                            const start = new Date('{{ $date_picker_shortcuts[trans("reports.this_quarter")]["start"] }}');
                            const end = new Date('{{ $date_picker_shortcuts[trans("reports.this_quarter")]["end"] }}');

                            picker.$emit('pick', [start, end]);
                        }
                    },
                    {
                        text: '{{ trans("reports.previous_quarter") }}',
                        onClick(picker) {
                            const start = new Date('{{ $date_picker_shortcuts[trans("reports.previous_quarter")]["start"] }}');
                            const end = new Date('{{ $date_picker_shortcuts[trans("reports.previous_quarter")]["end"] }}');

                            picker.$emit('pick', [start, end]);
                        }
                    },
                    {
                        text: '{{ trans("reports.last_12_months") }}',
                        onClick(picker) {
                            const start = new Date('{{ $date_picker_shortcuts[trans("reports.last_12_months")]["start"] }}');
                            const end = new Date('{{ $date_picker_shortcuts[trans("reports.last_12_months")]["end"] }}');

                            picker.$emit('pick', [start, end]);
                        }
                    }
                ]
            }">
        </el-date-picker>
    </x-slot>

    @section('dashboard_action')
        @canany(['create-common-widgets', 'read-common-dashboards'])
            <div class="dashboard-action">
                <x-dropdown id="dropdown-dashboard-company">
                    <x-slot name="trigger" class="flex" override="class">
                        <span id="dashboard-more-actions" class="material-icons-outlined text-4xl text-purple cursor-pointer hover:bg-gray-100 hover:rounded-lg hover:shadow-md">more_vert</span>
                    </x-slot>

                    @can('create-common-widgets')
                        <x-button
                            type="button"
                            id="dashboard-add-widget"
                            class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap"
                            override="class"
                            title="{{ trans('general.title.add', ['type' => trans_choice('general.widgets', 1)]) }}"
                            @click="onCreateWidget()"
                        >
                            <span class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
                                {{ trans('general.title.add', ['type' => trans_choice('general.widgets', 1)]) }}
                            </span>
                        </x-button>

                        <x-dropdown.divider />
                    @endcan

                    @can('update-common-dashboards')
                        @can('create-common-dashboards')
                        <x-dropdown.link href="{{ route('dashboards.create') }}">
                            {{ trans('general.title.create', ['type' => trans_choice('general.dashboards', 1)]) }}
                        </x-dropdown.link>
                        @endcan

                        <x-dropdown.link href="{{ route('dashboards.index') }}">
                            {{ trans('general.title.manage', ['type' => trans_choice('general.dashboards', 2)]) }}
                        </x-dropdown.link>
                    @endcan
                </x-dropdown>
            </div>
        @endcanany

        @php
            $text = json_encode([
                'name' => trans('general.name'),
                'type' => trans_choice('general.types', 1),
                'width' => trans('general.width'),
                'sort' => trans('general.sort'),
                'enabled' => trans('general.enabled'),
                'yes' => trans('general.yes'),
                'no' => trans('general.no'),
                'save' => trans('general.save'),
                'cancel' => trans('general.cancel')
            ]);

            $placeholder = json_encode([
                'name' => trans('general.form.enter', ['field' => trans('general.name')]),
                'type' => trans('general.form.select.field', ['field' => trans_choice('general.types', 1)]),
                'width' => trans('general.form.select.field', ['field' => trans('general.width')]),
                'sort' => trans('general.form.enter', ['field' => trans('general.sprt')])
            ]);
        @endphp

        <akaunting-widget
            v-if="widget_modal"
            :title="'{{ trans_choice('general.widgets', 1) }}'"
            :show="widget_modal"
            :widget_id="widget.id"
            :name="widget.name"
            :width="widget.width"
            :action="widget.action"
            :type="widget.class"
            :types="widgets"
            :sort="widget.sort"
            :dashboard_id="{{ $dashboard->id }}"
            :text="{{ $text }}"
            :placeholder="{{ $placeholder }}"
            @cancel="onCancel">
        </akaunting-widget>
    @endsection

    <x-slot name="content">
        <div class="dashboard flex flex-wrap -mx-12">
            @foreach($widgets as $widget)
                @widget($widget)
            @endforeach
        </div>
    </x-slot>

    <x-script folder="common" file="dashboards" />
</x-layouts.admin>
