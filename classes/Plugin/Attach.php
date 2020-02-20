<?php
/**
 * classes/Plugin/Attach.php
 *
 * @package AttachmentMods
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
		if ( strpos( $file, $this->plugin ) !== false ) {
$url = ( $this->setting ) ? $this->setting : admin_url( 'admin.php?page=fluidity_options&tab=' . $this->tab );
$links['force'] = sprintf( '<a href="%s"> %s </a>', esc_url( $url ), esc_html__( 'Force', 'attach-mods' ) );
		}
		return $links;
	}


}
