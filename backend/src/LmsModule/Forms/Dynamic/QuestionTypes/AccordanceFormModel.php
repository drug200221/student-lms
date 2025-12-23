<?php

declare(strict_types=1);

namespace Psk\LmsModule\Forms\Dynamic\QuestionTypes;

use Ox3a\Form\Factory\ElementFactory;
use Ox3a\Form\Model\ButtonModel;
use Ox3a\Form\Model\CollectionModel;
use Ox3a\Form\Model\FormModel;
use Psk\LmsModule\Forms\Dynamic\Fields\TextField;
use Psk\LmsModule\Models\Requests\Questions\Types\MultiAnswerRequestModel;
use Zend\Validator\IsCountable;

final class AccordanceFormModel extends FormModel
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setAttribute("action", "");

        BaseFields::add($this);

        $collection = ElementFactory::factory([
            'type' => CollectionModel::class,
            'name' => 'answers',
            'validators' => [
                new IsCountable([
                    'min' => 2,
                    'max' => 10
                ]),
            ]
        ]);

        $collection->add(TextField::get('left', 'Левая часть'));
        $collection->add(TextField::get('right', 'Правая часть'));

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

    /**
     * @return MultiAnswerRequestModel
     */
    public function getDataModel(): MultiAnswerRequestModel
    {
        $model = new MultiAnswerRequestModel();

        foreach ($this->getData() as $field => $value) {
            if (property_exists($model, $field)) {
                $model->$field = $value;
            }
        }

        return $model;
    }
}
