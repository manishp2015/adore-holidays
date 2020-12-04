<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php wp_head(); ?>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

    <!-- Navigation Bar -->
    <div class="container-fluid">

      <nav class="navbar navbar-expand-lg navbar-light nav-style">
        <a class="navbar-brand" href="<?php echo site_url() ?>">
          <img src="http://adore-holidays.net/wp-content/uploads/2020/06/Adore-Holidays-Logo-4-e1591537393840.png" width="150px" height="53.5px" alt="adore-holidays-logo">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse " id="navbarTogglerDemo02">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">

            <?php
               wp_nav_menu(array(
                 'theme_location' => 'headerMenuLocation',
                 'menu_class'     => 'navbar-nav ml-auto',
               ));
             ?>

         </ul>

        <?php  ?>
         <div class="nav-item">
              <a href="<?php echo esc_url(site_url('/search')); ?> " class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
              </div>

         <?php     ?>

         <?php 
            if (is_user_logged_in()) { ?>
            <!-- Sign Out 
            <div class="nav-item">
            <a href="<?php echo esc_url(site_url('/my-villa')); ?>"><button type="submit" class="btn btn-sm" style="background-color: #318FB5; color: #B0CAC7">My Villa</button></a>
              <a href="<?php echo wp_logout_url(); ?>"><button type="submit" class="btn btn-sm btn--with-photo" style="background-color: #318FB5; color: #B0CAC7">
              <span class="site_header__avatar"><?php echo get_avatar(get_current_user_id(), 16)?></span>
              <span class="btn__text">Log Out</span>
              
              </button>
              </a>  
              </div>  -->

          <?php  } else { ?>

          <!-- Sign & Log In Button 
              <div class="nav-item">
              <a href="<?php echo wp_login_url(); ?>"><button type="submit" class="btn btn-sm" style="background-color: #318FB5; color: #B0CAC7">Log In</button></a>
              </div>
              <div class="nav-item">
              <a href="<?php echo wp_registration_url(); ?>"><button type="submit" class="btn btn-sm" style="background-color: #318FB5; color: #B0CAC7">Sign Up</button></a>  
              </div> 
              <div class="nav-item">
              <a href="<?php echo esc_url(site_url('/search')); ?> " class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
              </div>  -->
              
              <?php    }
         ?>
         
          

         
              
        </div>
        
          
        
       </nav>
    </div>
