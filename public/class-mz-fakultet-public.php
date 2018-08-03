<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.linkedin.com/in/milos-zivic-2586a174
 * @since      1.0.0
 *
 * @package    Mz_Fakultet
 * @subpackage Mz_Fakultet/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mz_Fakultet
 * @subpackage Mz_Fakultet/public
 * @author     Milos Zivic <milosh.zivic@gmail.com>
 */
class Mz_Fakultet_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mz_Fakultet_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mz_Fakultet_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mz-fakultet-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mz_Fakultet_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mz_Fakultet_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mz-fakultet-public.js', array( 'jquery' ), $this->version, false );

	}

	//shortcode for displaying Student and Exams, use [fakultet_ispiti student_id="id_nekog_studenta"]
	public function fakultet_ispiti_shortcode( $atts ) {

		$a = shortcode_atts( array(
			'student_id' => 0
		), $atts );

		global $wpdb;
		$prepare = $wpdb->prepare( "SELECT * FROM {$wpdb->usermeta} WHERE {$wpdb->usermeta}.meta_value = %d", $a['student_id'] );
		$student_id = $wpdb->get_results( $prepare );
		$student = get_userdata( $student_id[0]->user_id );

		$args = array(
			'post_type' => 'ispit',
			'meta_key' => 'ime_studenta',
			'meta_value' => $student->display_name
		);

		$loop = new WP_Query($args);

		if( $loop->have_posts() ) :

			?>
			<h1>Ispiti studenta: <?php echo $student->display_name; ?></h1>
			<table>
				<tr>
					<th>Naziv ispita</th>
					<th>Datum ispita</th>
					<th>Ocena</th>
				</tr>
			<?php

			while( $loop->have_posts() ) :
				?>
				<tr>
				<?php
				$loop->the_post();
				$naziv_ispita = get_post_meta( get_the_ID(), 'naziv_ispita', true );
				$datum_ispita = strtotime(get_post_meta( get_the_ID(), 'datum_ispita', true ));
				$ocena = get_post_meta( get_the_ID(), 'ocena', true );
				?>
					<th><?php echo $naziv_ispita; ?></th>
					<th><?php echo date('d.m.Y', $datum_ispita); ?></th>
					<th><?php echo $ocena; ?></th>
				</tr>
				<?php

			endwhile;
			?>
			</table>
			<?php


		endif;

	}

	//shortcode registration
	public function register_shortcode() {
		add_shortcode( 'fakultet_ispiti', array( $this, 'fakultet_ispiti_shortcode' ) );
	}

}
