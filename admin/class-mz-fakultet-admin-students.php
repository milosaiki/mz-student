<?php

/**
 * Class Mz_Students displays table for created students
 */
class Mz_Students extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( array(
			'singular' => __( 'Student', 'mz-fakultet' ), //singular name of the listed records
			'plural'   => __( 'Students', 'mz-fakultet' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		) );

		$this->prepare_items();
		$this->display();

	}

	public static function get_students( $per_page = 5, $page_number = 1 ) {

		$args = array(
		   'role' => 'Student',
		   'orderby' => 'meta_value_num',
		   'meta_key' => $_REQUEST['orderby'] ? $_REQUEST['orderby'] : 'broj_indeksa',
		   'order' => $_REQUEST['order'] ? $_REQUEST['order'] : 'ASC',
		  
		  );

		$user = new WP_User_Query( $args );

		$students = $user->get_results();

		foreach( $students as $student ) {
			$result[$student->data->ID][] = get_userdata( $student->data->ID );
			$result[$student->data->ID][] = get_user_meta( $student->data->ID );
		}

		return $result;
	}

	public static function delete_student( $id ) {

		global $wpdb;
		wp_delete_user( $id );
		$wpdb->delete( "{$wpdb->prefix}usermeta", array( "user_id" => $id ) );

	}

	public static function record_count() {

		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}users AS students INNER JOIN {$wpdb->prefix}usermeta AS studentmeta ON 
students.id = studentmeta.user_id WHERE studentmeta.meta_key = 'wp_capabilities' AND studentmeta.meta_value  LIKE '%student%'";

		return $wpdb->get_var( $sql );
	}

	public function no_items() {

		_e( 'Nema studenata.', 'mz-fakultet' );
	}

	function column_broj_indeksa( $item ) {

		$broj_indeksa = $item[1]['broj_indeksa'][0];

		$title = '<strong>' . $broj_indeksa . '</strong>';

		$actions = array (
			'edit' => sprintf( '<a href="?page=%s&action=%s&student=%s">'.__("Izmeni", "mz-fakultet").'</a>', esc_attr(
				$_REQUEST['page'] ), 'edit', absint( $item[0]->data->ID ) ),
			'delete' => sprintf( '<a href="?page=%s&action=%s&student=%s">'.__("Obrisi", "mz-fakultet").'</a>', esc_attr(
				$_REQUEST['page'] ), 'delete', absint( $item[0]->data->ID ) )
		);

		return $title . $this->row_actions( $actions );
	}

	function column_display_name( $item ) {
		return $item[1]['first_name'][0] . ' ' . $item[1]['last_name'][0];
	}

	function column_cb( $item ) {

		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}

	function get_columns() {

		$columns = array (
			
			'broj_indeksa'    => __( 'Broj indeksa', 'mz-fakultet' ),
			'display_name'    => __( 'Ime i prezime', 'mz-fakultet' ),
		);

		return $columns;
	}

	public function get_sortable_columns() {

		$sortable_columns = array(
			'broj_indeksa' => array( 'broj_indeksa', true ),
			'display_name' => array( 'display_name', true ),
		);

		return $sortable_columns;
	}

	public function prepare_items() {
		$per_page     = $this->get_items_per_page( 'students_per_page', 5 );

		$columns               = $this->get_columns();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, array(), $sortable );

		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( array(
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		) );

		/** Process bulk action */
		$this->process_bulk_action();

		$data = self::get_students( $per_page, $current_page );

		usort( $data, array( &$this, 'usort_reorder' ) );
		$this->items = $data;
	}

	public function process_bulk_action() {

		if ( 'delete' === $this->current_action() ) {
			$this->delete_student( absint( $_GET['student'] ) );
		}

	}

	public function usort_reorder( $a, $b ) {

		// If no sort, default to title
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'broj_indeksa';
		// If no order, default to asc
		$order = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'asc';
		// Determine sort order
		$result = strnatcmp( $a[ $orderby ], $b[ $orderby ] );

		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : - $result;
	}

}