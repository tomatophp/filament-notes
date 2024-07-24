@extends('filament-notes::layouts.filament')

@section('content')
    <div class="p-6 bg-white dark:bg-gray-900 h-screen w-screen">
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

                    @if($note->time || $note->date || $note->user_id)
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
                        <div class="flex justify-start gap-2 px-4 pb-4 text-sm">
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
                    width: 100%;
                    font-size: {{$note->font_size}} !important;
                    font-family: "{{$note->font !='null'  && !empty($note->font)? str($note->font)->replace('+', ' ') : filament()->getCurrentPanel()->getFontFamily()}}" !important;
                }
                #sticky_note_{{$note->id}}.taped_note:after {
                    display: block;
                    content: "";
                    position: absolute;
                    width: 110px;
                    height: 20px;
                    top: -11px;
                    left: 50%;
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
                    left: 50%;
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
    </div>
@endsection
