<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\ContentNodeModel;

use Psk\LmsModule\Models\ContentNodeModel;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Class ContentNodeHydrator
 * @package Psk\LmsModule\Repositories\Db\ContentNodeModel
 */
class ContentNodeHydrator
{
    /** @var array<non-empty-string, ReflectionProperty> */
    private static $propertyReflections = [];

    /** @var ReflectionClass<ContentNodeModel>|null */
    private static $classReflection;

    /**
     * Карта полей
     * @var array<non-empty-string, array{non-empty-string|null, non-empty-string}>
     */
    private $map = [
        'id' => ['id', 'int'],
        'courseId' => ['course_id', 'int'],
        'title' => ['title', 'string'],
        'parentId' => ['parent_id', 'int'],
        'type' => ['type', 'int'],
        'treeLevel' => ['tree_level', 'int'],
        'treeLeft' => ['tree_left', 'int'],
        'treeRight' => ['tree_right', 'int'],
        'treeOrder' => ['tree_order', 'int'],
    ];

    /**
     * @param non-empty-string $property
     * @return ReflectionProperty
     * @throws ReflectionException
     */
    private static function getReflectionProperty($property)
    {
        if (!isset(self::$propertyReflections[$property])) {
            $reflectionClass = self::getReflectionClass();

            $reflectionProperty = $reflectionClass->getProperty($property);
            $reflectionProperty->setAccessible(true);

            self::$propertyReflections[$property] = $reflectionProperty;
        }

        return self::$propertyReflections[$property];
    }

    /**
     * @return ReflectionClass<ContentNodeModel>
     */
    private static function getReflectionClass()
    {
        return self::$classReflection ?: self::$classReflection = new ReflectionClass(ContentNodeModel::class);
    }

    /**
     * Заполнить объект данными
     * @param ContentNodeModel $object
     * @param array<non-empty-string, mixed> $data
     * @return ContentNodeModel
     */
    public function hydrate(ContentNodeModel $object, array $data)
    {
        foreach ($this->map as $property => $settings) {
            if (array_key_exists($property, $data)) {
                $value         = $data[$property];
                $hydrateMethod = "create" . ucfirst($settings[1]);

                $this->hydrateProperty($object, $property, $this->{$hydrateMethod}($value));
            }
        }

        return $object;
    }

    /**
     * Заполнить данными свойство объекта
     * @param ContentNodeModel $object
     * @param non-empty-string $property
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    public function hydrateProperty(ContentNodeModel $object, $property, $value)
    {
        self::getReflectionProperty($property)->setValue($object, $value);
    }

    /**
     * Создать строковое значение
     * @param string|null $value
     * @return string|null
     */
    private function createString($value)
    {
        return is_null($value) ? null : ((string)$value);
    }

    /**
     * Создать целочисленное значение
     * @param string|null $value
     * @return int|null
     */
    private function createInt($value)
    {
        return is_null($value) ? null : ((int)$value);
    }
}
