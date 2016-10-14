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

		add_action( 'ac/admin_scripts/tab=columns', array( $this, 'admin_scripts' ) );
		add_action( 'ac/column/after_init', array( $this, 'set_column_defaults' ) );
		add_action( 'cac/column/settings_after', array( $this, 'column_settings_field' ) );
		add_action( 'cac/column/settings_meta', array( $this, 'column_active_indicator' ) );
		add_action( 'ac/column', array( $this, 'remove_inactive_columns_on_list_screen' ) );
	}

	/**
	 * @param CPAC_Column $column
	 */
	public function remove_inactive_columns_on_list_screen( $column ) {
		if ( 'off' === $column->get_option( 'active' ) && $column->get_list_screen()->is_current_screen() ) {
			$column->get_list_screen()->columns()->deregister_column( $column->get_name() );
		}
	}

	/**
	 * Prevent inactive columns from being displayed on content overview pages
	 *
	 * @param array $columns
	 *
	 * @see filter:cpac/storage_model/stored_columns
	 * @since 1.0
	 */
	public function remove_inactive_columns_from_overview( $columns ) {
		if ( is_array( $columns ) ) {
			foreach ( $columns as $index => $column ) {
				if ( isset( $column['active'] ) && $column['active'] == 'off' ) {
					unset( $columns[ $index ] );
				}
			}
		}

		return $columns;
	}

	/**
	 * Register and enqueue scripts
	 *
	 * @since 1.0
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'acti/admin', plugin_dir_url( ACTI_FILE ) . 'assets/css/admin.css' );
	}

	/**
	 * @param CPAC_Column $column
	 *
	 * @see filter:cac/column/properties
	 * @since 1.0
	 */
	function set_column_defaults( $column ) {
		$column->properties['is_activity_toggleable'] = true;
	}

	/**
	 * @see filter:cac/column/settings_after
	 *
	 * @param CPAC_Column $column
	 *
	 * @since 1.0
	 */
	public function column_settings_field( $column ) {
		if ( $column->get_property( 'is_activity_toggleable' ) ) {
			$column->field_settings->field( array(
				'type'           => 'radio',
				'name'           => 'active',
				'label'          => __( 'Active', 'codepress-admin-columns' ),
				'description'    => __( 'Disabling this will disable the column, but not remove it from this overview.', 'cpac' ),
				'options'        => array(
					'on'  => __( 'Yes' ),
					'off' => __( 'No' ),
				),
				'section'        => true,
				'toggle_trigger' => 'active',
				'default_value'  => 'on',
			) );
		}
	}

	/**
	 * Label in column admin screen column header
	 *
	 * @param CPAC_Column $column
	 *
	 * @since 1.0
	 */
	function column_active_indicator( $column ) {
		// TODO: @see ACP_Filtering_Addon
		if ( $column->get_property( 'is_activity_toggleable' ) ) : ?>
			<span class="activity <?php echo $column->get_option( 'active' ); ?>" data-indicator-id="<?php $column->field_settings->attr_id( 'active' ); ?>"></span>
			<?php
		endif;
	}

}