<?php

namespace Psk\LmsModule\Forms\Dynamic;

use Ox3a\Form\Factory\ElementFactory;
use Ox3a\Form\Model\ButtonModel;
use Ox3a\Form\Model\CollectionModel;
use Ox3a\Form\Model\FormModel;

final class TestForm extends FormModel
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setAttribute("action", "");

        $collection = ElementFactory::factory([
            'type' => CollectionModel::class,
            'name' => 'questions',
        ]);

        $this->add($collection);

        $this->add(
            [
                'type' => ButtonModel::class,
                'name' => "submit",
                'options' => [
                    'label' => "&nbsp;",
                    'title' => "Сохранить",
                ],
            ]
        );
    }
}
