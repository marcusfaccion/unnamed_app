var _return;

var Icons;

var isAjax = false;

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
var map_bounding_box = [
    [-22.7721, -43.1613],
    [-23.0661, -43.7894]
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
               maxZoom: 18.0, //limit do leafletJS
               minZoom:  12.0,
               layers: null,
               crs: L.CRS.EPSG3857, // Sistema de Referenciamento de Coordenadas geográficas utilizando Projeção EPSG3857 mais comum e indicada para mapas online
               bounceAtZoomLimits: false,
               zoomControl: false,
               attributionControl: false,
               // map.setMaxBounds altera dinâmicamente
               maxBounds: map_bounding_box,
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
                            url: '?r=home/build-popup',
                            async: false,
                            success: function(response){
                                _return = response; 
                            }
                       });
                       return _return;
                   }
            }
};

var my_location = {
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
};


//Configurando o token de acesso a API MapBox
L.mapbox.accessToken = map_conf.accessToken;
        

$(document).ready(function() {
        //Bootstrapping 
        //
        //verificando se browser suporta API geolocation
        if (!"geolocation" in navigator) {
            alert('Seu navergador não possui suporte a geolocalização ou está desativada');
        }
        //Inicializando opções de mapa e renderizando
        map = new L.map('map', map_conf.options);
              
        //Renderizando mapa
        //map.setView();
        
        ////Inicializando o conteúdo do menu de contexto
        map_popup_menu.setContent(map_conf.popup_menu.getContent());
       
       // Adicionando controle de atribuição/créditos
        L.control.attribution( 
            {
                prefix: "<div class='hidden-xs'>Mapbox, OpenStreetMap, Leaflet</div>"
            }
         ).addTo(map);
       
       //Adicionando Controle de Zoom
        L.control.zoom(
        {
            position: 'bottomright',
            zoomInTitle: 'Aumentar Zoom',
            zoomOutTitle: 'Diminuir Zoom',
        }).addTo(map);
        
        //Adicionando Controle de camadas
        L.control.layers(
        {
            //baseMap layer
            'Mapa': L.tileLayer(leaflet_style.app_style).addTo(map),
            'Satélite': L.tileLayer(leaflet_style.satellite)
        },
        {
           //overLayers
            'Bike Stations': L.mapbox.tileLayer('examples.bike-locations'),
            'Bike Lanes': L.mapbox.tileLayer('examples.bike-lanes'),
            'Alertas': {},
            'Usuários': {}
        },
        {
            position: 'bottomright'
        }).addTo(map);
        
        //EventListeners do mapa
        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);
        map.on('contextmenu', onContextMenuFired);
});
