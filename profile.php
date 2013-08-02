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

// получим ленту новостей активной страницы
$data = $fb->api(
   '/me/',
   'GET',
   array(
      'access_token' => $access_token
   )
);

/*
$fql = 'SELECT name from user where uid = ' . $fb_uid;
$ret_obj = $facebook->api(array(
   'method' => 'fql.query',
   'query' => $fql,
 ));
*/


// get profile vars
extract($data);
//echo '<pre>';
//print_r($profile);

// save profile to sesion
$_SESSION['facebook_profile']=$data;

// format education list
if (isset($education)) {
    $edu = array();
    foreach ($education as $school) {
        $string = $school['type'].': '.$school['school']['name']; 
        if (isset($school['year']['name'])) $string .= ', '.$school['year']['name'];
        $edu[] = $string;
    }
}    

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
           <h3><?php echo $name; ?>'s Facebook Profile</h3>
           
           <?php require_once '_menu.php'; ?>

        </div>
       
       
       
       
       <div class="row data_wrap">
             <h4><?php echo $name; ?>'s Profile</h4>
             
                <div class="data_line">
                    
                    <div class="row data_photo">
                       <div class="span2">
                            <img src="http://graph.facebook.com/<?php echo $id; ?>/picture?type=large" alt="<?php echo $name; ?>"/>
                       </div>
                       <div class="span9">
                           <ul>
                               <li><strong>ID:</strong> <?php echo $id; ?></li>
                               <li><strong>Firsrt Name:</strong> <?php echo $first_name; ?></li>
                               <li><strong>Last Name:</strong> <?php echo $last_name; ?></li>
                               <li><strong>Email:</strong> <?php echo $email; ?></li>
                               <li><strong>Gender:</strong> <?php echo $gender; ?></li>
                               
                               <li><strong>Location:</strong> <?php echo (isset($hometown))? $hometown['name'] : '&nbsp;'; ?></li>
                               <li><strong>Education:</strong> <?php echo (isset($edu))? implode(', ', $edu) : '&nbsp;'; ?></li>
                           </ul>
                       </div>
                    </div>

                    <div class="row data_action">
                        <div class="pull-right">
                            <a target="_blank" href="<?php echo $link; ?>" class="btn btn-mini">View Page</a>
                        </div> 
                    </div>
                    
                    <div class="row data_raw">
                        <a class="pull-right" onclick="$('#post_<?php echo $id; ?>').slideToggle()" href="javascript:void(0)">[ Raw data ]</a>
                        <div class="clearfix"></div>
                        <div id="post_<?php echo $id; ?>" class="hide"><pre><?php print_r($data); ?></pre></div>
                        <div class="clearfix"></div>
                    </div>                    
                    
                </div>
             
      </div>           
       
       
   </div>
</div>
    
    
</body>
</html>