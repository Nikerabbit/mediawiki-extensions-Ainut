<?php
/**
 * Application form.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 */

namespace Ainut;

use Html;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserFactory;
use Override;
use PermissionsError;
use SpecialPage;
use SplObjectStorage;
use Wikimedia\Rdbms\ILoadBalancer;

class SpecialAinutAdmin extends SpecialPage {
	private ApplicationManager $appManager;
	private ReviewManager $revManager;
	private ILoadBalancer $loadBalancer;
	private LinkRenderer $linkRenderer;
	private UserFactory $userFactory;

	public function __construct() {
		parent::__construct( 'AinutAdmin' );
		$services = MediaWikiServices::getInstance();
		$this->loadBalancer = $services->getDBLoadBalancer();
		$this->appManager = new ApplicationManager( $this->loadBalancer );
		$this->revManager = new ReviewManager( $this->loadBalancer );
		$this->linkRenderer = $services->getLinkRenderer();
		$this->userFactory = $services->getUserFactory();
	}

	#[Override]
	public function isListed(): bool {
		return false;
	}

	#[Override]
	public function execute( $par ): void {
		$this->requireLogin();

		if ( !$this->getUser()->isAllowed( 'ainut-admin' ) ) {
			throw new PermissionsError( 'ainut-admin' );
		}
		$this->setHeaders();

		$out = $this->getOutput();

		$this->appManager = new ApplicationManager( $lb );
		$this->revManager = new ReviewManager( $lb );

		if ( $par === 'export' ) {
			$format = $out->getRequest()->getText( 'format', 'Word2007' );
			$appId = $out->getRequest()->getInt( 'app', -1 );
			$appReviews = null;
			$filename = 'Unnamed';

			if ( $appId === -1 ) {
				$appReviews = $this->getAllReviewsByApplication();
				$filename = $this->msg( 'ainut-export-summary' )->plain();
			} else {
				$app = $this->appManager->findById( $appId );
				if ( $app ) {
					$appReviews = new SplObjectStorage();
					$appReviews[$app] = $this->revManager->findByApplication( $app->getId() );
					$filename = $app->getFields()['title'];
				}
			}

			if ( $appReviews ) {
				$de = new DocumentExporter();
				$doc = $de->createDocument( $appReviews, $this->getContext() );
				$de->printDocument( $doc, $filename, $format );
				$out->disable();
				return;
			}
		}

		$appReviews = $this->getAllReviewsByApplication();
		$listing = $this->getApplicationListing( $appReviews );
		$out->addHtml( $listing );
	}

	private function getAllReviewsByApplication(): SplObjectStorage {
		$s = new SplObjectStorage();
		$apps = $this->appManager->getFinalApplications();
		foreach ( $apps as $app ) {
			$s[$app] = $this->revManager->findByApplication( $app->getId() );
		}

		return $s;
	}

	private function getApplicationListing( SplObjectStorage $appReviews ): string {
		$output = [];

		$lang = $this->getLanguage();

		$rows = [];
		$rows[] = implode(
			[
				Html::element( 'th', [], $this->msg( 'ainut-revlist-name' )->text() ),
				Html::element( 'th', [], $this->msg( 'ainut-revlist-submitter' )->text() ),
				Html::element( 'th', [], $this->msg( 'ainut-revlist-reviewcount' )->text() ),
				Html::element( 'th', [], $this->msg( 'ainut-revlist-export' )->text() ),
			]
		);

		/** @var Application $app */
		foreach ( $appReviews as $app ) {
			/** @var Review[] $reviews */
			$reviews = $appReviews[$app];

			$exportLinks = [];
			$exportLinks[] = $this->linkRenderer->makeLink(
				$this->getPageTitle( 'export' ),
				'DOC',
				[ 'target' => '_blank' ],
				[ 'app' => $app->getId() ]
			);
			$exportLinks[] = $this->linkRenderer->makeLink(
				$this->getPageTitle( 'export' ),
				'PDF',
				[ 'target' => '_blank' ],
				[ 'app' => $app->getId(), 'format' => 'PDF' ]
			);

			$editLink = $this->linkRenderer->makeLink(
				SpecialPage::getTitleFor( 'Ainut', $app->getId() ),
				$app->getFields()['title']
			);

			$rows[] = implode(
				[
					Html::rawElement( 'td', [], $editLink ),
					Html::element( 'td', [], $this->userFactory->newFromId( $app->getUser() )->getName() ),
					Html::element( 'td', [], $lang->formatNum( count( $reviews ) ) ),
					Html::rawElement( 'td', [], implode( ' | ', $exportLinks ) ),
				]
			);
		}

		$rows = array_map(
			static function ( $x ) {
				return Html::rawElement( 'tr', [], $x );
			},
			$rows
		);
		$contents = implode( $rows );

		$output[] = Html::rawElement( 'table', [ 'class' => 'wikitable sortable' ], $contents );

		$GLOBALS[ 'wgUseMediaWikiUIEverywhere' ] = true;

		$output[] = Html::linkButton(
			$this->msg( 'ainut-export-summary-as-doc' ),
			[ 'href' => $this->getPageTitle( 'export' )->getLocalUrl() ],
			[ 'mw-ui-button', 'mw-ui-big' ]
		);

		$output[] = Html::linkButton(
			$this->msg( 'ainut-export-summary-as-pdf' ),
			[ 'href' => $this->getPageTitle( 'export' )->getLocalUrl( [ 'format' => 'PDF' ] ) ],
			[ 'mw-ui-button', 'mw-ui-big' ]
		);

		return implode( $output );
	}
}
