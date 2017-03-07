<?php

declare(strict_types=1);

namespace AdminPanel\Component\Reflection\Tests;

use AdminPanel\Component\Reflection\ReflectionClass;
use AdminPanel\Component\Reflection\ReflectionProperty;
use AdminPanel\Component\Reflection\ReflectionMethod;
use AdminPanel\Component\Reflection\Tests\Fixture\ClassA;
use AdminPanel\Component\Reflection\Tests\Fixture\ClassAParent;
use AdminPanel\Component\Reflection\Tests\Fixture\ClassAParentParent;

class SampleTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $classReflection1 = ReflectionClass::factory(ClassA::class);
        $classReflection2 = ReflectionClass::factory(ClassA::class);
        $this->assertSame($classReflection1, $classReflection2);

        $classReflection3 = $classReflection2->getMethod('privateMethod')->getDeclaringClass();
        $this->assertSame($classReflection2, $classReflection3);

        $classReflection4 = ReflectionClass::factory(new ClassA('param'));
        $this->assertSame($classReflection1, $classReflection4);

        $classReflection5 = $classReflection2->getProperty('privateProperty')->getDeclaringClass();
        $this->assertSame($classReflection2, $classReflection5);

        $obj = new ClassA('param');
        $classReflection6 = ReflectionClass::factory($obj);
        $this->assertSame($classReflection2, $classReflection6);
    }

    public function testClassGetProperties()
    {
        $filters = [
            ReflectionProperty::IS_STATIC,
            ReflectionProperty::IS_PUBLIC ,
            ReflectionProperty::IS_PROTECTED,
            ReflectionProperty::IS_PRIVATE
        ];

        $filtersCombinations = $this->pc_array_power_set($filters);

        foreach ($filtersCombinations as $combination) {
            $filter = null;
            foreach ($combination as $value) {
                if (!isset($filter)) {
                    $filter = $value;
                } else {
                    $filter = $filter | $value;
                }
            }
            $this->_testClassGetProperties($filter);
        }
    }

    protected function _testClassGetProperties($filter = null)
    {
        $adminPanelClassReflection = ReflectionClass::factory(ClassA::class);
        $classReflection    = new \ReflectionClass(ClassA::class);

        $adminPanelReflectionProperties  = isset($filter) ? $adminPanelClassReflection->getProperties($filter) : $adminPanelClassReflection->getProperties();
        $reflectionProperties     = isset($filter) ? $classReflection->getProperties($filter) : $classReflection->getProperties();

        $this->assertSame(count($adminPanelReflectionProperties), count($reflectionProperties));

        foreach ($adminPanelReflectionProperties as $index => $reflectionProperty) {
            $reflectionPropertyNew = ReflectionProperty::factory($reflectionProperty->class, $reflectionProperty->name);
            $this->assertSame($reflectionPropertyNew, $reflectionProperty);

            $orgReflectionProperty = $reflectionProperties[$index];

            $this->assertSame($orgReflectionProperty->name, $reflectionProperty->name);
            $this->assertSame($orgReflectionProperty->class, $reflectionProperty->class);
        }
    }

    public function testClassGetMethods()
    {
        $filters = [
            ReflectionMethod::IS_STATIC,
            ReflectionMethod::IS_PUBLIC ,
            ReflectionMethod::IS_PROTECTED,
            ReflectionMethod::IS_PRIVATE,
            ReflectionMethod::IS_ABSTRACT,
            ReflectionMethod::IS_FINAL
        ];

        $filtersCombinations = $this->pc_array_power_set($filters);

        foreach ($filtersCombinations as $combination) {
            $filter = null;
            foreach ($combination as $value) {
                if (!isset($filter)) {
                    $filter = $value;
                } else {
                    $filter = $filter | $value;
                }
            }
            $this->_testClassGetMethods($filter);
        }
    }

    protected function _testClassGetMethods($filter = null)
    {
        $adminPanelClassReflection = ReflectionClass::factory(ClassA::class);
        $classReflection    = new \ReflectionClass(ClassA::class);

        $adminPanelReflectionMethods  = isset($filter) ? $adminPanelClassReflection->getMethods($filter) : $adminPanelClassReflection->getMethods();
        $reflectionMethods     = isset($filter) ? $classReflection->getMethods($filter) : $classReflection->getMethods();

        $this->assertSame(count($adminPanelReflectionMethods), count($reflectionMethods));

        foreach ($adminPanelReflectionMethods as $index => $reflectionMethod) {
            $reflectionMethodNew = ReflectionMethod::factory($reflectionMethod->class, $reflectionMethod->name);
            $this->assertSame($reflectionMethodNew, $reflectionMethod);

            $orgReflectionMethod = $reflectionMethods[$index];

            $this->assertSame($orgReflectionMethod->name, $reflectionMethod->name);
            $this->assertSame($orgReflectionMethod->class, $reflectionMethod->class);
        }
    }

    public function testMethod()
    {
        $methodReflection1 = ReflectionMethod::factory(ClassA::class, 'protectedMethod');
        $methodReflection2 = ReflectionMethod::factory(ClassA::class, 'protectedMethod');
        $this->assertSame($methodReflection1, $methodReflection2);

        $methodReflection3 = ReflectionClass::factory(ClassA::class)->getMethod('protectedMethod');
        $this->assertSame($methodReflection1, $methodReflection3);

        $obj = new ClassA('param');
        $methodReflection4 = ReflectionMethod::factory($obj, 'protectedMethod');
        $this->assertSame($methodReflection1, $methodReflection4);

        $res = $methodReflection1->invoke($obj, 'foo', 'bar');
        $this->assertEquals($res, 'foo+bar');

        $methodReflection5 = ReflectionMethod::factory(ClassA::class, 'privateMethod');
        $res = $methodReflection5->invoke($obj, 'foo', 'bar');
        $this->assertEquals($res, 'foo-bar');

        $methodReflection6 = ReflectionMethod::factory(ClassA::class, 'publicMethod');
        $res = $methodReflection6->invoke($obj, 'foo', 'bar');
        $this->assertEquals($res, 'foo=bar');
    }

    public function testInvalidMethod()
    {
        $this->setExpectedException('ReflectionException');
        $methodReflection = ReflectionMethod::factory(ClassA::class, 'invalidMethod');
    }

    public function testProperty()
    {
        $propertyReflection1 = ReflectionProperty::factory(ClassA::class, 'protectedProperty');
        $propertyReflection2 = ReflectionProperty::factory(ClassA::class, 'protectedProperty');
        $this->assertSame($propertyReflection1, $propertyReflection2);

        $propertyReflection3 = ReflectionClass::factory(ClassA::class)->getProperty('protectedProperty');
        $this->assertSame($propertyReflection1, $propertyReflection3);

        $obj = new ClassA('param');
        $propertyReflection4 = ReflectionProperty::factory($obj, 'protectedProperty');
        $this->assertSame($propertyReflection1, $propertyReflection4);

        $propertyReflection1->setValue($obj, 'foo');
        $this->assertAttributeEquals('foo', 'protectedProperty', $obj);
        $this->assertEquals('foo', $propertyReflection1->getValue($obj));

        $propertyReflection5 = ReflectionProperty::factory(ClassA::class, 'privateProperty');
        $propertyReflection5->setValue($obj, 'bar');
        $this->assertAttributeEquals('bar', 'privateProperty', $obj);
        $this->assertEquals('bar', $propertyReflection5->getValue($obj));

        $propertyReflection6 = ReflectionProperty::factory(ClassA::class, 'publicProperty');
        $propertyReflection6->setValue($obj, 'baz');
        $this->assertAttributeEquals('baz', 'publicProperty', $obj);
        $this->assertEquals('baz', $propertyReflection6->getValue($obj));
    }

    public function testInvalidProperty()
    {
        $this->setExpectedException('ReflectionException');
        $propertyReflection = ReflectionProperty::factory(ClassA::class, 'invalidProperty');
    }

    public function testExceptionClass()
    {
        $this->setExpectedException('ReflectionException');
        $reflectionClass = new ReflectionClass(ClassA::class);
    }

    public function testExceptionProperty()
    {
        $this->setExpectedException('ReflectionException');
        $reflectionProperty = new ReflectionProperty(ClassA::class, 'protectedProperty');
    }

    public function testExceptionMethod()
    {
        $this->setExpectedException('ReflectionException');
        $reflectionMethod = new ReflectionMethod(ClassA::class, 'protectedMethod');
    }

    public function testClassInterfaces()
    {
        $adminPanelClassReflection = ReflectionClass::factory(ClassA::class);
        $classReflection    = new \ReflectionClass(ClassA::class);

        $adminPanelClassInterfaces = $adminPanelClassReflection->getInterfaces();
        $classInterfaces    = $classReflection->getInterfaces();

        $this->assertSame(count($adminPanelClassInterfaces), count($classInterfaces));

        foreach ($adminPanelClassInterfaces as $name => $interfaceReflection) {
            $orgInterface = $classInterfaces[$name];
            $this->assertEquals($orgInterface->name, $interfaceReflection->name);
        }
    }

    public function testGetParentClassPropertiesAndMethods()
    {
        $publicProperty3     = ReflectionProperty::factory(ClassA::class, 'publicProperty3');
        $ClassAParent  = ReflectionClass::factory(ClassAParent::class);
        $ClassAParentParent  = ReflectionClass::factory(ClassAParentParent::class);

        $ClassAParentProperties        = $ClassAParent->getProperties();
        $ClassAParentParentProperties  = $ClassAParentParent->getProperties();

        $propertyExists = false;
        foreach ($ClassAParentProperties as $index => $parentProperty) {
            if ($parentProperty->name == $publicProperty3->name) {
                $this->assertSame($parentProperty, $publicProperty3);
                $propertyExists = true;
            }
        }
        $this->assertTrue($propertyExists);

        $propertyExists = false;
        foreach ($ClassAParentParentProperties as $index => $parentParentProperty) {
            if ($parentParentProperty->name == $publicProperty3->name) {
                $this->assertSame($parentParentProperty, $publicProperty3);
                $propertyExists = true;
            }
        }
        $this->assertTrue($propertyExists);



        $publicMethod3     = ReflectionMethod::factory(ClassA::class, 'publicMethod3');
        $ClassAParent      = ReflectionClass::factory(ClassAParent::class);
        $ClassAParentParent  = ReflectionClass::factory(ClassAParentParent::class);

        $ClassAParentMethods       = $ClassAParent->getMethods();
        $ClassAParentParentMethods = $ClassAParentParent->getMethods();

        $methodExists = false;
        foreach ($ClassAParentMethods as $index => $parentMethod) {
            if ($parentMethod->name == $publicMethod3->name) {
                $this->assertSame($parentMethod, $publicMethod3);
                $methodExists = true;
            }
        }
        $this->assertTrue($methodExists);

        $methodExists = false;
        foreach ($ClassAParentParentMethods as $index => $parentParentMethod) {
            if ($parentParentMethod->name == $publicMethod3->name) {
                $this->assertSame($parentParentMethod, $publicMethod3);
                $methodExists = true;
            }
        }
        $this->assertTrue($methodExists);
    }

    public function testGetParentClass()
    {
        $adminPanelClassReflection = ReflectionClass::factory(ClassAParentParent::class);
        $adminPanelParentClassReflection = $adminPanelClassReflection->getParentClass();

        $classReflection = new \ReflectionClass(ClassAParentParent::class);
        $parentClassReflection = $classReflection->getParentClass();

        $this->assertSame($adminPanelParentClassReflection, $parentClassReflection);


        $adminPanelClassReflection1 = ReflectionClass::factory(ClassA::class);
        $adminPanelParentClassReflection1 = $adminPanelClassReflection1->getParentClass();

        $classReflection1 = new \ReflectionClass(ClassA::class);
        $parentClassReflection1 = $classReflection1->getParentClass();

        $this->assertSame($adminPanelParentClassReflection1->name, $parentClassReflection1->name);
    }

    protected function pc_array_power_set($array)
    {
        $results = [[ ]];
        foreach ($array as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge([$element], $combination));
            }
        }

        return $results;
    }
}
