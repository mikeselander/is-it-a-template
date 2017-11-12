<?php

?>
<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo esc_html( get_bloginfo( 'name' ) ); ?></title>
	<meta name="description" content="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>" />

	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
	<meta property="og:description" content="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>" />
	<meta property="og:url" content="<?php echo esc_url( site_url() ); ?>" />
	<meta property="og:site_name" content="Is It a Template?" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:description" content="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>" />
	<meta name="twitter:title" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
	<meta name="twitter:site" content="@Mike_Selander" />
	<meta name="twitter:creator" content="@mikeselander" />
	<link href="/assets/images/favicon.png" rel="shortcut icon">

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
