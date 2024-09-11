<x-filament-actions::action
    :action="$action"
    dynamic-component="filament::button"
    color="null"
    style="box-shadow: none;"
    class="cursor-pointer"
>

    <div>
        <div class="post-it">
            <div id="sticky_note_{{$note->id}}" class="@if($note->is_pined) taped_note_pined @else taped_note @endif  w-full">
                <div class="p-4 apply-font text-start" style="color: {{ $note->color }} !important;">
                    @if($note->icon)
                        <div style="color: {{$note->color}} !important;">
                            <x-filament::icon icon="{{$note->icon}}" class="w-8 h-8 mb-2"  />
                        </div>
                    @endif
                    @if($note->title)
                        <h1 class="font-bold text-lg mb-2">{{ $note->title }}</h1>
                    @endif
                    <p style=" line-height: 1.2em;" class="prose text-wrap ">
                        {!! str($note->body)->markdown()->limit(400)->toString() !!}
                    </p>
                </div>

                @if($note->time || $note->date || $note->checklist || $note->user_id)
                    <div class="flex justify-between gap-2 px-4 pb-4 text-sm">
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
                            <span class="flex justify-center" >
                                <x-filament::icon icon="heroicon-s-clock" class="w-4 h-4" style="color: {{$note->color}} !important;" />
                            </span>
                                </div>
                            @endif
                            @if($note->date)
                                <div x-tooltip="{
                                content: '{{ $note->date }}',
                                theme: $store.theme,
                            }" class="flex flex-col justify-center items-center">
                                <span class="flex justify-center" >
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
                                        <span class="flex justify-center" >
                                            <div class="flex flex-col justify-center items-center pt-2">
                                                <canvas  class="w-5 h-5" x-ref="canvas" id="checklist_{{ $note->id }}"></canvas>

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
                    <div class="flex justify-start gap-2 px-4 pb-4 text-sm">
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
        </div>
        <style>
            #sticky_note_{{$note->id}} {
                -webkit-box-shadow: #DDD 0px 1px 2px;
                overflow-wrap: break-word;
                position: relative;
                background-color: {{ $note->background }};
                border-radius: 5px;
                border: 2px solid {{ $note->border }};
                color: {{ $note->color }};
                margin: 1.5em auto;
                -webkit-box-shadow: 0px 1px 3px rgba(0,0,0,0.25);
                -moz-box-shadow: 0px 1px 3px rgba(0,0,0,0.25);
                box-shadow: 0px 1px 3px rgba(0,0,0,0.25);
                -webkit-transform: rotate(2deg);
                -moz-transform: rotate(2deg);
                -o-transform: rotate(2deg);
                -ms-transform: rotate(2deg);
                transform: rotate(2deg);
                width: 270px;
                transition-duration: 0.25s;
                font-size: {{$note->font_size}} !important;
                font-family: "{{$note->font !='null'  && !empty($note->font)? str($note->font)->replace('+', ' ') : filament()->getCurrentPanel()->getFontFamily()}}" !important;
            }
            #sticky_note_{{$note->id}}:hover {
                -webkit-transform: rotate(-2deg);
                -moz-transform: rotate(-2deg);
                -o-transform: rotate(-2deg);
                -ms-transform: rotate(-2deg);
                transform: rotate(-2deg);
                -webkit-box-shadow: 0px 1px 3px rgba(0,0,0,0.35);
                -moz-box-shadow: 0px 1px 3px rgba(0,0,0,0.35);
                box-shadow: 0px 1px 3px rgba(0,0,0,0.35);
                transition-duration: 0.25s;
            }
            #sticky_note_{{$note->id}}.taped_note:after {
                display: block;
                content: "";
                position: absolute;
                width: 110px;
                height: 20px;
                top: -11px;
                left: 30%;
                border-radius: 5px;
                border: 1px solid  #fff;
                background: rgba(254, 254, 254, .6);
                -webkit-box-shadow: 0px 0 3px rgba(0,0,0,0.1);
                -moz-box-shadow: 0px 0 3px rgba(0,0,0,0.1);
                box-shadow: 0px 0 3px rgba(0,0,0,0.1);
            }
            #sticky_note_{{$note->id}}.taped_note_pined:after {
                display: block;
                content: "";
                position: absolute;
                width: 110px;
                height: 20px;
                top: -11px;
                left: 30%;
                border-radius: 5px;
                border: 1px solid #6be38b;
                background: rgba(107, 227, 139, .6);
                -webkit-box-shadow: 0px 0 3px rgba(0,0,0,0.1);
                -moz-box-shadow: 0px 0 3px rgba(0,0,0,0.1);
                box-shadow: 0px 0 3px rgba(0,0,0,0.1);
            }
            #sticky_note_{{$note->id}} .apply-font p{
                font-size: '{{ $note->font_size }}' !important;
                font-family: "{{$note->font !='null'  && !empty($note->font)? str($note->font)->replace('+', ' ') : filament()->getCurrentPanel()->getFontFamily()}}" !important;
            }


        </style>

        @if($note->font && $note->font != 'null' && !empty($note->font))
            <link href="https://fonts.googleapis.com/css2?family={{$note->font}}:wght@100..400&display=swap" rel="stylesheet">
        @endif

    </div>
</x-filament-actions::action>
