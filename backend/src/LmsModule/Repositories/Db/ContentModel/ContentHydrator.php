<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\ContentModel;

use DateTimeImmutable;
use DateTimeInterface;
use Psk\LmsModule\Models\ContentModel;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Class ContentHydrator
 * @package Psk\LmsModule\Repositories\Db\ContentModel
 */
class ContentHydrator
{
    /** @var array<non-empty-string, ReflectionProperty> */
    private static $propertyReflections = [];

    /** @var ReflectionClass<ContentModel>|null */
    private static $classReflection;

    /**
     * Карта полей
     * @var array<non-empty-string, array{non-empty-string|null, non-empty-string}>
     */
    private $map = [
        'id' => ['id', 'int'],
        'courseId' => ['course_id', 'int'],
        'title' => ['title', 'string'],
        'content' => ['content', 'string'],
        'path' => ['path', 'string'],
        'parentId' => ['parent_id', 'int'],
        'createdAt' => ['created_at', 'DateTime'],
        'updatedAt' => ['updated_at', 'DateTime'],
        'revision' => ['revision', 'int'],
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
     * @return ReflectionClass<ContentModel>
     */
    private static function getReflectionClass()
    {
        return self::$classReflection ?: self::$classReflection = new ReflectionClass(ContentModel::class);
    }

    /**
     * Заполнить объект данными
     * @param ContentModel $object
     * @param array<non-empty-string, mixed> $data
     * @return ContentModel
     */
    public function hydrate(ContentModel $object, array $data)
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
     * @param ContentModel $object
     * @return array<non-empty-string, mixed>
     * @throws ReflectionException
     */
    public function extract(ContentModel $object)
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
     * @param ContentModel $object
     * @param non-empty-string $property
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    public function hydrateProperty(ContentModel $object, $property, $value)
    {
        self::getReflectionProperty($property)->setValue($object, $value);
    }

    /**
     * Извлечь данные из свойства объекта
     * @param ContentModel $object
     * @param non-empty-string $property
     * @return mixed
     * @throws ReflectionException
     */
    public function extractProperty(ContentModel $object, $property)
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

    /**
     * Создать объект даты со временем
     * @param string|null $value
     * @return DateTimeInterface|null
     */
    private function createDateTime($value)
    {
        return $value ? new DateTimeImmutable($value) : null;
    }

    /**
     * Извлечь дату со временем
     * @param DateTimeInterface|null $value
     * @return string|null
     */
    private function extractDateTime($value)
    {
        return $value ? $value->format("Y-m-d H:i:s") : null;
    }
}
