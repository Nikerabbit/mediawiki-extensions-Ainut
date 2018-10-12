<?php

/**
 * A text area that can take data attributes.
 */

namespace Ainut;

class HTMLLimitedTextAreaField extends \HTMLTextAreaField {
	public function getAttributes( $list ) {
		$list[] = 'data-mw-ainut-len';
		return parent::getAttributes( $list );
	}
}
