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
	 *
	 * @var ACTI $acti Plugin class instance
	 */
	public function __construct() {

		add_action( 'ac/enqueue_settings_scripts', array( $this, 'scripts' ) );
		add_action( 'ac/column/defaults', array( $this, 'set_column_defaults' ) );
		add_action( 'cac/column/settings_after', array( $this, 'column_settings_field' ), 10 );
		add_action( 'cac/column/settings_meta', array( $this, 'column_active_indicator' ), 10 );
		add_filter( 'cpac/storage_model/stored_columns', array( $this, 'storage_model_stored_columns' ), 10, 2 );
	}

	/**
	 * Prevent inactive columns from being displayed on content overview pages
	 *
	 * @see filter:cpac/storage_model/stored_columns
	 * @since 1.0
	 */
	public function storage_model_stored_columns( $columns, $storage_model ) {
		if ( ( cac_is_doing_ajax() || $storage_model->is_current_screen() ) && is_array( $columns ) ) {
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
	public function scripts() {
		wp_enqueue_style( 'acti/admin', plugin_dir_url( ACTI_FILE ) . 'assets/css/admin.css' );
	}

	/**
	 * @see filter:cac/column/properties
	 * @since 1.0
	 */
	function set_column_defaults( $column ) {
		$column->properties['is_activity_toggleable'] = true;
		$column->options['active'] = 'on';
	}

	/**
	 * @see filter:cac/column/settings_after
	 * @param CPAC_Column $column
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
			) );
		}
	}

	/**
	 * Label in column admin screen column header
	 * @param CPAC_Column $column
	 * @since 1.0
	 */
	function column_active_indicator( $column ) {
		if ( $column->get_property( 'is_activity_toggleable' ) ) : ?>
			<span class="activity <?php echo $column->get_option( 'active' ); ?>" data-indicator-id="<?php $column->field_settings->attr_id( 'active' ); ?>"></span>
			<?php
		endif;
	}
}