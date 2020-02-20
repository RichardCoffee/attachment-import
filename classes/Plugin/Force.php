<?php

/**
 *  classes/Plugin/Force.php
 *
 *  based on https://gist.github.com/simplenotezy/c6ce5ffcdd0e420f4e4a606b2506c3a3
 *  original file located in vendor/install-wp-plugin.php
 */

/*
 * Hide the 'Activate Plugin' and other links when not using QuietSkin as these links will
 * fail when not called from /wp-admin
 */

class PAM_Plugin_Silent extends \WP_Upgrader_Skin {

	public function feedback($string, ...$args) { /* no output */ }
	public function header() { /* no output */ }
	public function footer() { /* no output */ }

}


class PAM_Plugin_Force {


	private $link = '';
	private $slug = '';

	/**
	 * A function to download and activate a wordpress plugin from the wordpress repo, based on a slug
	 * @param  string $slug the WordPress plugin slug (say: https://wordpress.org/plugins/wordpress-seo/ - slug is: "wordpress-seo")
	 * @return array        the response
	 */

	public function __construct( $slug, $link ) {
		if ( is_url( $link ) ) {
			$this->link = $link;
			$this->slug = $slug;
			add_filter( 'upgrader_package_options', [ $this, 'upgrader_package_options' ] );
			$this->load_requires();
			$this->install_plugin();
		}
	}

	private function load_requires() {
		// Some, or most, if not all, of these should no longer be necessary because of the class usage.
		require_once(ABSPATH . '/wp-load.php');
		require_once(ABSPATH . 'wp-includes/pluggable.php');
		require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/misc.php');
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
	}

	private function install_plugin() {
		$success = true;
		$message = 'OK';
		$api = plugins_api(
			'plugin_information',
			array(
				'slug' => $this->slug,
				'fields' => array(
					'short_description' => false,
					'sections' => false,
					'requires' => false,
					'rating' => false,
					'ratings' => false,
					'downloaded' => false,
					'last_updated' => false,
					'added' => false,
					'tags' => false,
					'compatibility' => false,
					'homepage' => false,
					'donate_link' => false,
				),
			)
		$api->download_link = $this->link;
		$upgrader = new Plugin_Upgrader( new PAM_Plugin_Silent( array( 'api' => $api ) ) );
		$install  = $upgrader->install( $api->download_link );
		if ( ! ( $install === true ) ) {
			$success = false;
			$message = 'Error: Install process failed (' . $this->slug . '). var_dump of result follows: ' . var_dump( $install );
		}
		$response = array(
			'success' => $success,
			'message' => $message
		);
		ob_clean(); // remove any previous printed content
		header('Content-Type: application/json');
		exit( json_encode( $response ) );
	}

	public function upgrader_package_options( $options ) {
		$options['abort_if_destination_exists'] = false;
		return $options;
	}


}
