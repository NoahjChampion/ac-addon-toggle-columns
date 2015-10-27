<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit of accessed directly

/**
 * Admin class
 *
 * @since 1.0
 */
class ACTI_Admin {

	/**
	 * Plugin class instance
	 *
	 * @var ACTI
	 * @since 1.0
	 */
	public $acti;

	/**
	 * Constructor
	 *
	 * @since 1.0
	 *
	 * @var ACTI $acti Plugin class instance
	 */
	public function __construct( ACTI $acti ) {
		$this->acti = $acti;

		// Hooks
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_filter( 'cac/column/properties', array( $this, 'column_default_properties' ) );
		add_filter( 'cac/column/default_options', array( $this, 'column_default_options' ) );
		add_action( 'cac/column/settings_after', array( $this, 'column_settings_field' ), 10 );
		add_action( 'cac/column/settings_meta', array( $this, 'column_active_indicator' ), 10 );
		add_filter( 'cpac/storage_model/stored_columns', array( $this, 'storage_model_stored_columns' ) );
	}

	/**
	 * Prevent inactive columns from being displayed on content overview pages
	 *
	 * @see filter:cpac/storage_model/stored_columns
	 * @since 1.0
	 */
	public function storage_model_stored_columns( $columns ) {
		if ( ( $this->acti->cpac->is_doing_ajax() || $this->acti->cpac->is_columns_screen() ) && is_array( $columns ) ) {
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
		wp_enqueue_style( 'acti/admin', ACTI_PLUGIN_DIR_URL . 'assets/css/admin.css' );
	}

	/**
	 * @see filter:cac/column/properties
	 * @since 1.0
	 */
	function column_default_properties( $properties ) {
		$properties['is_activity_toggleable'] = true;

		return $properties;
	}

	/**
	 * @see filter:cac/column/options
	 * @since 1.0
	 */
	function column_default_options( $options ) {
		$options['active'] = 'on';

		return $options;
	}

	/**
	 * @see filter:cac/column/settings_after
	 * @since 1.0
	 */
	public function column_settings_field( $column ) {
		if ( ! $column->properties->is_activity_toggleable ) {
			return false;
        }

		?>
		<tr class="column_activity">
			<?php $column->label_view( __( 'Is column active?', 'cpac' ), __( 'Disabling this will disable the column, but not remove it from this overview.', 'cpac' ), 'activity' ); ?>
			<td class="input" data-toggle-id="<?php $column->attr_id( 'active' ); ?>">
				<label for="<?php $column->attr_id( 'active' ); ?>-on">
					<input type="radio" value="on" name="<?php $column->attr_name( 'active' ); ?>" id="<?php $column->attr_id( 'active' ); ?>-on"<?php checked( $column->options->active, 'on' ); ?> />
					<?php _e( 'Yes'); ?>
				</label>
				<label for="<?php $column->attr_id( 'active' ); ?>-off">
					<input type="radio" value="off" name="<?php $column->attr_name( 'active' ); ?>" id="<?php $column->attr_id( 'active' ); ?>-off"<?php checked( $column->options->active, '' ); ?><?php checked( $column->options->active, 'off' ); ?> />
					<?php _e( 'No'); ?>
				</label>
			</td>
		</tr>
		<?php
	}

	/**
	 * Label in column admin screen column header
	 *
	 * @since 1.0
	 */
	function column_active_indicator( $column ) {

		if ( ! $column->properties->is_activity_toggleable )
			return false;

		?>
		<span class="activity <?php echo $column->options->active; ?>" data-indicator-id="<?php $column->attr_id( 'active' ); ?>"></span>
		<?php
	}

}
