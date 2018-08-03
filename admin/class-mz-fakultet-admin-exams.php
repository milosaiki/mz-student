<?php
/**
 *  Exams class that displays table with students and exams
 */
class MY_Exams extends WP_List_Table {

	public function __construct() {

		parent::__construct( array(
			'singular' => __( "Ispit", "mz-fakultet" ),
			'plural'   => __( "Ispiti", "mz-fakultet" ),
			'ajax'     => false
		) );
		$this->prepare_items();
		$this->display();
	}

	function get_columns() {

		$columns = array(
			'student'      => __( "Student", "mz-fakultet" ),
			'naziv_ispita' => __( "Naziv Ispita", "mz-fakultet" ),
			'ocena'        => __( "Ocena", "mz-fakultet" ),
			'datum'        => __( "Datum", "mz-fakultet" ),
		);

		return $columns;
	}

	protected function get_sortable_columns() {

		$sortable_columns = array(
			'student'      => array( 'student', false ),
			'naziv_ispita' => array( 'naziv_ispita', false ),
			'ocena'        => array( 'ocena', false )
		);

		return $sortable_columns;
	}

	function column_naziv_ispita( $item ) {

		return get_post_meta( $item['id'], 'naziv_ispita', true );
	}

	function column_ocena( $item ) {

		return get_post_meta( $item['id'], 'ocena', true );
	}

	function column_datum( $item ) {

		return get_post_meta( $item['id'], 'datum_ispita', true ) ? date( 'd.m.Y.', strtotime( get_post_meta( $item['id'], 'datum_ispita', true ) ) ) : 'The date is missing.';
	}

	public function process_bulk_action() {

		if ( 'delete' === $this->current_action() ) {
			$this->delete_exam( absint( $_GET['exam'] ) );
		}

	}

	public function delete_exam( $id ) {

		global $wpdb;
		wp_delete_post( $id );
		$prepare = $wpdb->prepare( "DELETE FROM {$wpdb->postmeta} WHERE {$wpdb->postmeta}.post_id = %d", $id );
		$wpdb->query( $prepare );
	}

	public function no_items() {

		_e( 'There are no exams.', 'sp' );
	}

	function column_student( $item ) {

		$student = get_post_meta( $item['id'], 'ime_studenta', true );

		$title = '<strong>' . $student . '</strong>';

		$actions = array (
			'edit'   => sprintf( '<a href="?page=%s&action=%s&exam=%s">'.__("Izmeni", "mz-fakultet").'</a>', esc_attr(
				$_REQUEST['page'] ), 'edit', absint( $item['id'] ) ),
			'delete' => sprintf( '<a href="?page=%s&action=%s&exam=%s">'.__("Obrisi", "mz-fakultet").'</a>', esc_attr(
				$_REQUEST['page'] ), 'delete', absint( $item['id'] ) )
		);

		return $title . $this->row_actions( $actions );
	}

	function prepare_items() {

		$exams_arr = array();

		/** Process bulk action */
		$this->process_bulk_action();
		
		$exams_query = new WP_Query( array( 'post_type' => 'ispit' ) );
		$exams_array = $exams_query->posts;

		foreach ( $exams_array as $exam ) {

			$student      = get_post_meta( $exam->ID, 'ime_studenta', true );
			$ispit        = get_post_meta( $exam->ID, 'naziv_ispita', true );
			$datum_ispita = get_post_meta( $exam->ID, 'datum_ispita', true ) ? strtotime( get_post_meta( $exam->ID, 'datum_ispita', true ) ) : 0;
			$ocena        = get_post_meta( $exam->ID, 'ocena', true );
			$id           = $exam->ID;

			$exams_arr[] = array(
				'id'           => $id,
				'student'      => $student,
				'naziv_ispita' => $ispit,
				"ocena"        => $ocena,
				'datum'        => $datum_ispita === 0 ? date( 'd.m.Y', $datum_ispita ) : '',
			);
		}
		

		$columns               = $this->get_columns();
		$sortable              = $this->get_sortable_columns();
		$hidden                = array();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		usort( $exams_arr, array( &$this, 'usort_reorder' ) );
		$this->items = $exams_arr;

		?>
		<?php
	}

	protected function usort_reorder( $a, $b ) {

		// If no sort, default to title
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'student';
		// If no order, default to asc
		$order = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'asc';
		// Determine sort order
		$result = strnatcmp( $a[ $orderby ], $b[ $orderby ] );

		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : - $result;
	}

}