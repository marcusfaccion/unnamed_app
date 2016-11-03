Icons = {
    interdicoes: L.mapbox.marker.icon({
                    'marker-size': 'small',
                    'marker-symbol': 'roadblock',
                    'marker-color': '#FF0000'
                }),
    roubos_e_furtos:L.icon({
	iconUrl: 'images/icons/marker_roubos_e_furtos_32.png',
	//iconRetinaUrl: 'my-icon@2x.pcng',
	iconSize: [22, 27],
	iconAnchor: [12, 26],
	//popupAnchor: [-3, -76],
	//shadowUrl: 'my-icon-shadow.png',
	//shadowRetinaUrl: 'my-icon-shadow@2x.png',
	shadowSize: [12, 26],
	//shadowAnchor: [22, 94]
    }),
    perigo_na_via: L.mapbox.marker.icon({
        'marker-size': 'small',
        'marker-symbol': 'danger',
        'marker-color': '#FF0000'
    }),
    outro: L.mapbox.marker.icon({
        'marker-size': 'small',
        'marker-symbol': 'embassy',
        'marker-color': '#FF0000'
    }),
};
