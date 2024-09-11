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

        @if($note->checklist)
            <div class="my-4 flex flex-col justify-start gap-2 text-sm">
                @foreach($note->checklist as $key=>$checklist)
                    <div class="flex justify-start gap-2">
                        <div class="flex flex-col justify-center items-center">
                            <input wire:change="updateChecklist('{{$key}}')" class="fi-checkbox-input dark:bg-gray-700 rounded border-none bg-white shadow-sm ring-1 transition duration-75 checked:ring-0 focus:ring-2 focus:ring-offset-0 disabled:pointer-events-none disabled:bg-gray-50 disabled:text-gray-50 disabled:checked:bg-current disabled:checked:text-gray-400 dark:disabled:bg-transparent dark:disabled:checked:bg-gray-600 text-primary-600 ring-gray-950/10 focus:ring-primary-600 checked:focus:ring-primary-500/50 dark:text-primary-500 dark:ring-white/20 dark:checked:bg-primary-500 dark:focus:ring-primary-500 dark:checked:focus:ring-primary-400/50 dark:disabled:ring-white/10 fi-ta-record-checkbox" type="checkbox" @if(!empty($checklist)) checked @endif>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <h1 class="font-bold">{{ $key }}</h1>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($note->time || $note->date || $note->checklist || $note->user_id)
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
                            <span class="flex justify-center">
                                <x-filament::icon icon="heroicon-s-clock" class="w-4 h-4" style="color: {{$note->color}} !important;" />
                            </span>
                        </div>
                    @endif
                    @if($note->date)
                        <div x-tooltip="{
                                content: '{{ $note->date }}',
                                theme: $store.theme,
                            }" class="flex flex-col justify-center items-center">
                        <span class="flex justify-center">
                            <x-filament::icon icon="heroicon-s-calendar-days" class="w-4 h-4" style="color: {{$note->color}} !important;" />
                        </span>
                        </div>
                    @endif
                    @if($note->checklist)
                            @php
                                $checked = 0;
                                $notChecked = 0;
                                foreach ($note->checklist as $item){
                                    if($item){
                                        $checked++;
                                    }
                                    else {
                                        $notChecked++;
                                    }
                                }
                            @endphp
                            <div x-tooltip="{
                                            content: '{{ $checked }}/{{ $checked + $notChecked }}',
                                            theme: $store.theme,
                                        }"  class="flex flex-col justify-center items-center">
                                <div>
                                    <div
                                        @if (\Filament\Support\Facades\FilamentView::hasSpaMode())
                                            ax-load="visible"
                                        @else
                                            ax-load
                                        @endif
                                        wire:ignore
                                        x-ignore
                                        x-data="chart({
                                                type: 'doughnut',
                                                options: {
                                                    plugins: {
                                                        legend: false,
                                                        tooltip: false,
                                                    },
                                                    elements: {
                                                        arc: {
                                                            borderWidth: 0
                                                        }
                                                    },

                                                    scales: {
                                                        x: {
                                                          border: {display: false},
                                                          ticks: {display: false},
                                                          grid: {display: false},
                                                        },
                                                        y: {
                                                          border: {display: false},
                                                          ticks: {display: false},
                                                          grid: {display: false},
                                                        },
                                                    }
                                                },
                                                cachedData: @js($this->getCachedData()),
                                            })"
                                        ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('chart', 'filament/widgets') }}"
                                    >
                                        <span class="flex justify-center w-6 h-6" >
                                            <div class="flex flex-col justify-center items-center pt-2 w-6 h-6">
                                                <canvas  class="w-6 h-6" x-ref="canvas" id="checklist_{{ $note->id }}"></canvas>

                                                <span x-ref="backgroundColorElement" class="text-gray-100 dark:text-gray-800"></span>

                                                <span x-ref="borderColorElement" class="text-gray-400"></span>

                                                <span
                                                    x-ref="gridColorElement"
                                                    class="text-gray-200 dark:text-gray-800"
                                                ></span>

                                                <span
                                                    x-ref="textColorElement"
                                                    class="text-gray-500 dark:text-gray-400"
                                                ></span>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        @endif
                </div>
            </div>
        @endif
        @if($note->group || $note->status)
            <div class="flex justify-start gap-2 pb-4 text-sm">
                @if($note->group && filament('filament-notes')->useGroups)
                    <div>
                        @php $group = type_of($note->group, 'notes', 'groups') @endphp
                        @if($group)
                            <x-tomato-type label="{{ trans('filament-notes::messages.columns.group') }}" :type="$group" />
                        @endif
                    </div>
                @endif
                @if($note->status && filament('filament-notes')->useStatus)
                    <div>
                        @php $status = type_of($note->status, 'notes', 'status') @endphp
                        @if($status)
                            <x-tomato-type label="{{ trans('filament-notes::messages.columns.status') }}" :type="$status" />
                        @endif
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
    .tappy
    {
        display: none !important;
    }
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
