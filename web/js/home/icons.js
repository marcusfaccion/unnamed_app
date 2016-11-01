Icons = {
    interdicoes: L.mapbox.marker.icon({
                    'marker-size': 'small',
                    'marker-symbol': 'roadblock',
                    'marker-color': '#FF0000'
                }),
    roubos_e_furtos:L.icon({
	iconUrl: 'images/icons/marker_roubos_e_furtos_32.png',
	//iconRetinaUrl: 'my-icon@2x.pcng',
	iconSize: [32, 37],
	iconAnchor: [22, 36],
	//popupAnchor: [-3, -76],
	//shadowUrl: 'my-icon-shadow.png',
	//shadowRetinaUrl: 'my-icon-shadow@2x.png',
	shadowSize: [32, 937],
	//shadowAnchor: [22, 94]
    }),
    perigo_na_via: L.mapbox.marker.icon({
        'marker-size': 'small',
        'marker-symbol': 'danger',
        'marker-color': '#FF0000'
    }),
    outros: L.mapbox.marker.icon({
        'marker-size': 'small',
        'marker-symbol': 'roadblock',
        'marker-color': '#FF0000'
    }),
};
