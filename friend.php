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

// создаем запрос
$friends = $fb->api(
   '/me/friends',
   'GET',
   array(
      'access_token' => $access_token
   )
);

// get profile from sesion
$profile = (isset($_SESSION['facebook_profile']))? $_SESSION['facebook_profile'] : false;
if (is_array($profile)) extract($profile);

//echo '<pre>';
//print_r($friends);

/*
friends_about_me        
friends_activities      
friends_birthday
friends_checkins        
friends_education_history       
friends_events
friends_games_activity      
friends_groups      
friends_hometown
friends_interests       
friends_likes       
friends_location
friends_notes       
friends_online_presence     
friends_photo_video_tags 
friends_photos      
friends_relationship_details        
friends_relationships
friends_religion_politics       
friends_status      
friends_subscriptions
friends_videos      
friends_website     
friends_work_history
 */


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
           <h3><?php echo $name; ?>'s Facebook Friends</h3>
           
            <?php require_once '_menu.php'; ?>
           
        </div>
       
       
       <div class="row data_wrap">
           
         <div class="">
             <h4>Friends List</h4>
             
            <ul class="feed_list">
               <?php foreach($friends['data'] as $friend): ?>
                
                    <li>
                        
                        <!-- FEED PHOTO - START --> 
                       <div class="feed_photo">
                          <img src="http://graph.facebook.com/<?php echo $friend['id']; ?>/picture" alt="<?php echo $friend['name']; ?>"/>
                       </div>
                        <!-- FEED PHOTO - END --> 

                        <!-- FEED TEXT - START --> 
                       <div class="feed_data">
                          <p><a href="http://facebook.com/profile.php?id=<?php echo $friend['id']; ?>" target="_blank"><?php echo $friend['name']; ?></a></p>
                          <p><?php echo $friend['message']; ?></p>
                          
                         <!-- LINK TYPE - START -->
                          <?php if( $friend['type'] == 'link' ): ?>
                            <div>

                               <div class="post_picture">
                                  <?php if( isset($friend['picture']) ): ?>
                                      <a target="_blank" href="<?php echo $friend['link']; ?>">
                                        <img src="<?php echo $friend['picture']; ?>" width="90" />
                                      </a>
                                  <?php endif; ?>
                               </div>

                               <div class="post_data_again">
                                  <p><a target="_blank" href="<?php echo $friend['link']; ?>"> <?php echo $friend['name']; ?></a></p>
                                  <p><small><?php echo $friend['caption']; ?></small></p> <p><?php echo $friend['story']; ?></p>
                               </div>

                               <div class="clearfix"></div>
                            </div>
                          <?php endif; ?>
                         <!-- LINK TYPE - END -->

                         <!-- ACTIONS - START --> 
                         <div style="margin-top: 5px;"><a class="pull-right" onclick="$('#feed_<?php echo $friend['id']; ?>').slideToggle()" href="javascript:void(0)">[ Raw data ]</a></div>
                         <div class="clearfix"></div>
                         
                         <div id="feed_<?php echo $friend['id']; ?>" style="display: none"><pre><?php print_r($friend); ?></pre></div>
                         <div class="clearfix"></div>                         
                         <!-- ACTIONS - END -->    
                         
                       </div>
                        <!-- FEED TEXT - END --> 
                        
                        
                       <div class="clearfix"></div>
                    </li>
                
               <?php endforeach; ?>
            </ul>
         </div>           
           
       </div>
     
      
       
   </div>
</div>
   

</body>
</html>