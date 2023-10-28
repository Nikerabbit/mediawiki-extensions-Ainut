<?php
/**
 * Application form.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 */

namespace Ainut;

class Review {
	/** The application id for saved reviews. */
	private int $id;
	/** User id who made the review. */
	private int $user;
	/** Timestamp when the review was saved. */
	private int $timestamp;
	/** Application number this review is for. */
	private int $appId;
	/** Application fields and values. */
	private array $fields;

	public function __construct( int $user, int $appId ) {
		$this->user = $user;
		$this->appId = $appId;
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function setId( int $x ): void {
		$this->id = $x;
	}

	public function getUser(): int {
		return $this->user;
	}

	public function getTimestamp(): int {
		$this->timestamp = $this->timestamp ?: time();

		return $this->timestamp;
	}

	public function setTimestamp( int $x ): void {
		$this->timestamp = $x;
	}

	public function getApplicationId(): int {
		return $this->appId;
	}

	public function getFields(): array {
		return $this->fields ?: [];
	}

	public function setFields( array $x ): void {
		$this->fields = $x;
	}
}
