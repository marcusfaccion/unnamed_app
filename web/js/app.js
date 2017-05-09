var _return;

var Icons;

/* Usar com map.hasLayer?
 * var alert = {
    id: null,
    marker: null,
    title: null,
    description: null,
    latlng: null,
}

var alerts = {
    alert: []
}*/

var geoJSON_layer = {
    alerts:{},
    bike_keepers:{},
};

var directions;
var directionsLayer;
var directionsInputControl;
var directionsErrorsControl;
var directionsRoutesControl;
var directionsInstructionsControl;

var me = {
    id: null,
    marker: null,
    circle: null,
    latlng: null,
    latlng_history: {
        items: [],
        getFirst: function(){
            return this.items[0];
        },
        getLast: function(){
            return this.items[this.items.length-1];
        },
        getItem: function(pos){
            return this.items[pos];
        },
        add: function(item){
            this.items.push(item);
        },
        remove: function(start, countDelete){
            this.items.splice(start, countDelete);
        },
        destroy: function(){
            this.items = [];
        }
    },
    setNull: function(){
        this.id = this.marker = this.circle = this.latlng = null;
        this.latlng_history.destroy();
    }
};

var users = {
    user: [],
    new: function(id){
        var me_copy = jQuery.extend({}, me);
        this.user.push(me_copy);
        this.user[this.user.length-1].setNull();
        this.user[this.user.length-1].id = id;
    }
};

var isAjax = false;

/**
 * Variável para configurações e troca de informações em tempo de execução do js.
 * @type Object
 */
var app = {
    layers:{
        alerts: {
        },
        bikeKeepers: {
        },
        selected: null,
    },
    /**
     * Variável para troca de informação entre as rotinas pré requisição http
     * @type type
     */
    request: {
        ajax: null,
        afterAction: function(){;;},
    },
    
    /**
    * Variável que armazena o código para tradução da mensagem (i18n)
    * @type String
    */
    message_code: '',
    user:{
        id: null
    },
    alert:{
      id: null  
    },
    /**
     * Variável para armazenar a resposta sim|não do usuário aos pedidos de confirmação
     * @type Number
     */
    user_confirmation: 0,
}


var selectedlatlng;

var mapbox_accessToken = {
    public_token: 'pk.eyJ1IjoibWFyY3VzZmFjY2lvbiIsImEiOiJjaXNxZ29jcHMwMjRyMnNwaHVxcmRlYjg4In0.eX6DVM4oZvmsJJn8o3B_oA',
    app_token: 'sk.eyJ1IjoibWFyY3VzZmFjY2lvbiIsImEiOiJjaXVxdzdxZ2wwMDNiMnlydnU4bTd2OWJjIn0.acmOjnBC4xZiFQhsJHSNjg'
};

var mapbox_style = {
    app_style: 'mapbox://styles/marcusfaccion/cita05dqg000d2iry8gtjoyi9',
    satellite: 'mapbox://styles/marcusfaccion/ciuqwismc00bt2jodqxhts3r0'
}

var leaflet_style = 
        {
            app_style: 'https://api.mapbox.com/styles/v1/marcusfaccion/cita05dqg000d2iry8gtjoyi9/tiles/256/{z}/{x}/{y}?access_token='+mapbox_accessToken.app_token,
            satellite: 'https://api.mapbox.com/styles/v1/marcusfaccion/ciuqwismc00bt2jodqxhts3r0/tiles/256/{z}/{x}/{y}?access_token='+mapbox_accessToken.app_token
        };

// Área do mapa restrita ao Rio de Janeiro
// http://www.latlong.net/
var map_bounding_box = [
    [-23.1814717, -44.2253863],// vértice ao Suldoeste 
    [-22.724613, -43.054733]  // vértice ao Nordeste
];

//LatLng inicial do mapa (centro do BoundingBox)
var map_center = L.latLngBounds(map_bounding_box).getCenter();
map_center = [map_center.lat, map_center.lng];

var map;
var map_popup_menu = L.popup(); 

// Variavel global de configuração
var map_conf = {
             // leafletJS map <options>
             options:{
               center: map_center,
               zoom:    12.0, // zoom inicial
               maxZoom: 18.0, //limit de aproximação do leafletJS
               minZoom:  11.0, // 0 Limite de distanciamento (visualização a nível continente)
               layers: null,
               crs: L.CRS.EPSG3857, // Sistema de Referenciamento de Coordenadas geográficas utilizando Projeção EPSG3857 mais comum e indicada para web mapas
               bounceAtZoomLimits: false,
               zoomControl: false,
               attributionControl: false,
               // map.setMaxBounds altera dinâmicamente
               maxBounds: map_bounding_box,
               maxBoundsViscosity: 0.0, // 1.0 Não permermite usuário fazer um drag para uma área fora do bbox defino para o mapa, 0.0 permite o usuário fazer o drag mas ao soltar ao fazer o drop é feito um setView para o bbox do mapa
               worldCopyJump: true,
             },
             url_style: leaflet_style.app_style,
             accessToken: mapbox_accessToken.public_token,  
             locate: {
                   setView: true,
                   watch:   true, // para rastrear posição
                   maxZoom: 16,
                   timeout: 20*1000, //20 secs de timeout para geolocalização
                   maximumAge:  0,
                   enableHighAccuracy: true
               },
             popup_menu: {
                   getContent: function(){
                        $.ajax({
                            type: 'GET',
                            url: 'home/build-popup-menu',
                            async: false,
                            success: function(response){
                                _return = response; 
                            }
                       });
                       return _return;
                   }
            }
};

//Adicionando Controle de Geocoding
//var geocoder = L.Control.geocoder().options.geocoder;

/**
 *  Variavel de controle do painel de Novaegação (Rounting/Geocoding)
 * @type Boolean
 * 
 * 0 - manter fechado
 * 1 - manter aberto
 * 2 - indiferente
 */
keepOpenUserNavigationPane = 2;

//Configurando o token de acesso a API MapBox
L.mapbox.accessToken = map_conf.accessToken;

//Iniciando componentes
$('[data-toggle="tooltip"]').tooltip({container: 'body'});
$('[data-toggle="popover"]').tooltip({container: 'body'});

// Inicializar draggable
$('.draggable' ).draggable();

// Inicializar tooltips 
$(document).on('mousemove', 'body', function(){
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});
    $('.leaflet-marker-icon').tooltip({container: 'body'});
});
$(document).on('mousemove', '.modal.in', function(){
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});
});

// Executa ações após a atualização do elemento html 
$(document).on('pjax:success', function(event, data, status, xhr, options) {
      
      //Bikekeeper Pajax request
      if(typeof options.bike_keeper_success === 'function'){
           options.bike_keeper_success();
      }
})

// Exibi preloader durante requisições XHR via Jquery Pjax plugin
$(document).on('pjax:send', function() {
  Loading.show()
})
$(document).on('pjax:complete', function() {
  Loading.hide()
})