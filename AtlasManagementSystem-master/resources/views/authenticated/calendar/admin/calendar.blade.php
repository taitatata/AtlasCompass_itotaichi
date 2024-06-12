@extends('layouts.sidebar')

@section('content')
<!-- スクール予約確認 -->
<div class="w-100 vh-100 d-flex" style="align-items:center; justify-content:center;">
    <div class="w-100 vh-100 border p-5 calendar_style_a">
        <div class="calendar_style_c">
            {!! $calendar->getTitle() !!}
            {!! $calendar->render() !!}
            </div>
        </div>
    </div>
</div>
@endsection
