<?php
/**
 * Application manager.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0+
 */

namespace Ainut;

class ApplicationManager {
	protected $lb;

	public function __construct( \LoadBalancer $lb ) {
		$this->lb = $lb;
	}

	public function saveApplication( Application $app ) {
		$db = $this->lb->getConnection( DB_MASTER );

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

	/**
	 * @param int $id
	 * @return null|Ainut\Application
	 */
	public function findLatestByUser( $id ) {
		$db = $this->lb->getConnection( DB_REPLICA );

		$row = $db->selectRow(
			'ainut_app',
			'*',
			[ 'aia_user' => $id ],
			__METHOD__,
			[ 'ORDER BY' => 'aia_revision DESC, aia_timestamp DESC' ]
		);

		return $row ? self::newAppFromRow( $row ) : null;
	}

	/**
	 * @param int $id
	 * @return null|Ainut\Application
	 */
	public function findById( $id ) {
		$db = $this->lb->getConnection( DB_REPLICA );
		$row = $db->selectRow( 'ainut_app', '*', [ 'aia_id' => $id ], __METHOD__ );
		return $row ? self::newAppFromRow( $row ) : null;
	}

	public function getFinalApplications() {
		$db = $this->lb->getConnection( DB_REPLICA );

		$res = $db->select(
			'ainut_app',
			'*',
			[],
			__METHOD__,
			[
				'ORDER BY' => 'aia_revision DESC, aia_timestamp DESC',
			]
		);

		$apps = array_map( 'self::newAppFromRow', iterator_to_array( $res ) );
		$appsByUser = [];
		foreach ( $apps as $app ) {
			$user = $app->getUser();
			if (
				!isset( $appsByUser[ $user ] ) ||
				$app->getTimestamp() > $appsByUser[ $user ]->getTimestamp()
			) {
				$appsByUser[ $user ] = $app;
			}
		}

		return array_values( $appsByUser );
	}

	protected static function newAppFromRow( $row ) {
		$app = new Application( $row->aia_user );
		$app->setId( $row->aia_id );
		$app->setTimestamp( $row->aia_timestamp );
		$app->setCode( $row->aia_code );
		$app->setRevision( $row->aia_revision );
		$app->setFields( json_decode( $row->aia_value, true ) );
		return $app;
	}
}
