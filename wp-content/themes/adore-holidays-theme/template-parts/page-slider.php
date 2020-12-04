<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">

    <?php while(have_rows('slider')) : the_row(); ?>
    <div class="carousel-item <?php if ( get_row_index() == 1) echo 'active'; ?>">
        <?php
          $image = get_sub_field('image');
          //print_r($image);
          $image_url = $image['sizes']['villaSlideshow'];
        ?>

        <a href="<?php echo ($image['url']); ?>" class="gallery-image" data-lightbox="gallery" data-title="<?php echo ($image['title']); ?>" >
        <img src="<?php echo $image_url; ?>" class="d-block w-100" alt="...">
        </a>
    </div>
    <?php endwhile;?>

  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>


<script>
    lightbox.option({
      'resizeDuration': 200,
      'wrapAround': true
    })
</script>