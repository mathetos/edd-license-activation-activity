<?php
/*
Plugin Name: EDD License Activation Activity
Plugin URI: https://www.mattcromwell.com
Description: This is an extension of the EDD Software Licensing Addon. It gives you an easy way to review the latest activations of your download licenses. This is useful to better understanding your customers and seeing live examples of your downloads on live sites.
Version: 1.0.0
Author: webdevmattcrom
Author URI: https://www.mattcromwell.com
License: GPLv2 or later
Text Domain: eddlaa
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Copyright 2016 Matt Cromwell
*/
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
// Globals
define( 'EDDLAA_SLUG', 'b16ecom' );
define( 'EDDLAA_PATH', plugin_dir_path( __FILE__ ) );
define( 'EDDLAA_URL', plugin_dir_url( __FILE__ ) );
define( 'EDDLAA_VERSION', '1.0' );

class EDDLAA_Loader {
  public function __construct() {
    $this->plugin_slug = EDDLAA_SLUG;
    $this->version = EDDLAA_VERSION;

    add_action( 'plugins_loaded', array( $this, 'eddlaa_admin' ) );
    add_action( 'admin_enqueue_scripts', array($this, 'eddlaa_load_admin_scripts') );

  }

  public function eddlaa_admin() {
      require_once( EDDLAA_PATH . '/admin/eddlaa_admin.php');
  }

  public function eddlaa_load_admin_scripts($hook) {

		global $eddlaa_adminpage;

		if( $hook != $eddlaa_adminpage ) {
			return; }

		wp_register_style( 'eddlaa-datatables-css', 'https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css', false, '1.0.0' );
    wp_enqueue_style( 'eddlaa-datatables-css' );

		wp_register_script( 'eddlaa-databales-js', 'https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js', false, '1.0.0' );
		wp_enqueue_script( 'eddlaa-databales-js' );
	}

  public function run() {
 }
}

function run_eddlaa_loader() {
   $eddlaa = new EDDLAA_Loader();
   $eddlaa->run();
}

run_eddlaa_loader();
