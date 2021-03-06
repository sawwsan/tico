<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Tables\DataTables\Build\Traffic;

use FernleafSystems\Wordpress\Plugin\Shield\Tables\DataTables\Build\Base;

class ForTraffic extends Base {

	protected function getOrderColumnSlug() :string {
		return 'date';
	}

	protected function getColumnsToDisplay() :array {
		return [
			'ip',
			'page',
			'details',
			'response',
			'date',
			'path',
			'code',
			'offense',
			'country',
		];
	}

	protected function getColumnDefs() :array {
		return [
			'rid'      => [
				'data'        => 'rid',
				'title'       => __( 'Request ID' ),
				'className'   => 'rid',
				'orderable'   => true,
				'searchable'  => true,
				'visible'     => false,
				'searchPanes' => [
					'show' => true,
				],
			],
			'page'     => [
				'data'        => 'page',
				'title'       => __( 'Page' ),
				'className'   => 'page',
				'orderable'   => true,
				'searchable'  => true,
				'visible'     => true,
				'searchPanes' => [
					'show' => false
				],
			],
			'details'  => [
				'data'        => 'details',
				'title'       => __( 'Details' ),
				'className'   => 'details',
				'orderable'   => false,
				'searchable'  => true,
				'visible'     => true,
				'searchPanes' => [
					'show' => false
				],
			],
			'response' => [
				'data'        => 'response',
				'title'       => __( 'Response' ),
				'className'   => 'response',
				'orderable'   => false,
				'searchable'  => true,
				'visible'     => true,
				'searchPanes' => [
					'show' => false
				],
			],
			'ip'       => [
				'data'        => 'ip',
				'title'       => __( 'IP Address' ),
				'className'   => 'ip',
				'orderable'   => true,
				'searchable'  => true,
				'visible'     => false,
				'searchPanes' => [
					'show' => true,
				],
			],
			'code'     => [
				'data'        => 'code',
				'title'       => __( 'Response Code' ),
				'className'   => 'code',
				'orderable'   => true,
				'searchable'  => true,
				'visible'     => false,
				'searchPanes' => [
					'show' => true,
				],
			],
			'country'  => [
				'data'        => 'country',
				'title'       => __( 'Country' ),
				'className'   => 'country',
				'orderable'   => true,
				'searchable'  => true,
				'visible'     => false,
				'searchPanes' => [
					'show' => true,
				],
			],
			'offense'  => [
				'data'        => 'offense',
				'title'       => __( 'Is Offense' ),
				'className'   => 'offense',
				'orderable'   => false,
				'searchable'  => true,
				'visible'     => false,
				'searchPanes' => [
					'show' => true,
				],
			],
			'path'  => [
				'data'        => 'path',
				'title'       => __( 'Path' ),
				'className'   => 'path',
				'orderable'   => false,
				'searchable'  => true,
				'visible'     => false,
				'searchPanes' => [
					'show' => true,
				],
			],
			'uid'      => [
				'data'       => 'uid',
				'title'      => __( 'User ID' ),
				'className'  => 'uid',
				'orderable'  => true,
				'searchable' => false,
				'visible'    => false,
			],
			'date'     => [
				'data'        => [
					'_'    => 'created_since',
					'sort' => 'created_at',
				],
				'title'       => __( 'Date' ),
				'className'   => 'date',
				'orderable'   => true,
				'searchable'  => false,
				'visible'     => true,
				'searchPanes' => [
					'show' => false
				],
			],
		];
	}
}