<?php

/**
 * Fired during plugin activation
 *
 * @link       www.linkedin.com/in/milos-zivic-2586a174
 * @since      1.0.0
 *
 * @package    Mz_Fakultet
 * @subpackage Mz_Fakultet/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mz_Fakultet
 * @subpackage Mz_Fakultet/includes
 * @author     Milos Zivic <milosh.zivic@gmail.com>
 */
class Mz_Fakultet_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	
	//Adds new custom user role
	add_role( 'student', 'Student', array( 'read' => true, 'level_0' => true ) );

	
	}
		

	

}
