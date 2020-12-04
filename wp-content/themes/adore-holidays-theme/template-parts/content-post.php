<div class="card-container">
          <div class="card" style="background-color: #f0ece3">
            <div class="card-body">

          <h3 class="card-title sub-heading-two"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>         
          <div class="metabox">
            <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('M-j-Y'); ?>  in <?php echo get_the_category_list(', '); ?></p>
          </div>

          <div class="card-text para-space">
            <?php the_excerpt(); ?>
          </div>

            <p><a class="btn btn-secondary btn-sm" href="<?php the_permalink(); ?>">Continue Reading &raquo;</a></p>      


            </div>
          </div>   
        </div>