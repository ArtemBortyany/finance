<x-form.group.select
    name="locale"
    label="{!! trans_choice('general.languages', 1) !!}"
    :options="language()->allowed()"
    :selected="$selected"
/>
