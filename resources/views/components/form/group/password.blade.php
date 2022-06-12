@stack($name . '_input_start')
    <div
        @class([
            'form-group relative',
            $formGroupClass,
            'required' => $required,
            'readonly' =>  $readonly,
            'disabled' => $disabled,
        ])

        @if (isset($attributes['v-show']))
        v-if="{{ $attributes['v-show'] }}"
        @endif

        @if (isset($attributes['v-disabled']))
        :class="[
            {'disabled' : {{ $attributes['v-disabled'] }}},
            {'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }}}
        ]"
        @else
        :class="[
            {'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }}}
        ]"
        @endif
    >
        @if (! $attributes->has('label') && ! empty($label->contents))
            {!! $label ?? '' !!}
        @elseif (! empty($label))
            <x-form.label for="{{ $name }}" class="form-control-label">{!! $label !!}</x-form.label>
        @endif

        <div @class([
                'input-group input-group-merge',
                $inputGroupClass,
            ])
        >
            @if (! $attributes->has('icon') && ! empty($icon->contents))
                {!! $icon ?? '' !!}
            @elseif (! empty($icon))
                <x-form.icon icon="{{ $icon }}" />
            @endif

            <x-form.input.password
                name="{{ $name }}"
                id="{{ $id }}"
                class="form-element"
                value="{{ $value }}"
                placeholder="{{ $placeholder }}"
                v-model="{{ !empty($attributes['v-model']) ? $attributes['v-model'] : (!empty($attributes['data-field']) ? 'form.' . $attributes['data-field'] . '.' . $name : 'form.' . $name) }}"
                {{ $attributes->merge($custom_attributes) }}
            />
        </div>

        @if (! $attributes->has('error') && ! empty($error->contents))
            {!! $error ?? '' !!}
        @else
            <x-form.error name="{{ $name }}" {{ $attributes }} />
        @endif
    </div>
@stack($name . '_input_end')
