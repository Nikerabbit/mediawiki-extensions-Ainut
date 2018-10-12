<?php
/**
 * Application form.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0+
 */

namespace Ainut;

class Hooks {
	/**
	 * Hook: LoadExtensionSchemaUpdates
	 *
	 * @param \DatabaseUpdater $updater
	 */
	public static function schemaUpdates( \DatabaseUpdater $updater ) {
		$dir = __DIR__;

		$updater->addExtensionUpdate( [
			'addTable',
			'ainut_app',
			"$dir/ainut.sql",
			true
		] );
	}

	public static function onSidebarBeforeOutput( \Skin $skin, &$bar ) {
		global $wgAinutApplicationsOpen, $wgAinutReviewsOpen, $wgAinutResultsOpen;

		if ( !$wgAinutApplicationsOpen && !$wgAinutReviewsOpen && !$wgAinutResultsOpen ) {
			return;
		}

		$user = $skin->getUser();

		$sectionName = $skin->getContext()->msg( 'ainut-sidebar-section' )->plain();

		$helpPage = $skin->getContext()->msg( 'ainut-sidebar-help-page' )->plain();
		$helpPageTitle = \Title::newFromText( $helpPage );
		$section[$sectionName]['ainut-sidebar-help'] = [
			'href' => $helpPageTitle->getLocalURL(),
		];

		if ( $wgAinutResultsOpen ) {
			$resultPage = $skin->getContext()->msg( 'ainut-sidebar-list-page' )->plain();
			$resultPageTitle = \Title::newFromText( $resultPage );
			$section[$sectionName]['ainut-sidebar-list'] = [
				'href' => $resultPageTitle->getLocalURL(),
			];
		}

		if ( $wgAinutApplicationsOpen ) {
			$section[$sectionName]['ainut-sidebar-apply'] = [
				'href' => \SpecialPageFactory::getTitleForAlias( 'Ainut' )->getLocalURL(),
			];
		}

		if ( $wgAinutReviewsOpen && $user->isAllowed( 'ainut-review' ) ) {
			$section[$sectionName]['ainut-sidebar-review'] = [
				'href' => \SpecialPageFactory::getTitleForAlias( 'AinutReview' )->getLocalURL(),
			];
		}

		if ( ( $wgAinutApplicationsOpen|| $wgAinutReviewsOpen ) && $user->isAllowed( 'ainut-admin' ) ) {
			$section[$sectionName]['ainut-sidebar-manage'] = [
				'href' => \SpecialPageFactory::getTitleForAlias( 'AinutAdmin' )->getLocalURL(),
			];
		}

		$bar = \wfArrayInsertAfter( $bar, $section, 'SEARCH' );
	}
}
