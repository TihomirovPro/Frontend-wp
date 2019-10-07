<?php

function the_posts_in_category($sort, $cat) {
  if(!empty($sort)):
    foreach( $sort as $filter ):
      $tag = get_term( $filter ); ?>
      <div class="name"><?php echo $tag->name; ?></div>

      <?php $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC',
        'category_name' => $cat,
        'tag_id' => $filter );

      $equipment = new WP_Query( $args );
      while ( $equipment->have_posts() ) :
        $equipment->the_post(); ?>
        <a class="categoryCard" href="<?php the_permalink(); ?>">
          <span class="categoryCard__arrow">
            <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M0.589844 10.59L5.16984 6L0.589844 1.41L1.99984 0L7.99984 6L1.99984 12L0.589844 10.59Z" fill="white"></path>
            </svg>
          </span>
          <span class="categoryCard__image">
            <img class="categoryCard__img" src="<?php the_field('thumbnail'); ?>" alt="" role="presentation"/>
          </span>
          <span class="categoryCard__title">
            <span class="categoryCard__name"><?php the_title(); ?></span>
            <span class="categoryCard__desc"><?php the_field('excerpt'); ?></span>
          </span>
          <?php if(get_field('base_chars')): ?>
            <span class="categoryCard__info">
              <?php while(has_sub_field('base_chars')): ?>
                <span class="categoryCard__wrap">
                  <span class="categoryCard__infoTitle"><?php the_sub_field('char'); ?></span>
                  <span class="categoryCard__text"><?php the_sub_field('text'); ?></span>
                </span>
              <?php endwhile; ?>
            </span>
          <?php endif; ?>
        </a>
      <?php wp_reset_postdata(); endwhile; 
    endforeach;
  else: 
    $args = array(
      'post_type' => 'post',
      'posts_per_page' => -1,
      'orderby' => 'date',
      'order' => 'ASC',
      'category_name' => $cat);
      
    $equipment = new WP_Query( $args );
    while ( $equipment->have_posts() ) :
      $equipment->the_post(); ?>
      <a class="categoryCard" href="<?php the_permalink(); ?>">
        <span class="categoryCard__arrow">
          <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.589844 10.59L5.16984 6L0.589844 1.41L1.99984 0L7.99984 6L1.99984 12L0.589844 10.59Z" fill="white"></path>
          </svg>
        </span>
        <span class="categoryCard__image">
          <img class="categoryCard__img" src="<?php the_field('thumbnail'); ?>" alt="" role="presentation"/>
        </span>
        <span class="categoryCard__title">
          <span class="categoryCard__name"><?php the_title(); ?></span>
          <span class="categoryCard__desc"><?php the_field('excerpt'); ?></span>
        </span>
        <span class="categoryCard__info">
          <?php if(get_field('base_chars')):
            while(has_sub_field('base_chars')): ?>
              <span class="categoryCard__wrap">
                <span class="categoryCard__infoTitle"><?php the_sub_field('char'); ?></span>
                <span class="categoryCard__text"><?php the_sub_field('text'); ?></span>
              </span>
            <?php endwhile;
          endif; ?>
        </span>
      </a>
    <?php wp_reset_postdata(); endwhile; 
  endif;

}