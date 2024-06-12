$(function () {
  $('.main_categories').click(function () {
    var category_id = $(this).attr('category_id');
    $('.category_num' + category_id).slideToggle();

    // 矢印の向きを変える
    var arrow = $(this).find('.toggle_arrow');
    arrow.toggleClass('open');
  });

  $(document).on('click', '.like_btn', function (e) {
    e.preventDefault();
    $(this).addClass('un_like_btn');
    $(this).removeClass('like_btn');
    var post_id = $(this).attr('post_id');
    var count = $('.like_counts' + post_id).text();
    var countInt = Number(count);
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/like/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      console.log(res);
      $('.like_counts' + post_id).text(countInt + 1);
    }).fail(function (res) {
      console.log('fail');
    });
  });

  $(document).on('click', '.un_like_btn', function (e) {
    e.preventDefault();
    $(this).removeClass('un_like_btn');
    $(this).addClass('like_btn');
    var post_id = $(this).attr('post_id');
    var count = $('.like_counts' + post_id).text();
    var countInt = Number(count);

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/unlike/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      $('.like_counts' + post_id).text(countInt - 1);
    }).fail(function () {

    });
  });

  $(document).ready(function () {
    if ($('#hasErrors').val() === 'true' && ($('#error_post_title').length > 0 || $('#error_post_body').length > 0)) {
      $('.js-modal').fadeIn();
      var post_title = $('#old_post_title').val();
      var post_body = $('#old_post_body').val();
      var post_id = $('#old_post_id').val();
      $('.modal-inner-title input').val(post_title);
      $('.modal-inner-body textarea').text(post_body);
      $('.edit-modal-hidden').val(post_id);
    }

    $('.edit-modal-open').on('click', function () {
      $('.js-modal').fadeIn();
      var post_title = $(this).attr('post_title');
      var post_body = $(this).attr('post_body');
      var post_id = $(this).attr('post_id');
      $('.modal-inner-title input').val(post_title);
      $('.modal-inner-body textarea').text(post_body);
      $('.edit-modal-hidden').val(post_id);
      return false;
    });

    $('.js-modal-close').on('click', function () {
      $('.js-modal').fadeOut();
      return false;
    });

    // モーダルの外側をクリックしたときのみモーダルを閉じる
    $(document).on('click', function (e) {
      if ($(e.target).hasClass('modal__bg')) {
        $('.js-modal').fadeOut();
      }
    });

    // モーダルの内容部分をクリックしても閉じないようにする
    $('.modal__content').on('click', function (e) {
      e.stopPropagation();
    });
  });

  $(document).ready(function () {
    $('#deleteModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // モーダルを表示させたボタン
      var postId = button.data('post-id'); // ボタンから投稿IDを取得
      var action = '/bulletin_board/delete/' + postId; // フォームのアクションURLを生成

      var modal = $(this);
      modal.find('#deleteForm').attr('action', action); // フォームのアクションURLを設定
    });
  });

});
