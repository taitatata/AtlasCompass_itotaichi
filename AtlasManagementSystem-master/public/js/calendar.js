$(function () {
    $(document).ready(function () {
        // モーダル表示時に予約情報を設定
        $('#cancelModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // モーダルを表示させたボタン
            var reserveId = button.data('id'); // ボタンから予約IDを取得
            var reserveDate = button.data('date'); // ボタンから予約日を取得
            var reservePart = button.data('part'); // ボタンから予約部を取得

            console.log("Reserve ID: ", reserveId);
            console.log("Reserve Date: ", reserveDate);
            console.log("Reserve Part: ", reservePart);

            var modal = $(this);
            // モーダル内に予約情報を表示
            modal.find('#reservationId').text('ID: ' + reserveId);//テスト用
            modal.find('#reservationDate').text('予約日: ' + reserveDate);
            modal.find('#reservationPart').text('部: ' + reservePart);
            modal.find('#confirmCancel').data('date', reserveDate); // キャンセルボタンに予約日を設定
            modal.find('#confirmCancel').data('part', reservePart); // キャンセルボタンに予約部を設定
            modal.find('#confirmCancel').data('id', reserveId); // キャンセルボタンに予約IDを設定
        });

        // キャンセルボタンをクリックしたときの処理
        $('#confirmCancel').click(function () {
            var reserveDate = $(this).data('date');
            var reservePart = $(this).data('part');
            var reserveId = $(this).data('id');
            $.ajax({
                url: '/cancel/reserve/' + reserveId,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        alert('予約をキャンセルしました');
                        location.reload(); // ページをリロードして変更を反映
                    } else {
                        alert('キャンセルに失敗しました: ' + response.message);
                    }
                },
                error: function (response) {
                    alert('キャンセルに失敗しました: ' + response.responseText);
                }
            });
        });
    });
});
