<?php
/**
 * The template for displaying the footer.
 * @package Core Fitness
 */
?>
    <div  id="footer" class="copyright-wrapper">
      <div class="container">
        <div class="footerinner row">
          <div class="col-md-3 col-sm-3">
              <?php dynamic_sidebar('footer-1');?>
          </div>
          <div class="col-md-3 col-sm-3">
              <?php dynamic_sidebar('footer-2');?>
          </div>
          <div class="col-md-3 col-sm-3">
              <?php dynamic_sidebar('footer-3');?>
          </div>
          <div class="col-md-3 col-sm-3">
              <?php dynamic_sidebar('footer-4');?>
          </div>
        </div>
      </div>
      <div class="inner">
        <div class="copyright text-center">
          <p><?php echo esc_html(get_theme_mod('the_wp_fitness_footer_text',__('Fitness WordPress Theme By','core-fitness'))); ?> <?php core_fitness_credit(); ?></p>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <?php wp_footer(); ?>
  </body>
</html>