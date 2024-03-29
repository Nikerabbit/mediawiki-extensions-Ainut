<?php
declare( strict_types=1 );
/**
 * Application form.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 */

namespace Ainut;

use Config;
use MediaWiki\Hook\SidebarBeforeOutputHook;
use MediaWiki\SpecialPage\SpecialPageFactory;
use Title;
use function wfArrayInsertAfter;

class HookHandler implements SidebarBeforeOutputHook {
	public function __construct(
		private readonly Config $config,
		private readonly SpecialPageFactory $specialPageFactory
	) {
	}

	public function onSidebarBeforeOutput( $skin, &$sidebar ): void {
		$ainutApplicationsOpen = $this->config->get( 'AinutApplicationsOpen' );
		$ainutReviewsOpen = $this->config->get( 'AinutReviewsOpen' );
		$ainutResultsOpen = $this->config->get( 'AinutResultsOpen' );

		if ( !$ainutApplicationsOpen && !$ainutReviewsOpen && !$ainutResultsOpen ) {
			return;
		}

		$user = $skin->getUser();

		$sectionName = $skin->getContext()->msg( 'ainut-sidebar-section' )->plain();

		$helpPage = $skin->getContext()->msg( 'ainut-sidebar-help-page' )->plain();
		$helpPageTitle = Title::newFromText( $helpPage );
		$section[$sectionName]['ainut-sidebar-help'] = [
			'href' => $helpPageTitle->getLocalURL(),
		];

		if ( $ainutResultsOpen ) {
			$resultPage = $skin->getContext()->msg( 'ainut-sidebar-list-page' )->plain();
			$resultPageTitle = Title::newFromText( $resultPage );
			$section[$sectionName]['ainut-sidebar-list'] = [
				'href' => $resultPageTitle->getLocalURL(),
			];
		}

		if ( $ainutApplicationsOpen ) {
			$section[$sectionName]['ainut-sidebar-apply'] = [
				'href' => $this->specialPageFactory->getTitleForAlias( 'Ainut' )->getLocalURL(),
			];
		}

		if ( $ainutReviewsOpen && $user->isAllowed( 'ainut-review' ) ) {
			$section[$sectionName]['ainut-sidebar-review'] = [
				'href' => $this->specialPageFactory->getTitleForAlias( 'AinutReview' )->getLocalURL(),
			];
		}

		if ( ( $ainutApplicationsOpen || $ainutReviewsOpen ) &&
			$user->isAllowed( 'ainut-admin' ) ) {
			$section[$sectionName]['ainut-sidebar-manage'] = [
				'href' => $this->specialPageFactory->getTitleForAlias( 'AinutAdmin' )->getLocalURL(),
			];
		}

		$sidebar = wfArrayInsertAfter( $sidebar, $section, 'SEARCH' );
	}
}
