<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\Tests\TestModel;

use DateTimeImmutable;
use DateTimeInterface;
use Psk\LmsModule\Models\Tests\TestModel;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Class TestHydrator
 * @package Psk\LmsModule\Repositories\Db\Tests\TestModel
 */
class TestHydrator
{
    /** @var array<non-empty-string, ReflectionProperty> */
    private static $propertyReflections = [];

    /** @var ReflectionClass<TestModel>|null */
    private static $classReflection;

    /**
     * Карта полей
     * @var array<non-empty-string, array{non-empty-string|null, non-empty-string}>
     */
    private $map = [
        'id' => ['id', 'int'],
        'courseId' => ['course_id', 'int'],
        'categoryId' => ['category_id', 'int'],
        'title' => ['title', 'string'],
        'description' => ['description', 'string'],
        'isDisplayAllQuestions' => ['is_display_all_questions', 'bool'],
        'isVisibleResult' => ['is_visible_result', 'bool'],
        'isRandomQuestion' => ['is_random_questions', 'bool'],
        'attemptCount' => ['attempt_count', 'int'],
        'questionCount' => ['question_count', 'int'],
        'timeLimit' => ['time_limit', 'int'],
        'startAt' => ['start_at', 'DateTime'],
        'endAt' => ['end_at', 'DateTime'],
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
     * @return ReflectionClass<TestModel>
     */
    private static function getReflectionClass()
    {
        return self::$classReflection ?: self::$classReflection = new ReflectionClass(TestModel::class);
    }

    /**
     * Заполнить объект данными
     * @param TestModel $object
     * @param array<non-empty-string, mixed> $data
     * @return TestModel
     */
    public function hydrate(TestModel $object, array $data)
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
     * @param TestModel $object
     * @return array<non-empty-string, mixed>
     * @throws ReflectionException
     */
    public function extract(TestModel $object)
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
     * @param TestModel $object
     * @param non-empty-string $property
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    public function hydrateProperty(TestModel $object, $property, $value)
    {
        self::getReflectionProperty($property)->setValue($object, $value);
    }

    /**
     * Извлечь данные из свойства объекта
     * @param TestModel $object
     * @param non-empty-string $property
     * @return mixed
     * @throws ReflectionException
     */
    public function extractProperty(TestModel $object, $property)
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
     * Создать логическое значение
     * @param string|null $value
     * @return bool|null
     */
    private function createBool($value)
    {
        return is_null($value) ? null : ((bool)$value);
    }

    /**
     * Извлечь логическое значение
     * @param bool|null $value
     * @return int|null
     */
    private function extractBool($value)
    {
        return is_null($value) ? null : ((int)(bool)$value);
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
