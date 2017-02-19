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
		add_filter( 'ac/headings', array( $this, 'disable_columns' ), 10, 2 );
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
	 * @param array $headings
	 * @param AC_ListScreen $list_screen
	 *
	 * @return array
	 */
	public function disable_columns( $headings, $list_screen  ) {
		foreach ( $list_screen->get_columns() as $column ) {
			if ( 'off' === $column->get_setting( 'active' )->get_value() ) {
				unset( $headings[ $column->get_name() ] );
			}
		}

		return $headings;
	}

}