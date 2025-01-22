<?php
global $themify;
$post_id = get_the_id();
$client = get_post_meta($post_id, 'project_client', true);
$services = get_post_meta($post_id, 'project_services', true);
$date = $themify->hide_date!=='yes'?get_post_meta($post_id, 'project_date', true):false;
$launch = get_post_meta($post_id, 'project_launch', true);
if ($client || $services || $date || $launch) :
	?>
	<div class="project-meta">
		<?php if ($client) : ?>
			<div class="project-client">
				<strong><?php _e('Client', 'themify'); ?></strong>
				<?php echo $client ?>
			</div>
		<?php endif; ?>

		<?php if ($services) : ?>
			<div class="project-services">
				<strong><?php _e('Services', 'themify'); ?></strong>
				<?php echo $services ?>
			</div>
		<?php endif; ?>

		<?php if ($date): ?>
			<div class="project-date">
				<strong><?php _e('Date', 'themify'); ?></strong>
				<?php echo $date ?>
			</div>
		<?php endif; ?>

		<?php if ($launch) : ?>
			<div class="project-view">
				<strong><?php _e('View', 'themify'); ?></strong>
				<a target="_blank" href="<?php echo esc_url($launch); ?>"><?php _e('Launch Project', 'themify'); ?></a>
			</div>
		<?php endif; ?>
	</div>
<?php endif; // $client || $services || $date || $launch ?>
