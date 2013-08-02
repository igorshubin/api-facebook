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

// получим ленту новостей пользователя
$feeds = $fb->api(
   '/me/feed',
   'GET',
   array(
      'access_token' => $access_token
   )
);

// get profile from sesion
$profile = (isset($_SESSION['facebook_profile']))? $_SESSION['facebook_profile'] : false;
if (is_array($profile)) extract($profile);

//echo '<pre>';
//print_r($feeds);

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
           <h3><?php echo $name; ?>'s Facebook Feeds</h3>
           
        <?php require_once '_menu.php'; ?>
           
        </div>
       
       
       <div class="row data_wrap">
           
         <div class="">
             <h4>Feeds List</h4>
             
            <ul class="feed_list">
               <?php foreach($feeds['data'] as $post): ?>
                
                    <li>
                        
                        <!-- FEED PHOTO - START --> 
                       <div class="feed_photo">
                          <img src="http://graph.facebook.com/<?php echo $post['from']['id']; ?>/picture" alt="<?php echo $post['from']['name']; ?>"/>
                       </div>
                        <!-- FEED PHOTO - END --> 

                        <!-- FEED TEXT - START --> 
                       <div class="feed_data">
                          <p><a href="http://facebook.com/profile.php?id=<?php echo $post['from']['id']; ?>" target="_blank"><?php echo $post['from']['name']; ?></a></p>
                          <p><?php if (isset($post['message'])) echo $post['message']; ?></p>
                          
                         <!-- LINK TYPE - START -->
                          <?php if( $post['type'] == 'link' ): ?>
                            <div>

                               <div class="post_picture">
                                  <?php if( isset($post['picture']) ): ?>
                                      <a target="_blank" href="<?php echo $post['link']; ?>">
                                        <img src="<?php echo $post['picture']; ?>" width="90" />
                                      </a>
                                  <?php endif; ?>
                               </div>

                               <div class="post_data_again">
                                   <p><a target="_blank" href="<?php echo $post['link']; ?>"> <?php if (isset($post['name'])) echo $post['name']; ?></a></p>
                                  <p><small><?php if (isset($post['caption'])) echo $post['caption']; ?></small></p> <p><?php echo $post['story']; ?></p>
                               </div>

                               <div class="clearfix"></div>
                            </div>
                          <?php endif; ?>
                         <!-- LINK TYPE - END -->

                         <!-- ACTIONS - START --> 
                        <div class="pull-right">
                            <?php foreach($post['actions'] as $action): ?>
                              <a href="<?php echo $action['link'] ?>" class="btn btn-mini"><?php echo $action['name'] ?></a>
                            <?php endforeach; ?>
                              <a onclick="return feed_delete('<?php echo $post['id']; ?>');" href="javascript:void(0)" class="btn btn-mini btn-warning">Delete</a>
                        </div>
                         <div class="clearfix"></div>
                         
                         <div style="margin-top: 5px;"><a class="pull-right" onclick="$('#feed_<?php echo $post['id']; ?>').slideToggle()" href="javascript:void(0)">[ Raw data ]</a></div>
                         <div class="clearfix"></div>
                         <div id="feed_<?php echo $post['id']; ?>" style="display: none"><pre><?php print_r($post); ?></pre></div>
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
           
           
        <div class="row data_wrap">

           <div class="">
               <h4>Post New Feed</h4>
               
                <form method="POST" action="/feed_post.php" class="form-stacked">
                   <label for="message">Message: <span style="color: red">*</span></label>
                   <input class="span5" type="text" id="message" name="message" placeholder="Message of post" />
                   
                   <!--
                   <label for="picture">Picture:</label>
                   <input class="span5" type="text" id="picture" name="picture" placeholder="Picture of post" />
                   <label for="link">Link:</label>
                   <input class="span5" type="text" id="link" name="link" placeholder="Link of post" />
                   <label for="name">Name:</label>
                   <input class="span5" type="text" id="name" name="name" placeholder="Name of post" />
                   <label for="caption">Caption:</label>
                   <input class="span5" type="text" id="caption" name="caption" placeholder="Caption of post" />
                   <label for="description">Description:</label>
                   <input class="span5" type="text" id="description" name="description" placeholder="Description of post" />
                   -->

                   <label for="privacy">Visible to:</label>
                   <select name="privacy">
                       <option value="EVERYONE">To All</option>
                       <option value="ALL_FRIENDS">To My Friends</option>
                       <option value="SELF">To Myself</option>
                   </select>
                   
                   <div class="actions">
                      <input type="submit" class="btn primary" value="Post" />
                      <input type="reset" class="btn" value="Reset" />
                   </div>
                </form>                   
               
           </div>
           
      </div>
       
       
      
       
   </div>
</div>
    
    
<script> 
function feed_delete( id ) {

    if (confirm('Are you sure to delete this post?'))
       location.href = '/feed_delete.php?'+id;

    return false;

}
</script>
    

</body>
</html>