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
$albums = $fb->api(
   '/me/albums',
   'GET',
   array(
      'access_token' => $access_token
   )
);

// get profile from sesion
$profile = (isset($_SESSION['facebook_profile']))? $_SESSION['facebook_profile'] : false;
if (is_array($profile)) extract($profile);

//echo '<pre>';
//print_r($albums);


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
           <h3><?php echo $name; ?>'s Facebook Albums</h3>
           
            <?php require_once '_menu.php'; ?>
           
        </div>
       
       
       <div class="row data_wrap">
           
         <div class="">
             <h4>Albums List</h4>
             
            <ul class="feed_list">
               <?php foreach($albums['data'] as $album): ?>
                
                    <li>
                        
                        <!-- FEED PHOTO - START --> 
                       <div class="feed_photo">
                           
                           <?php
                           
                           $cover = array();
                           if (isset($album['cover_photo'])) {
                           
                                $cover = $fb->api(
                                   '/'.$album['cover_photo'],
                                   'GET',
                                   array(
                                      'access_token' => $access_token
                                   )
                                );                            
                            
                           }
                           
                           ?>
                           
                            <?php if (isset($cover['picture'])):  ?>
                                <a href="<?php echo $cover['source'];  ?>"><img style="padding: 5px;border: 1px solid #ccc;" src="<?php echo $cover['picture'];  ?>" alt="<?php echo $album['name']; ?>"/></a>
                            <?php else: ?>
                                <img src="http://graph.facebook.com/<?php echo $album['id']; ?>/picture" alt="<?php echo $album['name']; ?>"/>
                            <?php endif; ?>
                            
                       </div>
                        <!-- FEED PHOTO - END --> 

                        <!-- FEED TEXT - START --> 
                       <div class="feed_data">
                           
                           <p><a href="<?php echo $album['link']; ?>" target="_blank"><?php echo $album['name']; ?></a> 
                               <span style="color: #999">(<?php echo (isset($album['count']))? $album['count'] : 0; ?> photos)</span>
                           </p>
                           <p><?php if (isset($album['description'])) echo $album['description']; ?></p>


                           <?php if ($album['can_upload'] ): ?>
                                <!-- UPLOAD PHOTO TO ALBUM - START -->
                                <div class="well span5" style="padding-bottom: 0;margin-left: 0">
                                    <form action="/album_post.php" enctype="multipart/form-data" method="POST" onsubmit="return check_upload('<?php echo $album['id']; ?>');">

                                     <h4>Add new image to album:</h4>

                                     <div>
                                          <input type="file" name="file" id="file_<?php echo $album['id']; ?>">
                                          <input type="hidden" name="text" value="">
                                          <input type="hidden" name="album_id" value="<?php echo $album['id']; ?>">
                                          <input class="btn" type="submit" value="Upload">
                                     </div>

                                </form>                               
                                </div>
                                <div class="clearfix"></div> 
                                 <!-- UPLOAD PHOTO TO ALBUM - START -->
                            <?php endif ?>
                           
                           
                           
                         <!-- ACTIONS - START --> 
                         <?php if (isset($album['count']) && $album['count'] ): ?>
                            <div class="pull-right">
                                <a onclick="$('#photos_<?php echo $album['id']; ?>').slideToggle()" href="javascript:void(0)" class="btn btn-mini">Show photos</a>
                            </div>
                            <div id="photos_<?php echo $album['id']; ?>" style="display: none">

                                <?php 
                                    $photos = $fb->api(
                                       '/'.$album['id'].'/photos',
                                       'GET',
                                       array(
                                          'access_token' => $access_token
                                       )
                                    );                            
                                ?>

                                <?php foreach ($photos['data'] as $photo): ?>
                                <div style="float: left;padding: 5px;margin: 5px;border: 1px solid #ccc;min-height: 100px">
                                    <a href="<?php echo $photo['images'][0]['source']; ?>">
                                        <img src="<?php echo $photo['images'][6]['source']; ?>" alt="" />
                                    </a>
                                </div>
                                <?php endforeach; ?>

                            </div>
                            <div class="clearfix"></div> 
                        <?php endif; ?>

                            
                         <div style="margin-top: 5px;"><a class="pull-right" onclick="$('#raw_<?php echo $album['id']; ?>').slideToggle()" href="javascript:void(0)">[ Raw data ]</a></div>
                         <div class="clearfix"></div> 
                         <div id="raw_<?php echo $album['id']; ?>" style="display: none"><pre><?php print_r($album); ?></pre></div>
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
   
    
<script>

    function check_upload ( id ) {
        
        if ( !$('#file_'+id).val() ) {
            alert('Please select file from local disk.');
            $('#file_'+id).focus();
            return false;
        }
            
        return true;

    }

</script>

</body>
</html>