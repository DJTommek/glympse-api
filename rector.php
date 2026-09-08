<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Rector\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector;
use Rector\Php80\Rector\FuncCall\ClassOnObjectRector;
use Rector\Php80\Rector\Identical\StrEndsWithRector;
use Rector\Php80\Rector\Identical\StrStartsWithRector;
use Rector\Php80\Rector\NotIdentical\StrContainsRector;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;
use Rector\Php80\Rector\Ternary\GetDebugTypeRector;
use Rector\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector;
use Rector\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector;
use Rector\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector;
use Rector\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector;
use Rector\Php81\Rector\Array_\ArrayToFirstClassCallableRector;
use Rector\Php81\Rector\Class_\MyCLabsClassToEnumRector;
use Rector\Php81\Rector\Class_\SpatieEnumClassToEnumRector;
use Rector\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector;
use Rector\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector;
use Rector\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;
use Rector\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Php83\Rector\Class_\ReadOnlyAnonymousClassRector;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\Php83\Rector\FuncCall\DynamicClassConstFetchRector;
use Rector\Php83\Rector\BooleanAnd\JsonValidateRector;
use Rector\Php84\Rector\Foreach_\ForeachToArrayAllRector;
use Rector\Php84\Rector\Foreach_\ForeachToArrayAnyRector;
use Rector\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector;
use Rector\Php84\Rector\Foreach_\ForeachToArrayFindRector;
use Rector\Php84\Rector\FuncCall\AddEscapeArgumentRector;
use Rector\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;
use Rector\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector;
use Rector\Php85\Rector\Class_\SleepToSerializeRector;
use Rector\Php85\Rector\Class_\WakeupToUnserializeRector;
use Rector\Php85\Rector\ClassMethod\NullDebugInfoReturnRector;
use Rector\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector;
use Rector\Php85\Rector\FuncCall\ChrArgModuloRector;
use Rector\Php85\Rector\FuncCall\OrdSingleByteRector;
use Rector\Php85\Rector\FuncCall\RemoveFinfoBufferContextArgRector;
use Rector\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector;
use Rector\Php85\Rector\ShellExec\ShellExecFunctionCallOverBackticksRector;
use Rector\Php85\Rector\Switch_\ColonAfterSwitchCaseRector;
use Rector\Php86\Rector\FuncCall\MinMaxToClampRector;
use Rector\Set\ValueObject\SetList;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    // Project still targets PHP 7.4 (see composer.json "require.php"). We enable
    // Rector's full PHP-version rule set (which unlocks rules that fix real
    // deprecations reported on PHP 8.x, e.g. ${var} string interpolation,
    // utf8_encode/decode, implicit-nullable params) but explicitly skip every
    // rule below that would rewrite code into syntax or functions PHP 7.4
    // doesn't have (constructor promotion, readonly, enums, match, first-class
    // callables, etc). After running Rector, always confirm with
    // `php vendor/bin/rector process --dry-run` and a 7.4 `php -l` lint that no
    // 8.0+-only syntax slipped in before applying/committing.
    ->withPhpVersion(PhpVersion::PHP_85)
    ->withSets([
        SetList::PHP_VERSION_BASED_SET,
    ])
    ->withSkip([
        // PHP 8.0 syntax/functions not available on 7.4
        StrContainsRector::class,
        StrStartsWithRector::class,
        StrEndsWithRector::class,
        StringableForToStringRector::class,
        ClassOnObjectRector::class,
        GetDebugTypeRector::class,
        RemoveUnusedVariableInCatchRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class,
        ChangeSwitchToMatchRector::class,
        AddParamBasedOnParentClassMethodRector::class,
        // PHP 8.1 syntax/functions not available on 7.4
        ArrayToFirstClassCallableRector::class,
        ArrowFunctionDelegatingCallToFirstClassCallableRector::class,
        ClosureDelegatingCallToFirstClassCallableRector::class,
        ClosureFromCallableToFirstClassCallableRector::class,
        FunctionFirstClassCallableRector::class,
        MyCLabsClassToEnumRector::class,
        MyCLabsMethodCallToEnumConstRector::class,
        MyCLabsConstructorCallToEnumFromRector::class,
        SpatieEnumClassToEnumRector::class,
        SpatieEnumMethodCallToEnumConstRector::class,
        ReadOnlyPropertyRector::class,
        // removes a call still required for private/protected reflection access on 7.4
        RemoveReflectionSetAccessibleCallsRector::class,
        // PHP 8.2 syntax not available on 7.4
        ReadOnlyClassRector::class,
        // PHP 8.3 syntax/functions not available on 7.4
        AddTypeToConstRector::class,
        ReadOnlyAnonymousClassRector::class,
        DynamicClassConstFetchRector::class,
        JsonValidateRector::class,
        // PHP 8.4 syntax/functions not available on 7.4
        AddEscapeArgumentRector::class,
        NewMethodCallWithoutParenthesesRector::class,
        ForeachToArrayFindRector::class,
        ForeachToArrayFindKeyRector::class,
        ForeachToArrayAllRector::class,
        ForeachToArrayAnyRector::class,
        // PHP 8.5 syntax/functions not available on 7.4 (skipped as a block - project
        // isn't targeting 8.5 yet, revisit individually if that changes)
        ArrayFirstLastRector::class,
        RemoveFinfoBufferContextArgRector::class,
        NullDebugInfoReturnRector::class,
        ColonAfterSwitchCaseRector::class,
        ArrayKeyExistsNullToEmptyStringRector::class,
        ChrArgModuloRector::class,
        SleepToSerializeRector::class,
        OrdSingleByteRector::class,
        WakeupToUnserializeRector::class,
        ShellExecFunctionCallOverBackticksRector::class,
        AddOverrideAttributeToOverriddenPropertiesRector::class,
        // PHP 8.6 functions not available on 7.4
        MinMaxToClampRector::class,
    ]);
