<?php
defined( 'ABSPATH' ) or die();

/**
 * Admin class
 *
 * @since 1.0
 */
class ACTI_Admin {

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {

		add_action( 'ac/settings_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'ac/column/settings', array( $this, 'column_settings' ) );
		add_filter( 'ac/headings', array( $this, 'remove_table_headings' ), 10, 2 );
		add_action( 'ac/table/list_screen', array( $this, 'remove_table_columns' ) );
	}

	public function admin_scripts() {
		wp_enqueue_style( 'acti/admin', plugin_dir_url( ACTI_FILE ) . 'assets/css/admin.css' );
	}

	/**
	 * @param AC_Column $column
	 */
	public function column_settings( $column ) {
		require_once plugin_dir_path( ACTI_FILE ) . 'includes/class-column-settings.php';

		$column->add_setting( new ACTI_Column_Settings( $column ) );
	}

	/**
	 * @param AC_ListScreen $list_screen
	 */
	public function remove_table_columns( $list_screen ) {
		foreach ( $this->get_disabled_columns( $list_screen ) as $name ) {
			$list_screen->deregister_column( $name );
		}
	}

	/**
	 * @param array         $headings
	 * @param AC_ListScreen $list_screen
	 *
	 * @return array
	 */
	public function remove_table_headings( $headings, $list_screen ) {
		foreach ( $this->get_disabled_columns( $list_screen ) as $name ) {
			unset( $headings[ $name ] );
		}

		return $headings;
	}

	/**
	 * @param AC_ListScreen $list_screen
	 */
	private function get_disabled_columns( $list_screen ) {
		$column_names = array();

		foreach ( $list_screen->get_columns() as $column ) {

			/* @var ACTI_Column_Settings $setting */
			$setting = $column->get_setting( 'active' );

			if ( ! $setting->is_active() ) {
				$column_names[] = $column->get_name();
			}
		}

		return $column_names;
	}

}