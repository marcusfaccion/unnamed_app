// Executa ações após a atualização do elemento html 
$(document).on('pjax:beforeSend', function(event, xhr, options) {
      
      //Bikekeeper Pajax request
//      if(typeof options.bike_keeper_beforeSend === 'function'){
//           options.bike_keeper_beforeSend();
//      }
})
// Executa ações após a atualização do elemento html 
$(document).on('pjax:success', function(event, data, status, xhr, options) {
      
      //Bikekeeper Pajax request
//      if(typeof options.bike_keeper_success === 'function'){
//           options.bike_keeper_success();
//      }
})

// Exibi preloader durante requisições XHR via Jquery Pjax plugin
$(document).on('pjax:send', function() {
  Loading.show()
})
$(document).on('pjax:complete', function() {
  Loading.hide()
})