<?php
/**
 * Application form.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0+
 */

namespace Ainut;

class Application {
	/// @var int The application id for saved applications.
	protected $id;

	/// @var int User id who made the application.
	protected $user;

	/// @var \MWTimestamp Timestamp when the application was saved.
	protected $timestamp;

	/// @var string Access code. Maximum length is 10 bytes.
	protected $code;

	/// @var int Revision number.
	protected $revision;

	/// @var string Title of the application.
	protected $title;

	/// @var array Application fields and values.
	protected $fields;

	public function __construct( $user ) {
		$this->user = $user;
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

	public function getRevision() {
		return $this->revision ?: 0;
	}

	public function getCode() {
		return $this->code = $this->code ?: bin2hex( random_bytes( 5 ) );
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

	public function setRevision( $x ) {
		$this->revision = (int)$x;
	}

	public function setCode( $x ) {
		$this->code = $x;
	}

	public function setFields( array $x ) {
		$this->fields = $x;
	}
}
