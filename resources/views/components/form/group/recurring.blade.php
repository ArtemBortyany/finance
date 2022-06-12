<akaunting-recurring
    start-text="{!! trans('recurring.limit_date', ['type' => Str::replace('-recurring', '', $type)]) !!}"
    :date-range-text="{{ json_encode(trans('general.date_range')) }}"
    middleText="{!! trans('recurring.limit_middle') !!}"
    end-text="{{ Str::plural(Str::replace('-recurring', '', $type)) }}"

    :frequencies="{{ json_encode($frequencies) }}"
    frequency-text="{!! trans('recurring.frequency_type', ['type' => Str::replace('-recurring', '', $type)]) !!}"
    frequency-every-text="{!! trans('recurring.every') !!}"
    frequency-value="{{ $frequency }}"
    :frequency-error="form.errors.get('recurring_frequency')"
    @if ($attributes->has('@frequency'))
    @frequency="form.recurring_frequency = $event;{{ $attributes['@frequency'] }}"
    @else
    @frequency="form.recurring_frequency = $event"
    @endif

    :custom-frequencies="{{ json_encode($customFrequencies) }}"
    custom-frequency-value="{{ $customFrequency }}"
    :custom-frequency-error="form.errors.get('recurring_custom_frequency')"
    @if ($attributes->has('@interval'))
    @interval="form.recurring_interval = $event;{{ $attributes['@interval'] }}"
    @else
    @interval="form.recurring_interval = $event"
    @endif

    @if ($attributes->has('@custom_frequency'))
    @custom_frequency="form.recurring_custom_frequency = $event;{{ $attributes['@custom_frequency'] }}"
    @else
    @custom_frequency="form.recurring_custom_frequency = $event"
    @endif

    started-value="{{ $startedValue }}"
    @if ($attributes->has('@started'))
    @started="form.recurring_started_at = $event;{{ $attributes['@started'] }}"
    @else
    @started="form.recurring_started_at = $event"
    @endif

    :limits="{{ json_encode($limits) }}"
    limit-value="{{ $limit }}"
    :limit-error="form.errors.get('recurring_limit')"
    @if ($attributes->has('@limit'))
    @limit="form.recurring_limit = $event;{{ $attributes['@limit'] }}"
    @else
    @limit="form.recurring_limit = $event"
    @endif

    limit-count-value="{{ $limitCount }}"
    @if ($attributes->has('@limit_count'))
    @limit_count="form.recurring_limit_count = $event;{{ $attributes['@limit_count'] }}"
    @else
    @limit_count="form.recurring_limit_count = $event"
    @endif

    limit-date-value="{{ $limitDateValue }}"
    @if ($attributes->has('@limit_date'))
    @limit_date="form.recurring_limit_date = $event;{{ $attributes['@limit_date'] }}"
    @else
    @limit_date="form.recurring_limit_date = $event"
    @endif

    date-format="{{ company_date_format() }}"

    {{ $attributes }}
>
</akaunting-recurring>
