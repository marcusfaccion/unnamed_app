var popup = {
    maxWidth: 320,
    minWidth: 320,
    /**
     *  @param {string} _url Endereço url da requisição 
     *  @param {object} options opções Ajax permitidas (type, data, async)
     *  @return {sring|HTML} conteúdo dinâmico do popup
     */
    getContentAjax: function(_url, options){
        var _return;
        $.ajax({
            url: _url,
            type: options.type,
            data: options.data,
            async: options.async,
            success: function (response /*,textStatus, jqXHR*/) {
                _return = response;
            }
        });
        return _return;
    }
}
var preloader = {
    count: function(elem_id){
        return parseInt($('#'+elem_id).parent().children('.loader').length);
    },
    destroy: function(elem_id, name, size, callback){
        if(typeof(elem_id)==='string'){  
           $('#'+elem_id).before(this[name][size]);
           $('#preload_'+name+'_'+size).hide();
           $('#preload_'+name+'_'+size).css('height', ($('#'+elem_id).height()/ 2)+'px');
           $('#'+elem_id).fadeOut(1500, function(){
             if(arguments.length===4){
                callback();
                $(this).remove(); 
                $('#preload_'+name+'_'+size).css('margin-top', ($('#preload_'+name+'_'+size).height()*0.95)+'px').fadeIn(1500);
             }
            
           });
        }
        return ;
    },
    /**
     * Interrompe o iniciado e o exclui da visualização 
     * @param {string|object} elem_id - id do elemento ao qual se pré-anexa o loader
     * @param {function} callback - função de callBack
     * @returns {void}
     */
    disable: function(elem_id, name, size, callback){
        if(typeof(elem_id)==='string'){
           if(this.hasPreloader(elem_id)){
               var params = arguments;
               $('#'+elem_id).parent().children('.loader').fadeOut(1500, function(){
                   if(params.length===4){
                        callback();
                   }
               });
           }
       }   
       $('#'+elem_id).parent().children('.loader').remove();
      return;
    },
    hasPreloader: function(elem_id){
        if($('#'+elem_id).parent().children('.loader').length>0)
            return true;
        return false;
    },
    hide: function(elem_id, name, size, callback){
        if(preloader.count(elem_id)>0){
              preloader.disable(elem_id);
        }
        if(typeof(elem_id)==='string'){
            $('#'+elem_id).before(this[name][size]);
            $('#preload_'+name+'_'+size).hide();
            $('#preload_'+name+'_'+size).css('height', ($('#'+elem_id).height()/ 2)+'px');
            var params = arguments;
            $('#'+elem_id).fadeOut(500, function(){
                if(params.length===4){
                    callback();
                }
                $('#preload_'+name+'_'+size).css('margin-top', ($('#preload_'+name+'_'+size).height()*0.95)+'px').fadeIn(1500);
            });
            
        }
        return;
    },
    isVisible: function(elem_id){
        if($('#'+elem_id).parent().children('.loader:visible').length>0)
            return true;
        return false;
    },
    show: function(elem_id, name, size, callback){
       if(typeof(elem_id)==='string'){
            var params = arguments;
            var height = preloader.hasPreloader(elem_id) ? $('#preload_'+name+'_'+size).height()*2 : $('#preload_'+name+'_'+size).parent().height();
               
                if(!preloader.isVisible(elem_id)){
                    preloader.disable(elem_id);
                }
                
                if($('#'+elem_id).parent().children(':first-child').attr('id')==elem_id){
                    $('#'+elem_id).before((preloader[name][size]));
                }else{
                    $('#'+elem_id).parent().children(':first-child').before((preloader[name][size]));
                }
               
                $('#preload_'+name+'_'+size).hide();
                
                $('#preload_'+name+'_'+size).css('height', (height/ 2)+'px'); 
                   $('#preload_'+name+'_'+size).css('margin-top', ($('#preload_'+name+'_'+size).height()*0.95)+'px').fadeIn('1000', function(){
                     $('#preload_'+name+'_'+size).fadeOut(400, function(){
                         if(params.length===4){
                            callback();
                            if(preloader.isVisible(elem_id)){
                                preloader.disable(elem_id);
                            }
                         }
                     });
                   }); 
        }
        $('#'+elem_id).fadeIn(1900);
        return;
    },
    gears:{
        32:"<div id='preload_gears_32' class='text-center loader'><img src='images/preload/gears.gif'></div>",
        64:"<div id='preload_gears_64' class='text-center loader'><img src='images/preload/gears.gif'></div>",
    },
    cicle_ball:{
        32:"<div id='preload_cicle_ball_32' class='text-center loader'><img src='images/preload/cicle_ball_32.gif'></div>",
        64:"<div id='preload_cicle_ball_64' class='text-center loader'><img src='images/preload/cicle_ball_64.gif'></div>",
        128:"<div id='preload_cicle_ball_128' class='text-center loader'><img src='images/preload/cicle_ball_128.gif'></div>",
    },
};