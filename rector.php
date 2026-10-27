<?php

declare( strict_types=1 );

use Rector\CodingStyle\Rector\FuncCall\ConsistentImplodeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
	->withPaths( [
		__DIR__ . '/src',
	] )
	->withPhpSets()
	->withSkip( [
		ConsistentImplodeRector::class
	] )
	->withPreparedSets(
		deadCode: true,
		codeQuality: true,
		typeDeclarations: true,
		privatization: true,
	);
