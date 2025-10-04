<?php
declare( strict_types=1 );
/**
 * Review manager.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 */

namespace Ainut;

use stdClass;
use Wikimedia\Rdbms\ILoadBalancer;

readonly class ReviewManager {
	private ILoadBalancer $loadBalancer;

	public function __construct( ILoadBalancer $lb ) {
		$this->loadBalancer = $lb;
	}

	public function saveReview( Review $rev ): void {
		$db = $this->loadBalancer->getConnection( DB_PRIMARY );

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

	public function findByUserAndApplication( int $userId, int $appId ): ?Review {
		$db = $this->loadBalancer->getConnection( DB_REPLICA );

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

	protected static function newReviewFromRow( stdClass $row ): Review {
		$app = new Review( (int)$row->air_user, (int)$row->air_aia );
		$app->setId( (int)$row->air_id );
		$app->setTimestamp( (int)$row->air_timestamp );
		$app->setFields( json_decode( (string)$row->air_value, true ) );
		return $app;
	}

	/** @return Review[] */
	public function findByApplication( int $appId ): array {
		$db = $this->loadBalancer->getConnection( DB_REPLICA );

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
}
