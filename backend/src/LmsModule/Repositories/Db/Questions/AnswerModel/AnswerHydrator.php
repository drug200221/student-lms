<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\Questions\AnswerModel;

use Psk\LmsModule\Models\Questions\AnswerModel;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Class AnswerHydrator
 * @package Psk\LmsModule\Repositories\Db\Questions\AnswerModel
 */
class AnswerHydrator
{
    /** @var array<non-empty-string, ReflectionProperty> */
    private static $propertyReflections = [];

    /** @var ReflectionClass<AnswerModel>|null */
    private static $classReflection;

    /**
     * Карта полей
     * @var array<non-empty-string, array{non-empty-string|null, non-empty-string}>
     */
    private $map = [
        'id' => ['id', 'int'],
        'questionId' => ['question_id', 'int'],
        'answer' => ['answer', 'string'],
        'isCorrect' => ['is_correct', 'bool'],
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
     * @return ReflectionClass<AnswerModel>
     */
    private static function getReflectionClass()
    {
        return self::$classReflection ?: self::$classReflection = new ReflectionClass(AnswerModel::class);
    }

    /**
     * Заполнить объект данными
     * @param AnswerModel $object
     * @param array<non-empty-string, mixed> $data
     * @return AnswerModel
     */
    public function hydrate(AnswerModel $object, array $data)
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
     * @param AnswerModel $object
     * @return array<non-empty-string, mixed>
     * @throws ReflectionException
     */
    public function extract(AnswerModel $object)
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
     * @param AnswerModel $object
     * @param non-empty-string $property
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    public function hydrateProperty(AnswerModel $object, $property, $value)
    {
        self::getReflectionProperty($property)->setValue($object, $value);
    }

    /**
     * Извлечь данные из свойства объекта
     * @param AnswerModel $object
     * @param non-empty-string $property
     * @return mixed
     * @throws ReflectionException
     */
    public function extractProperty(AnswerModel $object, $property)
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
}
