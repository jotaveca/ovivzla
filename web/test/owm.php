<?php
//ini_set('display_errors', 1);
//echo ' allow_url_fopen = ' . ini_get('allow_url_fopen') . "\n";

 
//get JSON
$json = file_get_contents('http://api.openweathermap.org/data/2.5/weather?lat=10.34&lon=-67.04&appid=44fb250963a09f36fb5c40be59ce0b98');
//$json = file_get_contents('http://api.openweathermap.org/data/2.5/weather?id=3633622&appid=44fb250963a09f36fb5c40be59ce0b98&units=metric');
 //Los Teques = 3633622
 //lat, lon [ -67.04, 10.34 ]

 //var_dump($json);
 
 //decode JSON to array
 $data = json_decode($json,true);

 //show data
 var_dump($data);

 //description
 echo $data['weather'][0]['description'];
 //temperature
 echo $data['main']['temp'];
 
 
//44fb250963a09f36fb5c40be59ce0b98

?>