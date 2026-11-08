<?php

declare( strict_types=1 );

use Rector\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector;
use Rector\CodingStyle\Rector\FuncCall\ConsistentImplodeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
	->withPaths( [
		__DIR__ . '/Alias.php',
		__DIR__ . '/dompdf_config.inc.php',
		__DIR__ . '/src',
	] )
	->withPhpSets()
	->withComposerBased()
	->withSkip( [
		// Keep consecutive class member declarations together.
		NewlineBetweenClassLikeStmtsRector::class,
		ConsistentImplodeRector::class,
	] )
	->withPreparedSets(
		deadCode: true,
		codeQuality: true,
		codingStyle: true,
		typeDeclarations: true,
		privatization: true,
	);
