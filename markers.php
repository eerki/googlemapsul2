<?php
require_once("connect.php");
$sql = 'SELECT * FROM marker';
//select * from markes
 
if ($result = $mysqli->query($sql)) {
    while($marker = $result->fetch_assoc()){
      $markers[]= [ 
        "lat"=>(float) $marker['latitude'],
         "lng"=>(float) $marker['longitude'],
         "description"=> $marker['description'],
         "name"=> $marker['name'],
         "id"=>(int) $marker['id']
        ];
      }


    $result->close();
}

echo json_encode($markers);