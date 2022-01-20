<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pharmacier</title>
</head>


<body class="antialiased">
    <p>Click the button to get your coordinates.</p>
    <p id="demo">wait...</p>
    <div id="mydiv"></div>
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
        x.innerHTML = "Latitude: " + lat + 
        "<br>Longitude: " + lon;
        fetch('{{  url('') }}/api/find/' + lat + "/" + lon)  
            .then(response => response.text())
            .then(data => {
                console.log(data);
                data = JSON.parse(data);
                data.list.forEach(function(item){
                    var mydiv = document.getElementById("mydiv");
                    var newcontent = document.createElement('div');
                    newcontent.innerHTML = `<b>name</b> = <span>` + item.name + `</span><br>`
                    + `<b>address</b> = <span>` + item.address + `</span><br>`
                    + `<b>phone</b> = <span>` + item.phone + `</span><br><br><br><br><br><br>`
                    ;
                    while (newcontent.firstChild) {
                        mydiv.appendChild(newcontent.firstChild);
                    }
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