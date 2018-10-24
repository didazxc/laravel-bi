
$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
$(document).pjax('[pjax-content] a, a[pjax]', '#pjax-content');
$(document).on('submit', 'form[pjax-content]', function(event){
    $.pjax.submit(event, '#pjax-content')
})
$(document).on("pjax:timeout", function(event) {
    event.preventDefault();// 阻止超时导致链接跳转事件发生
});
$(document).on('pjax:start', function() {
    NProgress.start();
});
$(document).on('pjax:end', function() {
    NProgress.done();
    //if(typeof siteBootUp != 'undefined' && siteBootUp instanceof Function) siteBootUp();
});
$(function(){
    //mask
    $("#loader").delay(500).fadeOut(300);
    $(".mask").delay(800).fadeOut(300);
});