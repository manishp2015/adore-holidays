<?php get_header(); ?>

<!-- Header Section-->

  <div class="hero-image-container">

  <div class="page-banner">
    <img src="https://adore-holidays.net/wp-content/uploads/2020/12/hero-image-adore-holidays-1345x635-1.jpg" width="1345px" height="530px" class="img-fluid" alt="Responsive image" alt="">
  </div>

    <h1 class="sub-1">Luxury Holidays of the World</h1>
    <h6 class="sub-2 mob-sub-2">Specialised in holiday rental villas and concierge services</h6>


  <div class="page-banner-box row">
    <div class="col-lg-4 col-md-6 col-sm-5">
      <div class="card">
<!--
        <div class="card-body bg-light">
          <h3 class="sub-heading-one card-title"><strong>Luxury Holiday Villas Of The World</strong></h3>
          <h6 class="sub-heading-two card-text"><strong>Specialised in holiday rental villas and concierge services</strong></h5>
        </div>
-->

        <div class="card-body bg-light">

          <form action="<?php echo esc_url(site_url('/')); ?>" method="get">
            <div class="form-group mx-sm-3">
              <label class="sub-2">Type your Destination</label>    
              <input id="s" class="form-control" type="search" name="s" placeholder="(eg. Palm Beach)">

            </div>


            <div class="form-group mx-sm-3">
              <input type="number" class="form-control" name="no_of_bedrooms" value="no_of_bedrooms" placeholder="No of Bedrooms">
            </div>

<!-- Check In / Out Dates
            <div class="form-group mx-sm-3">
              <label class="sub-heading-two" for="">Check In</label>
              <input type="date" class="form-control" name="" value="" placeholder="Check In">
            </div>

            <div class="form-group mx-sm-3">
              <label class="sub-heading-two" for="">Check Out</label>
              <input type="date" class="form-control" name="" value="" placeholder="Check Out">
            </div>  
-->

            <div class="form-group mx-sm-3">
                <input class="search-submit" type="submit" value="Search"></input>
            </div>

          </form>
        </div>

      </div>
    </div>
  </div>

  </div>        <!--Hero Image ends> -->

<!-- Villas Section -->
<div class="middle-container">
  <h2>Discover Our Collection</h2>
  <hr class="hr-border">

  <div class="villa-container">

    <div class="row">
      <div class="col-lg-6 col-md-6 col-s-12">
        <div class="villa-a card bg-light margin-spacing">
          <a href="https://adore-holidays.net/villas/bondi-dream/"><img class="card-img-top" src="<?php echo get_theme_file_uri('/images/bondi-dream-australia1.jpg') ?> " alt="bondi-dream-img"></a>
          <div class="card-body">
            <a href="https://adore-holidays.net/villas/bondi-dream/" class="card-img-top" alt="bondi-dream-img"><h5 class="card-title">Bondi Dream, AUSTRALIA</h5></a>
            <p class="card-text">Bedrooms: 4 | Bathroom: 4 | Sleeps 8</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-s-12">
        <div class="villa-a card bg-light margin-spacing">
          <a href="https://adore-holidays.net/villas/chateau-nobel/"><img class="card-img-top" src="<?php echo get_theme_file_uri('/images/chateau-nobel-france-1.jpg') ?>" alt="chateau-nobel-img"></a>
          <div class="card-body">
            <a href="https://adore-holidays.net/villas/chateau-nobel/"><h5 class="card-title">Chateau Nobel, FRANCE</h5></a>
            <p class="card-text">Bedrooms: 5 | Bathroom: 5 | Sleeps 10</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-4 col-md-6 col-s-12">
        <div class="villa-a card bg-light margin-spacing">
          <a href="https://adore-holidays.net/villas/villa-atas/"><img class="card-img-top" src="<?php echo get_theme_file_uri('/images/luxury-villa-house-rent-holidays-bali-383.jpg') ?>" alt="bali-img"></a>
          <div class="card-body">
            <a href="https://adore-holidays.net/villas/villa-atas/"><h5 class="card-title">Villa Atas, BALI</h5></a>
            <p class="card-text">Bedrooms: 4 | Bathroom: 4 | Sleeps 8</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-s-12">
        <div class="villa-a card bg-light margin-spacing">
          <a href="https://adore-holidays.net/villas/villa-boonsri/"><img class="card-img-top" src="<?php echo get_theme_file_uri('/images/luxury-villas-rental-houses-thailand-koh-samui-383.jpg') ?>" alt="thailand-img"></a>
          <div class="card-body">
            <a href="https://adore-holidays.net/villas/villa-boonsri/"><h5 class="card-title">Villa Boonsri, THAILAND</h5></a>
            <p class="card-text">Bedrooms: 5 | Bathroom: 5 | Sleeps 10</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-s-12">
        <div class="villa-a card bg-light margin-spacing">
          <a href="https://adore-holidays.net/villas/villa-de-luxe/"><img class="card-img-top" src="<?php echo get_theme_file_uri('/images/luxury-villa-new-zealand-1.jpg') ?>" alt="new-zealand-img"></a>
          <div class="card-body">
            <a href="https://adore-holidays.net/villas/villa-de-luxe/"><h5 class="card-title">Villa de Lux, NEW ZEALAND</h5></a>
            <p class="card-text">Bedrooms: 5 | Bathroom: 5 | Sleeps 10</p>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>

  <!-- Services Section -->
  <div class="line-spacing"></div>
  <div class="services-container">
    <h2>The Service We Offer</h2>
    <hr class="hr-border">
    

    <div class="row">
      <div class="services card">
        <img src="<?php echo get_theme_file_uri('/images/AirportTransfer-219.png') ?>" class="card-img-top" alt="service-airport-transfer">
        <div class="card-body">
          <h5 class="card-title">Airport Transfer</h5>
        </div>
      </div>

      <div class="services card">
        <img src="<?php echo get_theme_file_uri('/images/Check-in-Check-out-219.png') ?>" class="card-img-top" alt="service-check-in">
        <div class="card-body">
          <h5 class="card-title">Check-In / Out</h5>
        </div>
      </div>

      <div class="services card">
        <img src="<?php echo get_theme_file_uri('/images/Chef-219.png') ?>" class="card-img-top" alt="service-chef">
        <div class="card-body">
          <h5 class="card-title">Chef</h5>
        </div>
      </div>

      <div class="services card">
        <img src="<?php echo get_theme_file_uri('/images/Concierge-Service-219.png') ?>" class="card-img-top" alt="service-conceirge">
        <div class="card-body">
          <h5 class="card-title">Concierge Service</h5>
        </div>
      </div>

      <div class="services card">
        <img src="<?php echo get_theme_file_uri('/images/yacht-219.png') ?>" class="card-img-top" alt="service-yacht">
        <div class="card-body">
          <h5 class="card-title">Yacht</h5>
        </div>

      </div>
    </div>
  </div>

<?php get_footer(); ?>
