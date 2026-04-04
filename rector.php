<?php

declare( strict_types=1 );

use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodingStyle\Rector\FuncCall\ConsistentImplodeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
	->withPaths( [
		__DIR__ . '/src',
	] )
	->withPhpSets()
	->withSkip( [
		ConsistentImplodeRector::class,
		ExplicitBoolCompareRector::class
	] )
	->withPreparedSets(
		deadCode: true,
		codeQuality: true,
		typeDeclarations: true,
		privatization: true,
	);
