<?php
/**
 * classes/Plugin/Attach.php
 *
 * @package AttachmentImport
 * @subpackage Plugin_Core
 * @since 20170111
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2017, Richard Coffee
 * @link https://github.com/RichardCoffee/custom-post-type/blob/master/classes/Plugin/Base.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  Main plugin class
 *
 * @since 20180404
 */
class PAM_Plugin_Attach extends PAM_Plugin_Plugin {


	protected $attach = array();
	protected $parsed = array();


	use PAM_Trait_Logging;
#	 * @since 20200201
	use PAM_Trait_Singleton;


#	 * @since 20180404
	public function initialize() {
		if ( ( ! PAM_Register_Register::php_version_check() ) || ( ! PAM_Register_Register::wp_version_check() ) ) {
			return;
		}
		register_deactivation_hook( $this->paths->file, [ 'PAM_Register_Register', 'deactivate' ] );
		register_uninstall_hook(    $this->paths->file, [ 'PAM_Register_Register', 'uninstall'  ] );
		$this->add_actions();
		$this->add_filters();
	}

#	 * @since 20180404
	public function add_actions() {
		parent::add_actions();
	}

#	 * @since 20180404
	public function add_filters() {
		parent::add_filters();
	}

	public function settings_link( $links, $file, $data, $context ) {
		$links = parent::settings_link( $links, $file, $data, $context );
#		if ( strpos( $file, $this->plugin ) !== false ) {
#$url = ( $this->setting ) ? $this->setting : admin_url( 'admin.php?page=fluidity_options&tab=' . $this->tab );
#$links['force'] = sprintf( '<a href="%s"> %s </a>', esc_url( $url ), esc_html__( 'Force', 'attach-mods' ) );
#		}
		return $links;
	}

	protected function get_import_file() {
		$file = $this->paths->dir . 'xml/rtcenterprises.WordPress.2019-08-10.xml';
		$this->parsed = $this->parse( $file );
		$this->attach = array_filter(
			$this->parsed['posts'],
			function( $a ) {
				if ( $a['post_type'] === 'attachment' ) return true;
				return false;
			}
		);
	}

	protected function parse( $file ) {
		require_once( $this->paths->dir . $this->paths->vender . 'wordpress-importer/parsers.php' );
		$parser = new WXR_Parser();
		return $parser->parse( $file );
	}

	protected function fix_pictures_post() {
		global $wpdb;
		$sql    = "SELECT * FROM {$wpdb->prefix}posts WHERE post_title = 'Pictures' AND post_type = 'post'";
		$result = $wpdb->get_results( $sql, ARRAY_A );
		if ( ! empty( $result ) ) {
			$post = $result[0];
			$arr1 = explode( '"', $post['post_content'] );
			$arr2 = explode( ',', $arr1[3] );
			foreach( $arr2 as $key => $attID ) {
				$arr2[ $key ] = $this->get_new_attachment_id( $attID, $post['post_id'] );
			}
			$arr3 = array_filter( $arr2, function ( $a ) { return $a; } );
			$arr1[3] = implode( ',', $arr3 );
			$content = implode( '"', $arr1 );
			$wpdb->update( "{$wpdb->prefix}posts", [ 'post_content' => $content ], [ 'post_id' => $post['post_id'] ], [ '%s' ], [ '%d' ] );
		} else {
			$this->log( "No result for '$sql'";
		}
	}

	protected function get_new_attachment_id( $old, $parent = false ) {
		global $wpdb;
		foreach( $this->attach as $attachment ) {
			if ( $attachment['post_id'] === $old ) {
				$sql = "SELECT * FROM {$wpdb->prefix}posts WHERE post_title = '{$attachment['post_title']}' AND post_type = 'attachment'";
				$result = $wpdb->get_results( $sql, ARRAY_A );
				if ( ! empty( $result ) ) {
					$post = $result[0];
					if ( $post ) {
						if ( $parent ) {
							$wpdb->update( "{$wpdb->prefix}posts", [ 'post_parent' => $parent ], [ 'post_id' => $post['post_id'] ], [ '%d' ], [ '%d' ] );
						}
						return $post['post_id'];
					}
				} else {
					$this->log( "No result for '$sql'";
				}
			}
		}
		return 0;
	}
/*
	protected function fix_featured_image_posts() {
		global $wpdb;
		$fixes = array( 'red-flower', 'truck-air-schematic' );
		foreach( $fixes as $name ) {
			$sql = "SELECT * FROM {$wpdb->prefix}posts WHERE post_name = '$name' AND post_type = 'post'";
			$result = $wpdb->get_results( $sql, ARRAY_A );
			if ( ! empty( $result ) ) {
				$post = $result[0];

			} else {
				$this->log( "No result for '$sql'";
			}
		}
	} //*/


}
