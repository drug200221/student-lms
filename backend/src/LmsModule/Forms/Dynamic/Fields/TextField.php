<?php

namespace Psk\LmsModule\Forms\Dynamic\Fields;

use Ox3a\Form\Model\ElementModel;
use Psk\LmsModule\Models\Questions\AnswerModel;
use Zend\Filter\Callback;
use Zend\Filter\HtmlEntities;
use Zend\Filter\StringTrim;
use Zend\Validator\NotEmpty;
use Zend\Validator\Regex;

final class TextField
{
    public static function get(
        string $name = 'text',
        string $label = 'Текст',
        bool $required = false,
        bool $continueIfEmpty = true
    ): array
    {
        return [
            'name' => $name,
            'options' => [
                'required' => $required,
                'continueIfEmpty' => $continueIfEmpty,
                'label' => $label,
                'escapeAttr' => false,
            ],
            'attributes' => [
                'escapeAttr' => false,
            ],
            'filters' => [
                new HtmlEntities(),
                new StringTrim(),
                new Callback(function($value) {
                    return $value ?? ''; // чтобы Regex не ругался  на null
                }),
            ],
            'validators' => [
                new NotEmpty(),
                (new Regex('/^(?!.*' . preg_quote(AnswerModel::SEPARATOR, '/') . ').*/s'))
                    ->setMessage('Запрещен ввод зарезервированного разделителя.', Regex::NOT_MATCH),
            ],
            'type' => ElementModel::class,
        ];
    }
}
