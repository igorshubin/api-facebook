<?php
require 'src/facebook.php';

// Create app instance
$fb = new Facebook(array(
    'appId'  => APP_ID,
    'secret' => APP_SECRET,
    'cookie' => true
));

// get action - default: logout from app
$action = (count($_GET))? key($_GET) : 'app';

// получим FB UID пользователя, который авторизирован
$user = $fb->getUser();

// получаем access token для пользователя
$access_token = $fb->getAccessToken();

if ($user && $access_token) {

    // удаляем разрешения - деавторизуем пользователя
    $fb->api(
       '/me/permissions',
       'DELETE',
       array(
          'access_token' => $access_token
       )
    );

    // clear all session values
    $fb->destroySession();
    
    // logout from FB
    if ($action == 'facebook') {
        $fb->redirect( $fb->getLogoutUrl(array('next'=>'htp://test.teamave.com/') ) );
    } else {
        $fb->redirect('/');
    }
    
    
} else {
    
    $fb->redirect('/');
    
}    


?>

