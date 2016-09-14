<?php

if (!function_exists('qode_comment')) {
function qode_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>

<li>                        
	<div class="comment">
		<div class="image"> <?php echo get_avatar($comment, 90); ?> </div>
		<div class="text">
			<h4 class="name"><?php echo get_comment_author_link(); ?></h4>
			<span class="comment_date"><i class="icon_clock_alt" aria-hidden="true"></i> <?php comment_time('d.m.Y '); ?><?php _e('at', 'qode'); ?> <?php comment_date('H:i'); ?></span>
			<?php comment_reply_link( array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']) ) ); ?>
			<div class="text_holder" id="comment-<?php echo comment_ID(); ?>">
				<?php comment_text(); ?>
			</div>
		</div>
	</div>                          
                
<?php if ($comment->comment_approved == '0') : ?>
<p><em><?php _e('Your comment is awaiting moderation.', 'qode'); ?></em></p>
<?php endif; ?>
<?php 
}
}
?>