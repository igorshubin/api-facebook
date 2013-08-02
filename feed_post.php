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

// получаем данные из POST
$params = array(
   'message'     => $_POST['message'],
//   'picture'     => $_POST['picture'],
//   'link'        => $_POST['link'],
//   'name'        => $_POST['name'],
//   'caption'     => $_POST['caption'],
//   'description' => $_POST['description'],
);

// добавляем api параметры
$params['access_token'] = $access_token;
$params['privacy']= array('value'=>$_POST['privacy']);


// make post
try {
    
    // строим запрос к API и выполняем его
    $newpost = $fb->api(
       '/me/feed',
       'POST',
       $params
    );

 } catch (FacebookApiException $e) {
     
    var_dump($newpost);
    exit($e);
    
}

$fb->redirect('/feed.php');

?>


