<?php
/**
 * This driver will search for @Required setter methods.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Bean
 * @subpackage Factory.Driver
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://www.noneyet.ar/ Apache License 2.0
 * @version    SVN: $Id$
 * @link       http://www.noneyet.ar/
 */
namespace Ding\Bean\Factory\Driver;

use Ding\Bean\Factory\Exception\BeanFactoryException;

use Ding\Bean\BeanPropertyDefinition;
use Ding\Bean\Lifecycle\IAfterDefinitionListener;
use Ding\Bean\BeanDefinition;
use Ding\Bean\BeanAnnotationDefinition;
use Ding\Bean\Factory\IBeanFactory;
use Ding\Reflection\ReflectionFactory;

/**
 * This driver will search for @Required setter methods.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Bean
 * @subpackage Factory.Driver
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://www.noneyet.ar/ Apache License 2.0
 * @link       http://www.noneyet.ar/
 */
class AnnotationRequiredDriver implements IAfterDefinitionListener
{
    /**
     * Cache property setters names.
     * @var array[]
     */
    private $_propertiesNameCache;

    /**
     * Holds current instance.
     * @var AnnotationRequiredDriver
     */
    private static $_instance = false;

    public function afterDefinition(IBeanFactory $factory, BeanDefinition &$bean)
    {
        $annotations = ReflectionFactory::getClassAnnotations($bean->getClass());
        foreach ($annotations as $method => $annotations) {
            if ($method == 'class') {
                continue;
            }
            if (strpos($method, 'set') !== 0) {
                continue;
            }
            $propName = lcfirst(substr($method, 3));
            foreach ($annotations as $annotation) {
                if ($annotation->getName() == 'Required') {
                    $props = $bean->getProperties();
                    if (!isset($props[$propName])) {
                        throw new BeanFactoryException('Missing @Required property: ' . $method);
                    }
                }
            }
        }
        return $bean;
    }

    /**
     * Returns an instance.
     *
     * @param array $options Optional options.
     *
     * @return AnnotationRequiredDriver
     */
    public static function getInstance(array $options)
    {
        if (self::$_instance == false) {
            self::$_instance = new AnnotationRequiredDriver;
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     *
     * @return void
     */
    private function __construct()
    {
        $this->_propertiesNameCache = array();
    }
}