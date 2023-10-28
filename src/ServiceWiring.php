<?php
/**
 * List of services in this extension with construction instructions.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 */

use Ainut\ApplicationManager;
use Ainut\DocumentExporter;
use Ainut\ReviewManager;
use MediaWiki\MediaWikiServices;

/** @phpcs-require-sorted-array */
return [
	'Ainut:ApplicationManager' => static function ( MediaWikiServices $services ): ApplicationManager {
		return new ApplicationManager( $services->getDBLoadBalancer() );
	},

	'Ainut:DocumentExporter' => static function ( MediaWikiServices $services ): DocumentExporter {
		return new DocumentExporter( $services->getUserFactory() );
	},

	'Ainut:ReviewManager' => static function ( MediaWikiServices $services ): ReviewManager {
		return new ReviewManager( $services->getDBLoadBalancer() );
	},
];
