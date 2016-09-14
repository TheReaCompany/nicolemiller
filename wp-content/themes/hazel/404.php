<?php 
	global $qode_options;
?>

<?php get_header(); ?>

			<?php get_template_part( 'title' ); ?>
			<div class="container">
				<div class="container_inner default_template_holder">
					<div class="page_not_found">
						<h2><?php if($qode_options['404_subtitle'] != ""): echo $qode_options['404_subtitle']; else: ?> <?php _e('The page you are looking can not be found', 'qode'); ?> <?php endif;?></h2>
                        <div class="separator small center" style="margin-top:25px;margin-bottom:25px;"></div>
                        <h4><?php if($qode_options['404_text'] != ""): echo $qode_options['404_text']; else: ?> <?php _e('The page you are looking for does not exist. It may have been moved, or removed altogether.', 'qode'); ?> <?php endif;?></h4>
                        <a class="qbutton with-shadow" href="http://www.nicolemillerartdirector.com/portfolio/"><?php _e('Portfolio', 'qode'); ?></a>
						<a class="qbutton with-shadow" href="<?php echo home_url(); ?>/"><?php if($qode_options['404_backlabel'] != ""): echo $qode_options['404_backlabel']; else: ?> <?php _e('Homepage', 'qode'); ?> <?php endif;?></a>
					</div>
				</div>
			</div>
<?php get_footer(); ?>