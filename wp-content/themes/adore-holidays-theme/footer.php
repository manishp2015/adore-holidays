<!-- Footer Section-->
<div class="footer-container">
  <div class="row">
    <div class="footer col-lg-4 col-md-6">
      <a href="<?php echo site_url() ?>"><img src="http://adore-holidays.net/wp-content/uploads/2020/06/Adore-Holidays-Logo-4-e1591537393840.png" width="150px" height="53.5px" alt="adore-holidays-logo">
        <br>
        <a href="https://www.facebook.com/"><i class="fab fa-2x fa-facebook"></i></a>
        <a href="https://www.linkedin.com/company/adore-villa-rent-holidays-luxury/"><i class="fab fa-2x fa-linkedin-in"></i></a>
        <a href="https//www.instagram.com/"><i class="fab fa-2x fa-instagram-square"></i></a>

    </div>

    <!-- Middle Part -->
    <div class="footer col-lg-4 col-md-6";>
        
      <h4>Adore Holidays</h4>
      
      <div class="footer-spacing"> 
      <?php
        wp_nav_menu(array(
          'theme_location' => 'footerMenuLocation'
        ));
      ?>
      </div>
    </div>

    <div class="footer col-lg-4">
      <h4>Our Office</h4>
      <p> <i class="fas fa-map-marker-alt"></i> Suite 601/90, Pitt Street,</p>
              <p>Sydney, NSW 2009, Australia</p>

              <p><i class="fas fa-envelope"></i> julian@adore-holidays.net</p> 
              
              <p><i class="fas fa-phone-alt"></i> +61 407 008 176</p>      
      
      
    </div>

    <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>

  </div>

</div>
<div class="footer-bottom">
  <p>Copyright © Adore Holidays 2020</p>
  
</div>

<script>
//Get the button:
mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

</script>


<?php wp_footer(); ?>

</body>
</html>
