<?php

class ACTI_Column_Settings extends AC_Settings_Setting
	implements AC_Settings_HeaderInterface {

	private $active;

	protected function define_options() {
		return array( 'active' => 'on' );
	}

	public function create_header_view() {
		$view = new AC_View( array(
			'title'    => __( 'Active', 'codepress-admin-columns' ),
			'dashicon' => 'dashicons-yes',
			'state'    => $this->get_active(),
		) );

		$view->set_template( 'settings/header-icon' );

		return $view;
	}

	public function create_view() {
		$active = $this->create_element( 'radio', 'active' )
		             ->set_options( array(
			             'on'  => __( 'Yes' ),
			             'off' => __( 'No' ),
		             ) );

		$view = new AC_View();
		$view->set( 'label', __( 'Active', 'codepress-admin-columns' ) )
		     ->set( 'tooltip', __( 'Disabling this will disable the column, but not remove it from this overview.', 'codepress-admin-columns' ) )
		     ->set( 'setting', $active );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_active() {
		return $this->active;
	}

	/**
	 * @param string $active
	 *
	 * @return $this
	 */
	public function set_active( $active ) {
		$this->active = $active;

		return $this;
	}

}