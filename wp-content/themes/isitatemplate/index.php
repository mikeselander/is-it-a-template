<?php

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Is It a Template?</title>

	<?php wp_head(); ?>
  </head>
  <body>
    <div id="app" class="app-wrapper"></div>
	<footer class="footer">
		<p>
			Built with
			<a href="https://facebook.github.io/react/"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/react-logo.svg' ); ?>"></a> &
			<a href="https://wordpress.org"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/wp-logo.svg' ); ?>"></a> by <a href="https://mikeselander.com">Mike Selander</a>
		</P
	</footer>
  </body>
  <?php wp_footer(); ?>
</html>
