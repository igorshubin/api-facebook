<?php
//@session_destroy();
@session_start();

require 'src/facebook.php';

// Create app instance
$fb = new Facebook(array(
    'appId'  => APP_ID,
    'secret' => APP_SECRET,
    'cookie' => true
));

// получим FB UID пользователя, который авторизирован
$user = $fb->getUser();

//var_dump($user);
//var_dump($_GET);
//exit;

// check if user is connected
if ($user)
    $fb->redirect('/profile.php');

?>

<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Facebook API</title>

<link rel="stylesheet" href="/css/reset.css" type="text/css" />
<link rel="stylesheet" href="/css/bootstrap.css" type="text/css" />
<link rel="stylesheet" href="/css/style.css" type="text/css" />

<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.js"></script>

</head>


<body>

<div id="main" class="container">
   <div class="content">    
    
        <div class="page-header">
            <h3>Facebook API</h3>
            <a href="/login.php" class="btn primary large">Login To Facebook</a>
        </div>
       
       <div class="row span3 well" style="margin-left: 0">
          
           Please accept all permission requests.
          
      </div>       
       
            
   </div>       
</div>
       
</body>
</html>