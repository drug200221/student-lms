<?php

namespace Psk\LmsModule\Forms\Dynamic\Fields;

use Ox3a\Form\Model\RadioGroupModel;
use Zend\Filter\Boolean;

final class BoolField
{
    public static function get(string $name = 'isCorrect', string $label = 'Верный'): array
    {
        return [
            'name' => $name,
            'options' => [
                'continueIfEmpty' => true,
                'label' => $label,
                'options' => [
                    1 => "Да",
                    0 => "Нет",
                ],
                'escapeAttr' => true,
            ],
            'filters' => [
                new Boolean(),
            ],
            'type' => RadioGroupModel::class,
        ];
    }
}
