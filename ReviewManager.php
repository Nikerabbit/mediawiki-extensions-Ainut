<?php
/**
 * Review manager.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 */

namespace Ainut;

class ReviewManager {
	protected $lb;

	public function __construct( \LoadBalancer $lb ) {
		$this->lb = $lb;
	}

	public function saveReview( Review $rev ) {
		$db = $this->lb->getConnection( DB_MASTER );

		$data = [
			'air_timestamp' => $db->timestamp( $rev->getTimestamp() ),
			'air_user' => $rev->getUser(),
			'air_aia' => $rev->getApplicationId(),
			'air_value' => json_encode( $rev->getFields(), JSON_UNESCAPED_UNICODE ),
		];

		if ( $rev->getId() === null ) {
			$db->insert( 'ainut_rev', $data, __METHOD__ );
		} else {
			$db->update( 'ainut_rev', $data, [ 'air_id' => $rev->getId() ], __METHOD__ );
		}
	}

	/**
	 * @param int $userId
	 * @param int $appId
	 * @return null|\Ainut\Review
	 */
	public function findByUserAndApplication( $userId, $appId ) {
		$db = $this->lb->getConnection( DB_REPLICA );

		$row = $db->selectRow(
			'ainut_rev',
			'*',
			[
				'air_user' => $userId,
				'air_aia' => $appId,
			],
			__METHOD__
		);

		return $row ? self::newReviewFromRow( $row ) : null;
	}

	/**
	 * @param int $appId
	 * @return \Ainut\Review[]
	 */
	public function findByApplication( $appId ) {
		$db = $this->lb->getConnection( DB_REPLICA );

		$res = $db->select(
			'ainut_rev',
			'*',
			[ 'air_aia' => $appId ],
			__METHOD__
		);

		$reviews = [];
		foreach ( $res as $row ) {
			$reviews[] = self::newReviewFromRow( $row );
		}

		return $reviews;
	}

	protected static function newReviewFromRow( $row ) {
		$app = new Review( $row->air_user, $row->air_aia );
		$app->setId( $row->air_id );
		$app->setTimestamp( $row->air_timestamp );
		$app->setFields( json_decode( $row->air_value, true ) );
		return $app;
	}
}
