<?php
declare( strict_types=1 );
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
	'Ainut:ApplicationManager' => static fn ( MediaWikiServices $services ): ApplicationManager =>
		new ApplicationManager( $services->getDBLoadBalancer() ),
	'Ainut:DocumentExporter' => static fn ( MediaWikiServices $services ): DocumentExporter =>
		new DocumentExporter( $services->getUserFactory() ),
	'Ainut:ReviewManager' => static fn ( MediaWikiServices $services ): ReviewManager =>
		new ReviewManager( $services->getDBLoadBalancer() ),
];
