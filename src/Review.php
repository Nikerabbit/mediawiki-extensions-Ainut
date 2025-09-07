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

class Review {
	/** The application id for saved reviews. */
	private int $id;
	/** Timestamp when the review was saved. */
	private int $timestamp;
	/** Application fields and values. */
	private array $fields;

	public function __construct(
		private readonly int $user,
		private readonly int $appId
	) {
	}

	public function getId(): ?int {
		return $this->id ?? null;
	}

	public function setId( int $x ): void {
		$this->id = $x;
	}

	public function getUser(): int {
		return $this->user;
	}

	public function getTimestamp(): int {
		$this->timestamp ??= time();

		return $this->timestamp;
	}

	public function setTimestamp( int $x ): void {
		$this->timestamp = $x;
	}

	public function getApplicationId(): int {
		return $this->appId;
	}

	public function getFields(): array {
		return $this->fields ?? [];
	}

	public function setFields( array $x ): void {
		$this->fields = $x;
	}
}
