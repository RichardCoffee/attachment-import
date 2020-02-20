<?php

	/* 
	 * Hide the 'Activate Plugin' and other links when not using QuietSkin as these links will 
	 * fail when not called from /wp-admin 
	 */

		class QuietSkin extends \WP_Upgrader_Skin {
			public function feedback($string, ...$args) { /* no output */ }

			public function header() { /* no output */ }
			public function footer() { /* no output */ }
		}

	/**
	 * A function to download and activate a wordpress plugin from the wordpress repo, based on a slug
	 * @param  string $slug the WordPress plugin slug (say: https://wordpress.org/plugins/wordpress-seo/ - slug is: "wordpress-seo")
	 * @return array        the response
	 */
	
		function download_and_activate_wordpress_plugin($slug) {
			/**
			 * Install a plugin
			 */
			
				require_once(ABSPATH . '/wp-load.php');
				require_once(ABSPATH . 'wp-includes/pluggable.php');
				require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
				require_once(ABSPATH . 'wp-admin/includes/file.php');
				require_once(ABSPATH . 'wp-admin/includes/misc.php');
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
				require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');

			/**
			 * Download, install and activate a plugin
			 * 
			 * If the plugin directory already exists, this will only try to activate the plugin
			 * 
			 * @param string $slug The slug of the plugin (should be the same as the plugin's directory name
			 */

				$success = true;
				$message = 'OK';

			    $pluginDir = WP_PLUGIN_DIR . '/' . $slug . '/';

		    /* 
		     * Don't try installing plugins that already exist (wastes time downloading files that 
		     * won't be used 
		     */
		    
			    if (!is_dir($pluginDir)) {
			        $api = plugins_api(
			            'plugin_information',
			            array(
			                'slug' => $slug,
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
			        );
			        
			        // Replace with new QuietSkin for no output
			        $skin = new QuietSkin(array('api' => $api));

			        $upgrader = new Plugin_Upgrader($skin);

			        $install = $upgrader->install($api->download_link);



			        if ($install !== true) {
			        	$success = false;

			            $message = 'Error: Install process failed (' . $slug . '). var_dump of result follows: ' . var_dump($install);
			        }
			    }

		    /*
		     * Try to activate by guessing the file
		     */
			    
			    $activation_success = false;

			    $files = scandir($pluginDir); 
				foreach($files as $file) {
					if(is_file($pluginDir.$file)) {

						$activation = activate_plugin($pluginDir.$file);

						if(!is_wp_error($activation)) {
							$activation_success = true;
							break;
						}

					}
				}

			/**
			 * Return response
			 */

				header('Content-Type: application/json');

				return [
					'success' => $success,
					'activation_success' => $activation_success,
		 			'message' => $message
				];
		}


	/**
	 * Usage
	 * @var array
	 */
		
		$response = download_and_activate_wordpress_plugin('all-in-one-seo-pack');

		ob_clean(); // remove any previous printed content

		header('Content-Type: application/json');

		exit(json_encode($response));