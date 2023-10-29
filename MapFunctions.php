<script>
    //Adds markers based on data in Database
    function newMarker(entry, map, iwindow) { //Passes in the current DataBase Entry
        const latitude = parseFloat(entry.lat);
        const longitude = parseFloat(entry.long);

        const marker = new google.maps.Marker({
            position: {
                lat: latitude,
                lng: longitude
            },
            title: entry.name,
            map: map,
            desc: entry.desc, //Setting properties of marker.
        });

        // const buildingRectangle = new google.maps.Rectangle({
        //     strokeColor: "#FF0000",
        //     strokeOpacity: 0.8,
        //     strokeWeight: 2,
        //     fillColor: "#FF0000",
        //     fillOpacity: 0.35,
        //     map,
        //     bounds: {
        //         north: parseFloat(entry.lat) + 0.00025,
        //         south: parseFloat(entry.lat) - 0.00035,
        //         east: parseFloat(entry.long) + 0.000257,
        //         west: parseFloat(entry.long) - 0.00034,

        //     },
        // })

        // Add a click listener for each marker, and set up the info window.
        marker.addListener("click", () => {
            iwindow.close();
            iwindow.setContent("<b>" + entry.name + "</b><br/><p>" + marker.desc + "</p><br><img style='width:250px; overflow:hidden; padding:10px' src='data:image/png;base64," + entry.image + "'><br><form method='post'><button type='submit' style='width:250px;' name='ReviewLink' value='" + entry.name + "'>Click here to see the reviews!</button></form>");

            iwindow.open(marker.getMap(), marker);
            document.getElementById("review_url").onclick = function() {

            }

        })
    }

    // Initialize and add the map
    function initMap() {
        const uom = {
            lat: 53.4668,
            lng: -2.2339
        };

        var mapTypeStylesArray = [{
                featureType: 'water',
                elementType: 'labels.text', //Looks at element of the feature given
                stylers: [{
                    color: '#FF0000' //Styles text of label of water to red
                }]
            },
            {
                featureType: "poi.school",
                elementType: "geometry.fill",
                stylers: [{
                    color: "#202124"
                }]
            },
            {
                featureType: "road",
                elementType: "labels",
                stylers: [{
                    visibility: "off"
                }]
            },
            {
                featureType: "administrative",
                elementType: "all",
                stylers: [{
                    visibility: "off"
                }]
            },
            {
                featureType: 'poi',
                elementType: "labels",
                stylers: [{
                    saturation: "-40"
                }]
            },
            {
                featureType: "water",
                elementType: "labels",
                stylers: [{
                    visibility: "off"
                }]
            }, {
                stylers: [{
                    hue: "#242526"
                }, {
                    saturation: "-80"
                }]
            },

        ];

        const Map_Boundaries = {
            north: 53.4850,
            south: 53.4400,
            west: -2.2500,
            east: -2.2000,
        }

        let mapOptions = {

            center: uom,
            zoom: 15,
            minZoom: 10,
            maxZoom: 22,
            mapTypeControlOptions: {
                mapTypeIds: ['roadmap', 'hybrid', ]
            },
            styles: mapTypeStylesArray,
            restriction: {
                latLngBounds: Map_Boundaries,
                strictBounds: true,
            },

        }

        let map = new google.maps.Map(document.getElementById("map"), mapOptions);

        const infoWindow = new google.maps.InfoWindow();
        var data = <?php include 'dbmanage.php';
                    getPlaces(); ?>;
        console.log("penis");
        data.forEach((x, i) => newMarker(x, map, infoWindow));;

    }
</script>