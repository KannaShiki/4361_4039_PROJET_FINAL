<?php

use App\Models\OperatorPrefixModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class AdminFeeBracketValidationTest extends CIUnitTestCase
{
    public function testAdminModelsDoNotUseAutomaticTimestamps(): void
    {
        $model = new OperatorPrefixModel();
        $reflection = new ReflectionClass($model);
        $property = $reflection->getProperty('useTimestamps');
        $property->setAccessible(true);

        $this->assertFalse($property->getValue($model));
    }
}
