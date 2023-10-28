<?php
declare( strict_types=1 );

namespace Ainut;

use MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook;

/**
 * Schema hook handlers are not allowed any dependencies
 */
class SchemaHookHandler implements LoadExtensionSchemaUpdatesHook {

	public function onLoadExtensionSchemaUpdates( $updater ): void {
		$dir = __DIR__;

		$updater->addExtensionUpdate(
			[
				'addTable',
				'ainut_app',
				"$dir/ainut.sql",
				true,
			]
		);
	}

}
