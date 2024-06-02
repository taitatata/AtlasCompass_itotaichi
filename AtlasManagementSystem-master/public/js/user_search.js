$(function () {
  $('.search_conditions').click(function () {
    $('.search_conditions_inner').slideToggle();
    // 矢印の向きを変える
    var arrow = $(this).find('.toggle_arrow');
    arrow.toggleClass('open');
  });

  $('.subject_edit_btn').click(function () {
    $('.subject_inner').slideToggle();
  });

});
