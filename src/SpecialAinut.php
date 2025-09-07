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
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Status\Status;
use Override;

class SpecialAinut extends FormSpecialPage {
	private ?Application $app;

	public function __construct(
		private readonly ApplicationManager $appManager
	) {
		parent::__construct( 'Ainut' );
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
		$this->checkReadOnly();

		$userId = $this->getUser()->getId();
		if ( $par && $this->getUser()->isAllowed( 'ainut-admin' ) ) {
			$this->app = $this->appManager->findById( (int)$par );
		} else {
			if ( !$this->getConfig()->get( 'AinutApplicationsOpen' ) ) {
				throw new ErrorPageError( 'ainut', 'ainut-app-closed' );
			}

			$this->app = $this->appManager->findLatestByUser( $userId );
		}

		if ( !$this->app ) {
			$this->app = new Application( $userId );
		}

		parent::execute( $par );
	}

	#[Override]
	protected function getFormFields(): array {
		$appForm = new ApplicationForm();
		return $appForm->getFormFields(
			$this->app->getFields(),
			[ $this->getContext(), 'msg' ]
		);
	}

	#[Override]
	protected function alterForm( HTMLForm $form ): void {
		$this->getOutput()->addModuleStyles( 'ext.ainut.form.styles' );
		$form->setId( 'ainut-app-form' );
		$form->setSubmitTextMsg( 'ainut-app-submit' );

		if ( $this->app->getRevision() > 0 ) {
			$ts = $this->getLanguage()->timeanddate( $this->app->getTimestamp() );
			$msg = new RawMessage( Html::successBox( '$1' ) );
			$msg->params( $this->msg( 'ainut-app-old', $ts ) );
			$form->addPreHtml( $msg->parseAsBlock() );
		}

		$msg = new RawMessage( '<br>' . Html::successBox( '$1' ) );
		$msg->params( $this->msg( 'ainut-app-presave' ) );
		$form->addPostHtml( $msg->parseAsBlock() );

		$msg = new RawMessage( Html::warningBox( '$1' ) );
		$msg->params( $this->msg( 'ainut-app-guide' ) );
		$form->addPreHtml( $msg->parseAsBlock() );
	}

	#[Override]
	public function onSubmit( array $data ): Status {
		$this->app->setRevision( $this->app->getRevision() + 1 );
		$this->app->setFields( $data );
		$this->app->setTimestamp( 0 );
		$this->appManager->saveApplication( $this->app );

		return Status::newGood();
	}

	#[Override]
	public function onSuccess(): void {
		$out = $this->getOutput();

		$out->wrapWikiMsg( Html::successBox( '$1' ), 'ainut-app-saved' );
		$out->addReturnTo( $this->getPageTitle() );
	}
}
