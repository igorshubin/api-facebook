<?php
require 'src/facebook.php';

// инстанциируем класс библиотеки 
$fb = new Facebook(array(
    'appId'  => APP_ID,
    'secret' => APP_SECRET,
    'cookie' => true
));

// получим FB UID пользователя, который авторизирован
$user = $fb->getUser();
//var_dump($user);

// получаем access token для пользователя
$access_token = $fb->getAccessToken();
//var_dump($access_token);

// check if user is connected
if (!$user || !$access_token)
    $fb->redirect('/');


// get form params
$album_id = $_POST['album_id'];
$text = $_POST['text'];
$file = $_FILES['file'];

$fb->setFileUploadSupport(true);

// post аргументы
$params = array(
    'message' => $text,
    'image' => '@' . $file['tmp_name'],
);
        
// добавляем api параметры
$params['access_token'] = $access_token;


// make post
try {
    
    // строим запрос к API и выполняем его
    $post = $fb->api(
       '/'.$album_id.'/photos',
       'POST',
       $params
    );

    
 } catch (FacebookApiException $e) {
     
    var_dump($post);
    exit($e);
    
}

$fb->redirect('/album.php');


