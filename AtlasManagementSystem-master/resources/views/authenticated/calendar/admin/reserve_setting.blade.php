@extends('layouts.sidebar')
@section('content')
<!-- スクール枠登録 -->
<div class="w-100 vh-100 d-flex" style="align-items:center; justify-content:center;">
    <div class="w-100 vh-100 border p-5 calendar_style_a">
        <div class="calendar_style_b">
            {!! $calendar->getTitle() !!}
            {!! $calendar->render() !!}
            <div class="adjust-table-btn m-auto text-right">
                <input type="submit" class="btn btn-primary setting_btn" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
            </div>
        </div>
    </div>
</div>
@endsection
