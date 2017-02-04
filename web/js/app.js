// Inicializar tooltips 
$(document).on('mousemove', 'body', function(){
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});
    $('.leaflet-marker-icon').tooltip({container: 'body'});
});
$(document).on('mousemove', '.modal.in', function(){
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});
});
// Exibi preloader durante requisições XHR
$(document).on('pjax:send', function() {
  Loading.show()
})
$(document).on('pjax:complete', function() {
  Loading.hide()
})