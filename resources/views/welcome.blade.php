<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pharmacier</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
</head>
<style>
    #map {
        height: calc(90vh);
    }
</style>

<body class="antialiased">
    <div id="demo"></div>
    <div id="map"></div>
    <map></map>
</body>
<script>
    var x = document.getElementById("demo");
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
    function showPosition(position) {
        let lat = position.coords.latitude;
        let lon = position.coords.longitude;
        var map = L.map('map').setView([lat,lon],13);
        var tiles = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'pharmacy',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1
        }).addTo(map);
        var circle = L.circle([lat,lon], {
            color: 'red',
            fillColor: 'red',
            fillOpacity: 1,
            radius: 5
        }).addTo(map);
        circle.bindPopup("<p>Your location</p>").openPopup();
        x.innerHTML = "Latitude: " + lat + 
        "<br>Longitude: " + lon;
        fetch('{{  url('') }}/api/find/' + lat + "/" + lon)  
            .then(response => response.text())
            .then(data => {
                data = JSON.parse(data);
                data.list.forEach(function(item){
                    var marker = L.marker([item.lat,item.lon]).addTo(map); 
                    marker.bindPopup(
                    `<b>name</b> = <span>` + item.name + `</span><br>`
                    + `<b>address</b> = <span>` + item.address + `</span><br>`
                    );
                });
            })
            .catch(error => {
                console.log(error);
                x.innerHTML = "Not found any near Pharmacy."
        // handle the error
    });
    }
</script>

</html>