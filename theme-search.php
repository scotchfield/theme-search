<?php
/*
Plugin Name: Theme Search
Plugin URI: http://sgrant.ca/
Description: Search for content inside of the active WordPress theme
Author: Scott Grant
Version: 1.0
Author URI: http://sgrant.ca/
*/

class ThemeSearch {

	/**
	 * The domain for localization.
	 */
	const DOMAIN = 'theme-search';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		add_theme_page(
			'Theme Search',
			'Theme Search',
			'edit_theme_options',
			'theme-search',
			array( $this, 'admin_page' )
		);
	}

	public function admin_page() {
?>
<h1>Theme Search</h1>
<?php

		if ( isset( $_POST[ 'search' ] ) ) {
			$search = $_POST[ 'search' ];

			$theme = wp_get_theme();
			$stylesheet = get_stylesheet();
			$files = $theme->get_files();

			echo '<ul>';
			foreach ( $files as $key => $file ) {
				$f = fopen( $file, 'r' );
				$content = fread( $f, filesize( $file ) );

				if ( stripos( $content, $search ) !== FALSE ) {
					echo '<li><a href="theme-editor.php?file=' .
						urlencode( $key ) . '&theme=' .
						urlencode( $stylesheet ) . '">' . $file . '</a></li>';
				}

				fclose( $f );
			}
			echo '</ul>';
		}

?>
<form method="post" action="themes.php?page=theme-search">
<p>Search for: <input type="text" name="search" /></p>
</form>
<?php

	}

}

$wp_themesearch = new ThemeSearch();
