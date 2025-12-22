<?php

namespace Psk\LmsModule\Forms\Dynamic\Fields;

use Ox3a\Form\Model\ElementModel;
use Ox3a\Form\Model\FormModel;
use Zend\Filter\ToInt;
use Zend\Validator\Digits;

final class IdField extends FormModel
{
    public function get(): array
    {
        return [
            'name' => 'id',
            'options' => [],
            'attributes' => [],
            'filters' => [
                new ToInt(),
            ],
            'validators' => [
                new Digits(),
            ],
            'type' => ElementModel::class,
        ];
    }
}
