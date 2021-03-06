<?php
/**
 * This class will test the ReflectionFactory class.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Reflection
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @link       http://marcelog.github.com/
 *
 * Copyright 2011 Marcelo Gornstein <marcelog@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

use Ding\Container\Impl\ContainerImpl;
use Ding\Reflection\ReflectionFactory;

/**
 * This class will test the ReflectionFactory class.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Reflection
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @link       http://marcelog.github.com/
 */
class Test_Reflection extends PHPUnit_Framework_TestCase
{
    private $_properties = array();

    public function setUp()
    {
        $this->_properties = array(
            'ding' => array(
                'log4php.properties' => RESOURCES_DIR . DIRECTORY_SEPARATOR . 'log4php.properties',
                'cache' => array(),
                'factory' => array(
                    'bdef' => array(
                        'xml' => array(
                        	'filename' => 'ioc-xml-simple.xml', 'directories' => array(RESOURCES_DIR)
                        )
                    )
                )
            )
        );
    }


    /**
     * @test
     */
    public function can_return_nothing_if_no_annotations_driver()
    {
        $container = ContainerImpl::getInstance($this->_properties);
        $reflectionFactory = $container->getBean('dingReflectionFactory');
        $result = $reflectionFactory->getClassAnnotations('Test_Reflection');
        $this->assertTrue(empty($result));
        $result = $reflectionFactory->getMethodAnnotations(__CLASS__, __METHOD__);
        $this->assertTrue(empty($result));
        $result = $reflectionFactory->getPropertyAnnotations(__CLASS__, '_properties');
        $this->assertTrue(empty($result));
        $result = $reflectionFactory->getClassesByAnnotation('link');
        $this->assertTrue(empty($result));
    }

    /**
     * @test
     */
    public function can_return_all_ancestors_and_interfaces()
    {
        $container = ContainerImpl::getInstance($this->_properties);
        $reflectionFactory = $container->getBean('dingReflectionFactory');
        $this->assertEquals(
            $reflectionFactory->getClassAncestorsAndInterfaces('ReflectionTestC'), array(
            	'ReflectionTestB', 'ReflectionTestA',
                'ReflectionInterfaceTestC', 'ReflectionInterfaceTestA',
                'ReflectionInterfaceTestB', 'ReflectionInterfaceTestD'
            )
        );
    }

    /**
     * @test
     */
    public function can_return_all_ancestors()
    {
        $container = ContainerImpl::getInstance($this->_properties);
        $reflectionFactory = $container->getBean('dingReflectionFactory');
        $this->assertEquals($reflectionFactory->getClassAncestors('ReflectionTestC'), array('ReflectionTestB', 'ReflectionTestA'));
    }
}

interface ReflectionInterfaceTestA
{

}
interface ReflectionInterfaceTestB extends ReflectionInterfaceTestA
{

}
interface ReflectionInterfaceTestC extends ReflectionInterfaceTestB
{

}
interface ReflectionInterfaceTestD
{

}
class ReflectionTestA implements ReflectionInterfaceTestC, ReflectionInterfaceTestD
{
}

class ReflectionTestB extends ReflectionTestA
{

}

class ReflectionTestC extends ReflectionTestB
{

}