<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\Tests\ResultModel;

use Psk\LmsModule\Models\Tests\ResultModel;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Class ResultHydrator
 * @package Psk\LmsModule\Repositories\Db\Tests\ResultModel
 */
class ResultHydrator
{
    /** @var array<non-empty-string, ReflectionProperty> */
    private static $propertyReflections = [];

    /** @var ReflectionClass<ResultModel>|null */
    private static $classReflection;

    /**
     * Карта полей
     * @var array<non-empty-string, array{non-empty-string|null, non-empty-string}>
     */
    private $map = [
        'id' => ['id', 'int'],
        'attemptId' => ['attempt_id', 'int'],
        'questionId' => ['question_id', 'int'],
        'questionType' => ['type', 'int'],
        'question' => ['question', 'int'],
        'answerId' => ['answer_id', 'int'],
        'answerText' => ['answer_text', 'string'],
    ];

    /**
     * Список полей для исключения из записи
     * @var string[]
     */
    private $readonly = ['questionType', 'question'];

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
     * @return ReflectionClass<ResultModel>
     */
    private static function getReflectionClass()
    {
        return self::$classReflection ?: self::$classReflection = new ReflectionClass(ResultModel::class);
    }

    /**
     * Заполнить объект данными
     * @param ResultModel $object
     * @param array<non-empty-string, mixed> $data
     * @return ResultModel
     */
    public function hydrate(ResultModel $object, array $data)
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
     * @param ResultModel $object
     * @return array<non-empty-string, mixed>
     * @throws ReflectionException
     */
    public function extract(ResultModel $object)
    {
        $dbData = [];

        foreach ($this->map as $property => $settings) {
            if (in_array($property, $this->readonly) || is_null($settings[0])) {
                continue;
            }
            $field         = $settings[0];
            $extractMethod = "extract" . ucfirst($settings[1]);

            $dbData[$field] = $this->{$extractMethod}($this->extractProperty($object, $property));
        }

        return $dbData;
    }

    /**
     * Заполнить данными свойство объекта
     * @param ResultModel $object
     * @param non-empty-string $property
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    public function hydrateProperty(ResultModel $object, $property, $value)
    {
        self::getReflectionProperty($property)->setValue($object, $value);
    }

    /**
     * Извлечь данные из свойства объекта
     * @param ResultModel $object
     * @param non-empty-string $property
     * @return mixed
     * @throws ReflectionException
     */
    public function extractProperty(ResultModel $object, $property)
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
