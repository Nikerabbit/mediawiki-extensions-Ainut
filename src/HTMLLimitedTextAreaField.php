<?php
declare( strict_types=1 );
/**
 * A text area that can take data attributes.
 */

namespace Ainut;

use HTMLTextAreaField;
use Override;

class HTMLLimitedTextAreaField extends HTMLTextAreaField {
	#[Override]
	public function getAttributes( $list ): array {
		$list[] = 'data-mw-ainut-len';
		return parent::getAttributes( $list );
	}
}
