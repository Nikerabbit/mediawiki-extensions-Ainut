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

use ErrorPageError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\RawMessage;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use Override;
use PermissionsError;

class SpecialAinutReview extends FormSpecialPage {
	private ?Application $app;
	private ?Review $rev;

	public function __construct(
		private readonly ApplicationManager $applicationManager,
		private readonly ReviewManager $reviewManager,
		private readonly LinkRenderer $linkRenderer,
		private readonly UserFactory $userFactory
	) {
		parent::__construct( 'AinutReview' );
	}

	#[Override]
	public function isListed(): bool {
		return false;
	}

	#[Override]
	protected function getDisplayFormat(): string {
		return 'ooui';
	}

	#[Override]
	public function execute( $par ): void {
		$this->requireLogin();
		$this->checkExecutePermissions( $this->getUser() );

		$out = $this->getOutput();
		$apps = $this->applicationManager->getFinalApplications();

		if ( !$par ) {
			$this->setHeaders();
			$listing = $this->getApplicationListing( $apps );
			$out->addHtml( $listing );
			return;
		}

		$this->checkReadOnly();

		foreach ( $apps as $app ) {
			if ( $app->getId() === (int)$par ) {
				$this->app = $app;

				$userId = $this->getUser()->getId();
				$this->rev = $this->reviewManager->findByUserAndApplication( $userId, $app->getId() );

				if ( !$this->rev ) {
					$this->rev = new Review( $userId, $this->app->getId() );
				}
				parent::execute( $par );
				return;
			}
		}

		$this->setHeaders();
		$out->wrapWikiMsg( Html::errorBox( '$1' ), 'ainoa-rev-err-id1' );
		$out->addReturnTo( $this->getPageTitle() );
	}

	#[Override]
	protected function checkExecutePermissions( User $user ): void {
		if ( !$this->getConfig()->get( 'AinutReviewsOpen' ) ) {
			throw new ErrorPageError( 'ainutreview', 'ainut-rev-closed' );
		}

		if ( !$user->isAllowed( 'ainut-review' ) ) {
			throw new PermissionsError( 'ainut-review' );
		}
	}

	private function getApplicationListing( array $apps ): string {
		$output = [];

		$rows = [];
		$rows[] = implode(
			[
				Html::element( 'th', [], $this->msg( 'ainut-revlist-name' )->text() ),
				Html::element( 'th', [], $this->msg( 'ainut-revlist-submitter' )->text() ),
				Html::element( 'th', [], $this->msg( 'ainut-revlist-reviewed' )->text() ),
			]
		);

		foreach ( $apps as $app ) {
			$rev = $this->reviewManager->findByUserAndApplication(
				$this->getUser()->getId(),
				$app->getId()
			);

			$reviewed = $rev ? '✓ ' : '';

			$link = $this->linkRenderer->makeLink(
				$this->getPageTitle( $app->getId() ),
				$reviewed . $this->msg( 'ainut-revlist-act' )->text()
			);

			$rows[] = implode(
				[
					Html::element( 'td', [], $app->getFields()['title'] ),
					Html::element( 'td', [], $this->userFactory->newFromId(
						$app->getUser()
						)->getName() ),
					Html::rawElement( 'td', [], $link ),
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

		return implode( $output );
	}

	#[Override]
	protected function getFormFields(): array {
		$appForm = new ApplicationForm();
		$fields = $appForm->getFormFields(
			$this->app->getFields(),
			[ $this->getContext(), 'msg' ]
		);

		foreach ( $fields as &$field ) {
			$field['disabled'] = true;
			$field['required'] = false;
			unset( $field['validation-callback'], $field['help-message'] );

			if ( isset( $field['type'] ) && $field['type'] === 'textarea' ) {
				$escapedText = htmlspecialchars( $field['default'] );
				$formattedText = str_replace( "\n", "<br>", $escapedText );
				$field['default'] = $formattedText;
				$field['type'] = 'info';
				$field['raw'] = 'true';
			}
		}

		$fields['title']['type'] = 'info';
		$fields['location']['type'] = 'info';

		$defaults = $this->rev->getFields();

		$newFields = [
			'review' => [
				'type' => 'textarea',
				'label-message' => 'ainut-rev-review',
				'help-message' => "ainut-rev-review-notice",
				'rows' => 10,
				'maxlength' => 1000,
				'default' => $defaults['review'] ?? '',
				'required' => true,
				'cssclass' => 'mw-ainut-len-1000',
			],
			'submit' => [
				'type' => 'submit',
				'buttonlabel-message' => 'ainut-rev-submit',
			],
		];

		return $newFields + $fields;
	}

	#[Override]
	protected function alterForm( HTMLForm $form ): void {
		$this->getOutput()->addModuleStyles( 'ext.ainut.form.styles' );
		$form->setId( 'ainut-app-form' );
		if ( $this->rev->getId() !== null ) {
			$ts = $this->getLanguage()->timeanddate( $this->app->getTimestamp() );
			$msg = new RawMessage( Html::successBox( '$1' ) );
			$msg->params( $this->msg( 'ainut-rev-old', $ts ) );
			$form->addPreHtml( $msg->parseAsBlock() );
		}
		$form->suppressDefaultSubmit();

		$list = $this->getPageTitle()->getFullUrl();
		$msg = new RawMessage( '<div class="plainlinks">$1</div>' );
		$msg->params( $this->msg( 'ainut-rev-back', $list ) );
		$this->getOutput()->addSubtitle( $msg->parse() );
	}

	#[Override]
	public function onSubmit( array $data ): Status {
		$this->rev->setFields( [ 'review' => $data['review'] ] );
		$this->rev->setTimestamp( 0 );
		$this->reviewManager->saveReview( $this->rev );

		return Status::newGood();
	}

	#[Override]
	public function onSuccess(): void {
		$out = $this->getOutput();

		$out->wrapWikiMsg( Html::successBox( '$1' ), 'ainut-rev-saved' );
		$out->addReturnTo( $this->getPageTitle() );
	}
}
