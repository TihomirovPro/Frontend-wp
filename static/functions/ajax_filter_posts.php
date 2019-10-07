<?php

function ajax_filter_posts() {
  $cat = $_POST['cat'];
  $args = array(
  'post_type' => 'specialists',
  'posts_per_page' => -1,
  'orderby' => 'date',
  'order' => 'ASC',
  'specialists-tax' => $cat);

  $specialists = new WP_Query( $args );
  while ( $specialists->have_posts() ) : $specialists->the_post(); ?>
    <a class="specialist" href="<?php the_permalink(); ?>">
      <span class="specialist__photo">
        <?php $photo = get_field('photo'); ?>
        <img class="img" src="<?php echo $photo['url']; ?>" alt="<?php echo $photo['alt']; ?>"/>
      </span>
      <span class="specialist__desc">
        <span class="specialist__name"><?php the_title(); ?></span>
        <span class="specialist__activity"><?php the_field('activity'); ?></span>
        <span class="specialist__btn btn">Смотреть</span>
      </span>
    </a>
  <?php wp_reset_postdata(); endwhile;
  die;
}

add_action( 'wp_ajax_ajax_filter', 'ajax_filter_posts' );
add_action( 'wp_ajax_nopriv_ajax_filter', 'ajax_filter_posts' );