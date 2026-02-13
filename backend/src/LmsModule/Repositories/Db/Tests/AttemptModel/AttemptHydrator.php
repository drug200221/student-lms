<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\Tests\AttemptModel;

use DateTimeImmutable;
use DateTimeInterface;
use Psk\LmsModule\Models\Tests\AttemptModel;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Class AttemptHydrator
 * @package Psk\LmsModule\Repositories\Db\Tests\AttemptModel
 */
class AttemptHydrator
{
    /** @var array<non-empty-string, ReflectionProperty> */
    private static $propertyReflections = [];

    /** @var ReflectionClass<AttemptModel>|null */
    private static $classReflection;

    /**
     * Карта полей
     * @var array<non-empty-string, array{non-empty-string|null, non-empty-string}>
     */
    private $map = [
        'id' => ['id', 'int'],
        'userId' => ['user_id', 'int'],
        'firstName' => ['ima', 'string'],
        'lastName' => ['fam', 'string'],
        'middleName' => ['otc', 'string'],
        'testId' => ['test_id', 'int'],
        'startAt' => ['start_at', 'DateTime'],
        'endAt' => ['end_at', 'DateTime'],
        'isFinished' => ['is_finished', 'bool'],
        'grade' => ['grade', 'float'],
    ];

    /**
     * Список полей для исключения из записи
     * @var string[]
     */
    private $readonly = ['firstName', 'lastName', 'middleName'];

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
     * @return ReflectionClass<AttemptModel>
     */
    private static function getReflectionClass()
    {
        return self::$classReflection ?: self::$classReflection = new ReflectionClass(AttemptModel::class);
    }

    /**
     * Заполнить объект данными
     * @param AttemptModel $object
     * @param array<non-empty-string, mixed> $data
     * @return AttemptModel
     */
    public function hydrate(AttemptModel $object, array $data)
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
     * @param AttemptModel $object
     * @return array<non-empty-string, mixed>
     * @throws ReflectionException
     */
    public function extract(AttemptModel $object)
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
     * @param AttemptModel $object
     * @param non-empty-string $property
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    public function hydrateProperty(AttemptModel $object, $property, $value)
    {
        self::getReflectionProperty($property)->setValue($object, $value);
    }

    /**
     * Извлечь данные из свойства объекта
     * @param AttemptModel $object
     * @param non-empty-string $property
     * @return mixed
     * @throws ReflectionException
     */
    public function extractProperty(AttemptModel $object, $property)
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
     * Создать дробное значение
     * @param string|null $value
     * @return float|null
     */
    private function createFloat($value)
    {
        return is_null($value) ? null : ((float)$value);
    }

    /**
     * Извлечь дробное значение
     * @param float|null $value
     * @return float|null
     */
    private function extractFloat($value)
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
