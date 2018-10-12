<?php
/**
 * Application form.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0+
 */

namespace Ainut;

class Review {
	/// @var int The application id for saved reviews.
	protected $id;

	/// @var int User id who made the review.
	protected $user;

	/// @var \MWTimestamp Timestamp when the review was saved.
	protected $timestamp;

	/// @var int Application number this review is for.
	protected $appId;

	/// @var array Application fields and values.
	protected $fields;

	public function __construct( $user, $appId ) {
		$this->user = $user;
		$this->appId = $appId;
	}

	public function getId() {
		return $this->id;
	}

	public function getUser() {
		return $this->user;
	}

	public function getTimestamp() {
		return $this->timestamp = $this->timestamp ?: time();
	}

	public function getApplicationId() {
		return $this->appId;
	}

	public function getFields() {
		return $this->fields ?: [];
	}

	public function setId( $x ) {
		$this->id = (int)$x;
	}

	public function setTimestamp( $x ) {
		$this->timestamp = $x;
	}

	public function setFields( array $x ) {
		$this->fields = $x;
	}
}
