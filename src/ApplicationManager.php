<?php
declare( strict_types=1 );
/**
 * Application manager.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 */

namespace Ainut;

use Wikimedia\Rdbms\ILoadBalancer;

readonly class ApplicationManager {
	public function __construct( private ILoadBalancer $loadBalancer ) {
	}

	public function saveApplication( Application $app ): void {
		$db = $this->loadBalancer->getConnection( DB_PRIMARY );

		$fields = $app->getFields();

		$data = [
			'aia_timestamp' => $db->timestamp( $app->getTimestamp() ),
			'aia_user' => $app->getUser(),
			'aia_code' => $app->getCode(),
			'aia_revision' => $app->getRevision(),
			'aia_value' => json_encode( $fields, JSON_UNESCAPED_UNICODE ),
		];

		$db->insert( 'ainut_app', $data, __METHOD__ );
	}

	public function findLatestByUser( int $id ): ?Application {
		$db = $this->loadBalancer->getConnection( DB_REPLICA );

		$row = $db->selectRow(
			'ainut_app',
			'*',
			[ 'aia_user' => $id ],
			__METHOD__,
			[ 'ORDER BY' => 'aia_revision DESC, aia_timestamp DESC' ]
		);

		return $row ? self::newAppFromRow( $row ) : null;
	}

	protected static function newAppFromRow( $row ): Application {
		$app = new Application( (int)$row->aia_user );
		$app->setId( (int)$row->aia_id );
		$app->setTimestamp( (int)$row->aia_timestamp );
		$app->setCode( $row->aia_code );
		$app->setRevision( (int)$row->aia_revision );
		$app->setFields( json_decode( $row->aia_value, true ) );
		return $app;
	}

	public function findById( int $id ): ?Application {
		$db = $this->loadBalancer->getConnection( DB_REPLICA );
		$row = $db->selectRow( 'ainut_app', '*', [ 'aia_id' => $id ], __METHOD__ );
		return $row ? self::newAppFromRow( $row ) : null;
	}

	public function getFinalApplications(): array {
		$db = $this->loadBalancer->getConnection( DB_REPLICA );

		$res = $db->select(
			'ainut_app',
			'*',
			[],
			__METHOD__,
			[
				'ORDER BY' => 'aia_revision DESC, aia_timestamp DESC',
			]
		);

		$apps = array_map( [ self::class, 'newAppFromRow' ], iterator_to_array( $res ) );
		$appsByUser = [];
		foreach ( $apps as $app ) {
			$user = $app->getUser();
			if ( !isset( $appsByUser[$user] ) ||
				$app->getTimestamp() > $appsByUser[$user]->getTimestamp() ) {
				$appsByUser[$user] = $app;
			}
		}

		return array_values( $appsByUser );
	}
}
