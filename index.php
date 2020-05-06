<?php

require_once("connect.php");
if(isset($_POST['action'])){
    $sql = "INSERT INTO marker (name, latitude, longitude, description)
    VALUES ('".$_POST["name"]."','".$_POST["latitude"]."','".$_POST["longitude"]."','".$_POST["description"]."')";

if ($mysqli->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}

print_r($marker);
}

$id = $_POST['id'];

if (isset($_POST['delete'])) {
    $delete = "DELETE FROM marker WHERE id = '$id'";

    if ($mysqli->query($delete) === TRUE) {
        $referer = $_SERVER['HTTP_REFERER'];
        header("Location: $referer");
    } else {
        echo "Error deleting record: " . $mysqli->error;
    }
}

$name = $_POST['name'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$description = $_POST['description'];

if (isset($_POST['update'])) {
    $update = "UPDATE `marker` SET `name`='$name', `latitude`=$latitude,`longitude`='$longitude', `description`='$description',`edited` = CURRENT_TIMESTAMP WHERE id = '$id'";

    if ($mysqli->query($update) === TRUE) {
        $referer = $_SERVER['HTTP_REFERER'];
        header("Location: $referer");
    } else {
        echo "Error updating record: " . $mysqli->error;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Lihtne kaart</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<form method="post" style="display: inline-block;">
    <input name="name" placeholder="Add name"><br>
    <input name="latitude" id="lat" placeholder="Add latitude"><br>
    <input name="longitude" id="lng" placeholder="Add latitude"><br>
    <textarea name="description" placeholder="Add description"></textarea><br>
    <button name="action" value="save">Save</button>
</form>

<div id="map"></div>
<script>
    var map;

    function createMarker(data) {

        console.log(data);

        var infowindow = new google.maps.InfoWindow({
            content: "<form method=post><div><input name=name value='" + data.name + "'></div><div><input name=latitude value='" + data.lat + "'></div> <div><input name=longitude value='" + data.lng + "'></div><input name=description value='" + data.description + "'><br><button name=update value=Update>Update</button><button name=delete value=Delete>Delete</button><input type =hidden name=id value="+ data.id +"></form>"
        });
    
        var marker = new google.maps.Marker({ 
            position: data,
            map: map,
        });

        marker.addListener('click', function() {
            infowindow.open(map, marker);
            map.setZoom(10);
            map.setCenter(marker.getPosition());
        });
    }

    function initMap() {

        fetch('markers.php')
        .then(function(response){
            return response.json();
        })
        .then(function(data) {
            for (k in data) {
                console.log(data[k]);
                createMarker(data[k]);
            }
        })
        .catch(function(err) {
            console.log('Fetch Error :-S', err);
        });


        var start = {lat: 58.232693, lng: 22.503854};

        map = new google.maps.Map(document.getElementById('map'), {
            center: start,
            zoom: 8
        });

        map.addListener('click', function(e) {

            console.log(e.latLng.lat())
            console.log(e.latLng.lng())

            var location = {lat: e.latLng.lat(), lng: e.latLng.lng()}
            createMarker(location);
            document.getElementById('lat').value = e.latLng.lat();
            document.getElementById('lng').value = e.latLng.lng();
        });
    
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?callback=initMap"
        async defer></script>
</body>
</html>