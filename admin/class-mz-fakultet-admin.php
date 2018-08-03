<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.linkedin.com/in/milos-zivic-2586a174
 * @since      1.0.0
 *
 * @package    Mz_Fakultet
 * @subpackage Mz_Fakultet/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mz_Fakultet
 * @subpackage Mz_Fakultet/admin
 * @author     Milos Zivic <milosh.zivic@gmail.com>
 */
class Mz_Fakultet_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		//adds screen options for Student page
		add_filter( 'set-screen-option', array( $this, 'set_screen_students' ), 10, 3 );

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	public static function set_screen_students( $status, $option, $value ) {

		return $value;
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mz-fakultet-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mz-fakultet-admin.js', array( 'jquery' ), $this->version, false );


		wp_localize_script(
			$this->plugin_name,
			'mz_fakultet',
			array(
				'base_url' => site_url()
			)
		);

		wp_enqueue_script( $this->plugin_name );


	}


	/**
	 * Register a Ispiti post type.
	 */
	function codex_ispiti_init() {

		$labels = array(
			'name'               => _x( 'Ispiti', 'post type general name', 'mz-fakultet' ),
			'singular_name'      => _x( 'Ispit', 'post type singular name', 'mz-fakultet' ),
			'menu_name'          => _x( 'Ispiti', 'admin menu', 'mz-fakultet' ),
			'name_admin_bar'     => _x( 'Ispit', 'add new on admin bar', 'mz-fakultet' ),
			'add_new'            => _x( 'Dodaj Ispit', 'book', 'mz-fakultet' ),
			'add_new_item'       => __( 'Dodaj Novi Ispit', 'mz-fakultet' ),
			'new_item'           => __( 'Novi Ispit', 'mz-fakultet' ),
			'edit_item'          => __( 'Izmeni Ispit', 'mz-fakultet' ),
			'view_item'          => __( 'Vidi Ispit', 'mz-fakultet' ),
			'all_items'          => __( 'Svi Ispiti', 'mz-fakultet' ),
			'search_items'       => __( 'Pretrazi Ispite', 'mz-fakultet' ),
			'parent_item_colon'  => __( 'Parent Books:', 'mz-fakultet' ),
			'not_found'          => __( 'Nema ispita.', 'mz-fakultet' ),
			'not_found_in_trash' => __( 'Nema ispita u kanti.', 'mz-fakultet' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'mz-fakultet' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => false,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'ispit' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 90,
			'supports'           => array( 'title' )
		);

		register_post_type( 'ispit', $args );

	}


	public function register_settings_page() {

		// // Adds menu page
		add_menu_page(
			__( "Fakultet", "mz-fakultet" ),            //page_title
			__( "Fakultet", "mz-fakultet" ),            //menu_title
			"manage_options",                        //capability
			"mz-fakultet",                            //menu_slug
			array( $this, "display_exams_submenu_page" ),    //callback function
			"dashicons-welcome-learn-more"

		);
	}

	public function register_submenu_pages() {

		// //Adds submenu page
		add_submenu_page(
			'mz-fakultet',
			__( "Ispiti", "mz-fakultet" ),
			__( "Ispiti", "mz-fakultet" ),
			"manage_options",
			"mz-fakultet",
			array( $this, 'display_exams_submenu_page' )
		);
		// 	// //Adds submenu page
		add_submenu_page(
			'mz-fakultet',
			__( "Studenti", "mz-fakultet" ),
			__( "Studenti", "mz-fakultet" ),
			"manage_options",
			"mz-fakultet-studenti",
			array( $this, 'display_students_submenu_page' )
		);

	}


	/**
	 * Ispiti submenu
	 */

	public function display_exams_submenu_page() {

		//checks if user is editing or creating new student
		if ( $_REQUEST['edit_exam'] ) {
			$naziv_ispita = $_POST['naziv_ispita'];
			$datum_ispita = $_POST['datum_ispita'];
			$ocena        = $_POST['ocena'];
			$student_id   = ucwords( str_replace( '-', ' ', $_POST['ime_studenta'] ) );
			update_post_meta( $_REQUEST['exam'], 'naziv_ispita', $naziv_ispita );
			update_post_meta( $_REQUEST['exam'], 'datum_ispita', $datum_ispita );
			update_post_meta( $_REQUEST['exam'], 'ocena', $ocena );
			update_post_meta( $_REQUEST['exam'], 'ime_studenta', $student_id );
			echo '<script>window.location = "#success=true";</script>';
		} elseif ( $_REQUEST['submit_exam'] ) {
			$naziv_ispita = $_POST['naziv_ispita'];
			$datum_ispita = $_POST['datum_ispita'];
			$ocena        = $_POST['ocena'];
			$student_id   = ucwords( str_replace( '-', ' ', $_POST['ime_studenta'] ) );

			$postdata = array(
				'post_title'  => $naziv_ispita,
				'post_date'   => $datum_ispita,
				"post_type"   => "ispit",
				'post_name'   => strtolower( $naziv_ispita ),
				'post_status' => 'publish'
			);

			$post_id  = wp_insert_post( $postdata );

			if ( $post_id ) {
				update_post_meta( $post_id, 'ocena', $ocena );
				update_post_meta( $post_id, 'ime_studenta', $student_id );
				update_post_meta( $post_id, 'naziv_ispita', $naziv_ispita );
				update_post_meta( $post_id, 'datum_ispita', $datum_ispita );
			}
			echo '<script>window.location = "#success=true";</script>';
		}

		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'edit' && isset( $_REQUEST['exam'] ) ) {
			$naziv_ispita = get_post_meta( $_REQUEST['exam'], 'naziv_ispita', true );
			$datum_ispita = get_post_meta( $_REQUEST['exam'], 'datum_ispita', true );
			$ocena        = get_post_meta( $_REQUEST['exam'], 'ocena', true );
			$student_id   = get_post_meta( $_REQUEST['exam'], 'ime_studenta', true );
		}

		// Add/Edit exam form
		?>
		<div class="wrap">
            <h1>Ispiti</h1>

            <h2><?php echo $_REQUEST['action'] === 'edit' ? __('Izmeni Ispit', 'mz-fakultet') : __('Dodaj Ispit', 'mz-fakultet')?></h2>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                <table class="form-table">
                    <tr>
                        <td>
                            <label for="naziv_ispita"><?php echo __( "Naziv Ispita", "mz-fakultet" ); ?></label>
                            <input type="text" id="naziv_ispita" name="naziv_ispita"
                                   value="<?php echo $naziv_ispita; ?>" required>
                        </td>
                        <td>
                            <label for="datum_ispita"><?php echo __( "Datum Ispita", "mz-fakultet" ); ?></label>
                            <input type="date" id="datum_ispita" name="datum_ispita"
                                   value="<?php echo $datum_ispita; ?>" required>
                        </td>
                        <td>
                            <label for="ocena"><?php echo __( "Ocena", "mz-fakultet" ); ?></label>
                            <input type="number" id="ocena" name="ocena" value="<?php echo $ocena; ?>" min="5" max="10" required>
                        </td>
                        <td>
                            <label><?php echo __( "Ime Studenta", "mz-fakultet" ); ?>
                                <select name="ime_studenta" id="ime_studenta" required>
                                    <option selected="selected" value="izaberi_studenta">Izaberi Studenta</option>
									<?php
									//displaying all Students
									$students_query = new WP_User_Query( array( 'role' => 'Student' ) );

									$students_array = $students_query->get_results();

									foreach ( $students_array as $student ) {

										$student_name = $student->data->display_name;
										?>
                                        <option value="<?php echo $student->data->user_login; ?>"<?php echo
										$student->data->display_name == $student_id ? 'selected' : ''; ?>><?php echo
											$student_name; ?></option>
										<?php
									}
									?>

                                </select>
                            </label>
                        </td>
                        <td>
                            <p class="submit"><input type="submit"
                                                     value="<?php echo $_REQUEST['action'] === 'edit' ? __('Izmeni Ispit', 'mz-fakultet') : __('Dodaj Ispit', 'mz-fakultet')?>"
                                                     class="button-primary"
                                                     name="<?php echo $_REQUEST['action'] === 'edit' ? 'edit_exam' : 'submit_exam'
							                         ?>"></p>
                        </td>
                    </tr>
                </table>

            </form>
			<!-- Dropdown menu which will display data for selected students -->
            <h2><?php esc_attr_e( 'Izaberi Studenta', 'mz-fakultet' ); ?></h2>
			<small>Izaberite studenta čije podatke želite da vidite u tabeli</small><br><br>
            <select name="" id="student_select">
                <option selected="selected" value="izaberi_studenta" id="izaberi_studenta">Izaberi Studenta</option>
				<?php
				//displaying all Students
				$students_query = new WP_User_Query( array( 'role' => 'Student' ) );

				$students_array = $students_query->get_results();

				foreach ( $students_array as $student ) {
					$student_id   = $student->data->ID;
					$student_name = $student->data->display_name;
					echo "<option value='" . $student_id . "' id='" . $student_id . "'>" . $student_name . "</option>";

				}
				?>

            </select>
        </div>

        <div class="wrap">
            <h2><?php esc_attr_e( "Ispiti", "mz-fakultet" ); ?></h2>
			<?php
			new MY_Exams();
			?>
        </div>


		<?php
	}


	public function display_students_submenu_page() {

		//Checks if user is creating or editing Student
		if ( $_REQUEST['edit_student'] ) {
			global $wpdb;
			$firstname = $_POST['firstname'];
			$lastname  = $_POST['lastname'];
			$index_id  = $_POST['student_index_id'];
			$prepare = ("SELECT {$wpdb->usermeta}.meta_value FROM {$wpdb->usermeta} WHERE {$wpdb->usermeta}.meta_value='".$index_id."'");
			$student_index_id = $wpdb->get_results($prepare);

			if( ! $student_index_id[0]->meta_value){
				$userdata = array(
					'ID'           => $_REQUEST['student'],
					'display_name' => $firstname . ' ' . $lastname
				);
				wp_update_user( $userdata );
				update_user_meta( $_REQUEST['student'], 'broj_indeksa', $index_id );
				update_user_meta( $_REQUEST['student'], 'first_name', $firstname );
				update_user_meta( $_REQUEST['student'], 'last_name', $lastname );
				echo '<script>window.location = "#success=true";</script>';
			} else {
				echo  '<script>alert("Broj indeksa vec postoji, molim Vas dodajte drugi!");</script>';
			}
		} elseif ( $_REQUEST['create_student'] ) {
			global $wpdb;
			$firstname = $_POST['firstname'];
			$lastname  = $_POST['lastname'];
			$index_id  = $_POST['student_index_id'];
			$prepare = ("SELECT {$wpdb->usermeta}.meta_value FROM {$wpdb->usermeta} WHERE {$wpdb->usermeta}.meta_value='".$index_id."'");
			$student_index_id = $wpdb->get_results($prepare);
			
			if( ! $student_index_id[0]->meta_value){
				$userdata = array(
					'role'         => "Student",
					'user_login'   => strtolower( str_replace( ' ', '-', $firstname ) . '-' . str_replace( ' ', '-',
							$lastname ) ),
					'display_name' => $firstname . ' ' . $lastname,
					'first_name'   => $firstname,
					'last_name'    => $lastname,
					'user_pass'    => $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false )
				);
				$user_id  = wp_insert_user( $userdata );
				if ( $user_id ) {
					update_user_meta( $user_id, 'broj_indeksa', $index_id );
					update_user_meta( $user_id, 'wp_capabilities', array( 'Student' ) );
				}
				echo '<script>window.location = "#success=true";</script>';
			} else {
				echo  '<script>alert("Broj indeksa vec postoji, molim Vas dodajte drugi!");</script>';
			}
			
		}
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'edit' && isset( $_REQUEST['student'] ) ) {
			$student          = get_userdata( $_REQUEST['student'] );
             $display_name = explode(' ', $student->display_name);
			$ime_studenta = $display_name[0];;
			$prezime_studenta = $display_name[1];
			$broj_indeksa     = get_user_meta( $_REQUEST['student'], 'broj_indeksa', true );
		}
		?>
		<!-- Form for Create/Edit Student -->
        <div class="wrap">
            <h2><?php echo $_REQUEST['action'] === 'edit' ? __('Izmeni Studenta', 'mz-fakultet') : __('Dodaj Novog Studenta', 'mz-fakultet')?></h2>

            <form id="student-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                <table class="form-table">
                    <tbody>
                    <tr>
                        <td>
                            <label for="firstname"><?php echo __( 'Ime Studenta', 'mz-fakultet' ); ?></label>
                        </td>
                        <td>
                            <input type="text" class="regular-text" id="firstname" name="firstname" value="<?php echo $ime_studenta ? $ime_studenta : '';?>" required>
                        </td>
                        <td>
                            <label for="lastname"><?php echo __( 'Prezime Studenta', 'mz-fakultet' ); ?></label>
                        </td>
                        <td>
                            <input type="text" class="regular-text" id="lastname" name="lastname" value="<?php echo $prezime_studenta? $prezime_studenta : '';?>" required>
                        </td>
                        <td>
                            <label><?php echo __( 'Broj Indeksa', 'mz-fakultet' ); ?></label>
                        </td>
                        <td>
                            <input type="number" id="student_index_id" name="student_index_id" value="<?php echo $broj_indeksa ? $broj_indeksa : '';?>" placeholder="" id="broj_indeksa" required>
                        </td>
                        <td>
                            <p class="submit"><input type="submit"
                                                     value="<?php echo $_REQUEST['action'] === 'edit' ? __('Izmeni Studenta', 'mz-fakultet') : __('Dodaj Studenta', 'mz-fakultet')?>"
                                                     class="button-primary"
                                                     name="<?php echo $_REQUEST['action'] === 'edit' ? 'edit_student' : 'create_student'
							                         ?>"></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
        <div class="wrap">
			<?php
			new MZ_Students();
			?>
        </div>
		<?php
	}

	//Screen options for students page
	public function screen_option_students() {

		$option = 'per_page';
		$args   = array(
			'label'   => 'Students',
			'default' => 5,
			'option'  => 'students_per_page'
		);

		add_screen_option( $option, $args );

	}



}

