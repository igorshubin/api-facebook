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

// feed id to delete
$action_id = key($_GET);
//var_dump($action_id);


// make post
try {
    
    // строим запрос к API и выполняем его
    $fb->api(
       $action_id,
       'DELETE',
       array('access_token' => $access_token)
    );

 } catch (FacebookApiException $e) {
     
    exit($e);
    
}

$fb->redirect('/feed.php');

?>


