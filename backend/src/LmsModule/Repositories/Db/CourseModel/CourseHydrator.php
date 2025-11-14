<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\CourseModel;

use Psk\LmsModule\Models\CourseModel;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Class CourseHydrator
 * @package Psk\LmsModule\Repositories\Db\CourseModel
 */
class CourseHydrator
{
    /** @var array<non-empty-string, ReflectionProperty> */
    private static $propertyReflections = [];

    /** @var ReflectionClass<CourseModel>|null */
    private static $classReflection;

    /**
     * Карта полей
     * @var array<non-empty-string, array{non-empty-string|null, non-empty-string}>
     */
    private $map = [
        'id' => ['id', 'int'],
        'title' => ['title', 'string'],
        'description' => ['description', 'string'],
        'baseId' => ['base_id', 'int'],
        'type' => ['type', 'int'],
        'fillProgress' => ['fill_progress', 'int'],
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
     * @return ReflectionClass<CourseModel>
     */
    private static function getReflectionClass()
    {
        return self::$classReflection ?: self::$classReflection = new ReflectionClass(CourseModel::class);
    }

    /**
     * Заполнить объект данными
     * @param CourseModel $object
     * @param array<non-empty-string, mixed> $data
     * @return CourseModel
     */
    public function hydrate(CourseModel $object, array $data)
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
     * Извлечь данные из объекта
     * @param CourseModel $object
     * @return array<non-empty-string, mixed>
     * @throws ReflectionException
     */
    public function extract(CourseModel $object)
    {
        $dbData = [];

        foreach ($this->map as $property => $settings) {
            $field         = $settings[0];
            $extractMethod = "extract" . ucfirst($settings[1]);

            $dbData[$field] = $this->{$extractMethod}($this->extractProperty($object, $property));
        }

        return $dbData;
    }

    /**
     * Заполнить данными свойство объекта
     * @param CourseModel $object
     * @param non-empty-string $property
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    public function hydrateProperty(CourseModel $object, $property, $value)
    {
        self::getReflectionProperty($property)->setValue($object, $value);
    }

    /**
     * Извлечь данные из свойства объекта
     * @param CourseModel $object
     * @param non-empty-string $property
     * @return mixed
     * @throws ReflectionException
     */
    public function extractProperty(CourseModel $object, $property)
    {
        return self::getReflectionProperty($property)->getValue($object);
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
     * Извлечь строковое значение
     * @param string|null $value
     * @return string|null
     */
    private function extractString($value)
    {
        return $value;
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

    /**
     * Извлечь целочисленное значение
     * @param int|null $value
     * @return int|null
     */
    private function extractInt($value)
    {
        return $value;
    }
}
