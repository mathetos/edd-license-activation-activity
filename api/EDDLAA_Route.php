<?php

class EDDLAA_Route {

	public function __construct(){
		$this->add_route();
	}

	public function add_route(){
		$namespace = 'eddlaa/v1';
		$base = 'logs';

		register_rest_route( $namespace, '/' . $base, array(
			array(
				'methods'         => WP_REST_Server::READABLE,
				'callback'        => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'permissions_check' ),
				'args'            => array()
			),
		) );
		register_rest_route( $namespace, '/' . $base . '/(?P<id>[\d]+)', array(
			array(
				'methods'         => WP_REST_Server::READABLE,
				'callback'        => array( $this, 'get_item' ),
				'permission_callback' => array( $this, 'permissions_check' ),
				'args'            => array(
					'page'          => array(
						'default' => 1,
						'sanitize_callback' => 'absint'
					),
				),
			),
		));

	}

	public function get_items( WP_REST_Request $request ){
		$args = array(
			'post_type'      => 'edd_license_log',
			'posts_per_page' => 20,
			'paged' => $request->get_param( 'page' )
		);

		return $this->response_by_args( $args );
	}

	public function get_item( WP_REST_Request $request ){
		$url_params = $request->get_url_params();
		$log_id = $url_params[ 'ID' ];
		$args = array(
			'post_type'      => 'edd_license_log',
			'meta_value'     => $log_id,
			'posts_per_page' => 20,
			'paged' => $request->get_param( 'page' )
		);

		return $this->response_by_args( $args );
	}

	protected function response_by_args( array $args ){
		$logs = get_posts( $args  );

		return rest_ensure_response( $logs );
	}


	public function permissions_check(){
		return current_user_can( 'manage_options' );
	}

}
