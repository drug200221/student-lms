<?php

namespace Psk\MessengerModule\Annotations;

use Ox3a\Annotation\Form\ValidatorBuilderInterface;
use Zend\Validator\Db\RecordExists;

/**
 * Аннотация для генерации валидатора RecordExists в формах.
 * Проверяет, существует ли запись в базе данных.
 *
 * Основана на документации Zend\Validator\Db\RecordExists:
 * @link https://docs.zendframework.com/zend-validator/validators/db
 *
 * @Annotation
 */
class RecordExistsValidator implements ValidatorBuilderInterface
{
    /**
     * @var string Схема базы данных (опционально).
     */
    public $schema;

    /**
     * @var string Имя таблицы (обязательно, если select не передан через опции формы).
     */
    public $table;

    /**
     * @var string Имя поля для проверки (обязательно, если select не передан через опции формы).
     */
    public $field;

    /**
     * @var mixed|string|array Значение(я) для исключения из проверки (опционально).
     *                        Может быть строкой, массивом или вызываемым.
     */
    public $exclude;

    /**
     * @var string
     */
    private $adapter = 'db';

    /**
     * @var string
     */
    private $select = 'selectExists';

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Строит строку PHP для создания экземпляра валидатора RecordExists.
     * Поддерживает как режим таблица/поле/схема, так и пользовательский режим SELECT (переданный через опции формы).
     * Если таблица/поле установлены в аннотации, игнорирует select из опций формы.
     *
     * @return string
     * @throws \Exception
     */
    public function __toString(): string
    {
        $options = [];

        $options['adapter'] = sprintf("\$this->getOption('%s')->getAdapter()", $this->adapter);

        if (isset($this->exclude)) {
            if (is_string($this->exclude)) {
                $options['exclude'] = "'$this->exclude'";
            } elseif (is_array($this->exclude)) {
                $parts = [];
                foreach ($this->exclude as $item) {
                    if (is_string($item)) {
                        $parts[] = "'$item'";
                    } else {
                        $parts[] = $item;
                    }
                }
                $options['exclude'] = '[' . implode(', ', $parts) . ']';
            } else {
                $options['exclude'] = (string) $this->exclude;
            }
        }

        if (!empty($this->table) && !empty($this->field)) {
            $options['table'] = sprintf("'%s'", $this->table);
            $options['field'] = sprintf("'%s'", $this->field);
            if (!empty($this->schema)) {
                $options['schema'] = sprintf("'%s'", $this->schema);
            }

            $optionsStr = implode(",\n", array_map(
                static function ($key, $value) {
                    return sprintf("                        '%s' => %s", $key, $value);
                },
                array_keys($options),
                $options
            ));

            return sprintf(
                "new \%s([\n%s\n                    ])",
                RecordExists::class,
                $optionsStr
            );
        }

        return sprintf(
            "(new \%s(\$this->getOption('%s')))\n                        ->setAdapter(\$this->getOption('%s')->getAdapter())",
            RecordExists::class,
            $this->select,
            $this->adapter
        );
    }
}
