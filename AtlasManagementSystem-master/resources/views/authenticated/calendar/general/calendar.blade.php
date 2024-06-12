@extends('layouts.sidebar')

@section('content')
<!-- スクール予約 -->
<div class="w-100 vh-100 d-flex" style="align-items:center; justify-content:center;">
    <div class="w-100 vh-100 border p-5 calendar_style_a">
        <div class="calendar_style_b">
            <p class="text-center">{!! $calendar->getTitle() !!}</p>
            <div class="">
                {!! $calendar->render() !!}
            </div>
            <div class="adjust-table-btn text-right m-auto">
                <input type="submit" class="btn btn-primary setting_btn" value="予約する" form="reserveParts">
            </div>
        </div>
    </div>
</div>

<!-- モーダルのHTML -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">予約キャンセル確認</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <p id="reservationId"></p> -->
                <!-- ID確認用 -->
                <p id="reservationDate"></p>
                <p id="reservationPart"></p>
                <p>上記の予約をキャンセルしてもよろしいですか？</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                <button type="button" class="btn btn-danger" id="confirmCancel">キャンセルする</button>
            </div>
        </div>
    </div>
</div>
@endsection
