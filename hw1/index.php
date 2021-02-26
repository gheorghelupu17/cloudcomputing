<?php
require_once('config.php');
$rustart = getrusage();
$fp = fopen('logs/data.txt', 'a');//opens file in append mode  

 
  

$logData['acces_url'] = $_SERVER['REQUEST_URI'];

// var_dump($_SERVER['REQUEST_URI']);
if ($_SERVER['REQUEST_URI']=='/statics'){
    echo "statics";

    
    die();
}


if ($_SERVER['REQUEST_URI']=='/search-random')
{

 $id = getRandomNumber();
 $hero = getHeroById($id);
 $logData['time']= rutime($ru, $rustart, "stime");

 response($hero,200);
 
}

if ($_SERVER['REQUEST_URI']=='/search-by-name')
{
 $name = $_GET['name'];
 var_dump($name);
 $id = getRandomNumber();
 $hero = getHeroByName($name);
 $logData['time']= rutime($ru, $rustart, "stime");

 response($hero,200);
}

function response($data,$status)
{
    global $rustart,$fp;
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    $ru = getrusage();
    $logData['time']=rutime($ru, $rustart, "utime");
    fwrite($fp, json_encode($logData));  
    fclose($fp); 

    die();
}


function getHeroByName($name)
{
    $curl = curl_init();
global $apiKey;
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://superheroapi.com/api/{$apiKey}/search/{$name}",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: __cfduid=d67bbacf2a4d4f46925dff92988c0f3491614352969'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

}

function getRandomNumber()
{
    $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://numbersapi.com/random/math?json',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
$number=json_decode($response,true);
return $number['number']%10;

}
function getHeroById($id)
{
global $apiKey;

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://superheroapi.com/api/{$apiKey}/{$id}",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: __cfduid=d67bbacf2a4d4f46925dff92988c0f3491614352969'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

}


function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

  

// if ($_REQUEST['REQUEST_URI']=='')