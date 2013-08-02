<?php
require 'src/facebook.php';

// Create app instance
$fb = new Facebook(array(
    'appId'  => APP_ID,
    'secret' => APP_SECRET,
    'cookie' => true
));

// получим FB UID пользователя, который авторизирован
$user = $fb->getUser();

// если пользователь разрешил доступ приложению, то можно получить его UID
if($user) {

    // получаем access token для пользователя
    $access_token = $fb->getAccessToken();

    // проверим список разрешений
    $permissions_list = $fb->api(
       '/me/permissions',
       'GET',
       array(
          'access_token' => $access_token
       )
    );
    
    
    // проверим установлены ли нужные нам разрешения
    // если нет, то опять перенаправим на необходимую страницу 
    $permissions_needed = array(
        'email',
        'user_about_me',
        'publish_stream',
        'read_stream',
        'manage_pages',
        'user_photos'
        );
    
    foreach($permissions_needed as $perm) {
       if( !isset($permissions_list['data'][0][$perm]) || $permissions_list['data'][0][$perm] != 1 ) {
          $params = array(
             'scope' => implode(',', $permissions_needed),
             'fbconnect' =>  1,
             'display'   =>  "page",
             'next' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
          );
          $url = $fb->getLoginUrl($params);
          header("Location: {$url}");
          exit();
       }
    }   
    
    
    // если пользователь дал нам все нужные разрешения - перенаправим на profile
    $fb->redirect('/profile.php');

    
/*    
    // получим инфу о страницах, которыми он управляет
    $account = $fb->api(
       '/me',
       'GET',
       array(
          'access_token' => $access_token
       )
    );

    // сохраним инфу в сессии
    $_SESSION['access_token'] = $access_token;
    
    // saving different accounts
    $accounts = (isset($_SESSION['accounts']))? $_SESSION['accounts'] : array();
    $accounts[$account['id']]=$account;
    
    $_SESSION['accounts'] = $accounts;
    $_SESSION['active'] = $account;
*/    

    
} else {
    
   // если нет, то перенаправим на страницу, где можно дать нужные разрешения
   // сгенерируем нужный адрес с помощью метода getLoginUrl()
   $params = array(
      'scope' => implode(',', $permissions_needed),
      'fbconnect' =>  1,
      'redirect_uri' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
   );
   $url = $fb->getLoginUrl($params);
   
   // перенаправим на нужную страницу
   header("Location: {$url}");
   exit();
   
}

?>
