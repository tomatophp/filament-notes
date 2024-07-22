<div style="color: {{ $note->color }} !important;" class="note">
    @if($note->icon)
        <div style="color: {{$note->color}} !important;">
            <x-filament::icon icon="{{$note->icon}}" class="w-8 h-8 mb-2"  />
        </div>
    @endif
    @if($note->title)
        <h1 class="font-bold text-lg mb-2">{{ $note->title }}</h1>
    @endif
    <p class="prose text-wrap">
        {!! str($note->body)->markdown()->limit(400)->toString() !!}
    </p>

        @if($note->time || $note->date || $note->user_id)
            <div class="flex justify-between gap-2 py-4 text-sm">
                <div>
                    @if($note->user_id)
                        <div class="flex justify-start gap-2">
                            <div class="flex flex-col justify-center items-center">
                                <x-filament::icon icon="heroicon-s-user" class="w-4 h-4" style="color: {{$note->color}} !important;" />
                            </div>
                            <div class="flex flex-col justify-center items-center">
                                <h1 class="font-bold">{{ $note->user?->name }}</h1>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="flex justify-end gap-2">
                    @if($note->time)
                        <div x-tooltip="{
                                    content: '{{ $note->time }}',
                                    theme: $store.theme,
                                }" class="flex flex-col justify-center items-center">
                            <span class="flex justify-center" tooltip="hi">
                                <x-filament::icon icon="heroicon-s-clock" class="w-4 h-4" style="color: {{$note->color}} !important;" />
                            </span>
                        </div>
                    @endif
                    @if($note->date)
                        <div x-tooltip="{
                                content: '{{ $note->date }}',
                                theme: $store.theme,
                            }" class="flex flex-col justify-center items-center">
                        <span class="flex justify-center" tooltip="hi">
                            <x-filament::icon icon="heroicon-s-calendar-days" class="w-4 h-4" style="color: {{$note->color}} !important;" />
                        </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        @if($note->group || $note->status)
            <div class="flex justify-start gap-2 pb-4 text-sm">
                @if($note->group && filament('filament-notes')->useGroups)
                    <div>
                        <x-tomato-type label="{{ trans('filament-notes::messages.columns.group') }}" :type="type_of($note->group, 'notes', 'groups')" />
                    </div>
                @endif
                @if($note->status && filament('filament-notes')->useStatus)
                    <div>
                        <x-tomato-type label="{{ trans('filament-notes::messages.columns.status') }}" :type="type_of($note->status, 'notes', 'status')" />
                    </div>
                @endif
            </div>
        @endif
</div>


@if($note->is_pined)
<style>
    .note {
        line-height: 1.2em;
        font-size: {{$note->font_size}} !important;
    }
    .fi-modal-window {
        background: {{ $note->background }} !important;
        border-radius: 5px;
        border: 2px solid {{ $note->border }};
        overflow-wrap: break-word;
        font-family: "{{$note->font !='null' && !empty($note->font) ? str($note->font)->replace('+', ' ') : filament()->getCurrentPanel()->getFontFamily()}}" !important;
    }

    .fi-modal-window:after {
        display: block;
        content: "";
        position: absolute;
        width: 110px;
        height: 20px;
        top: -11px;
        left: 45%;
        border-radius: 5px;
        border: 1px solid #6be38b;
        background: rgba(107, 227, 139, .6);
        -webkit-box-shadow: 0px 0 3px rgba(0,0,0,0.1);
        -moz-box-shadow: 0px 0 3px rgba(0,0,0,0.1);
        box-shadow: 0px 0 3px rgba(0,0,0,0.1);
    }

</style>
@else
<style>
    .note {
        line-height: 1.2em;
        font-size: {{$note->font_size}} !important;
    }
    .fi-modal-window {
        background: {{ $note->background }} !important;
        border-radius: 5px;
        border: 2px solid {{ $note->border }};
        overflow-wrap: break-word;
        font-family: "{{$note->font !='null' && !empty($note->font) ? str($note->font)->replace('+', ' ') : filament()->getCurrentPanel()->getFontFamily()}}" !important;
    }

    .fi-modal-window:after {
        display: block;
        content: "";
        position: absolute;
        width: 110px;
        height: 20px;
        top: -11px;
        left: 45%;
        border-radius: 5px;
        border: 1px solid #fff;
        background: rgba(254, 254, 254, .6);
        -webkit-box-shadow: 0px 0 3px rgba(0,0,0,0.1);
        -moz-box-shadow: 0px 0 3px rgba(0,0,0,0.1);
        box-shadow: 0px 0 3px rgba(0,0,0,0.1);
    }

</style>
@endif
<style>
    .fi-modal-footer-actions button svg {
        color: {{ $note->color }} !important;
    }
</style>

@if($note->font && $note->font != 'null' && !empty($note->font))
    <link href="https://fonts.googleapis.com/css2?family={{$note->font}}:wght@100..400&display=swap" rel="stylesheet">
@endif
