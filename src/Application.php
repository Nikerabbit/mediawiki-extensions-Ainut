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

class Application {
	/** The application id for saved applications. */
	private int $id;
	/** User id who made the application. */
	private int $user;
	/** Timestamp when the application was saved. */
	private int $timestamp;
	/** Access code. Maximum length is 10 bytes. */
	private string $code;
	/** Revision number. */
	private int $revision;
	/** Application fields and values. */
	private array $fields;

	public function __construct( int $user ) {
		$this->user = $user;
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

	public function getRevision(): int {
		return $this->revision ?? 0;
	}

	public function setRevision( int $x ): void {
		$this->revision = $x;
	}

	public function getCode(): string {
		$this->code ??= bin2hex( random_bytes( 5 ) );

		return $this->code;
	}

	public function setCode( string $x ): void {
		$this->code = $x;
	}

	public function getFields(): array {
		return $this->fields ?? [];
	}

	public function setFields( array $x ): void {
		$this->fields = $x;
	}
}
