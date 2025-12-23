<?php

declare(strict_types=1);

namespace Psk\LmsModule\Forms\Dynamic\QuestionTypes;

use Ox3a\Form\Factory\ElementFactory;
use Ox3a\Form\Model\ButtonModel;
use Ox3a\Form\Model\CollectionModel;
use Ox3a\Form\Model\ElementModel;
use Ox3a\Form\Model\FormModel;
use Psk\LmsModule\Forms\Dynamic\Fields\TextField;
use Psk\LmsModule\Models\Requests\Questions\Types\MultipleChoiceRequestModel;
use Zend\Filter\ToInt;
use Zend\Validator\Callback;
use Zend\Validator\Digits;
use Zend\Validator\IsCountable;

final class MultipleChoiceFormModel extends FormModel
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setAttribute("action", "");

        BaseFields::add($this);

        $this->add(
            [
                'name' => "correct",
                'attributes' => [
                    'required' => true,
                ],
                'options' => [
                    'label' => "Верный",
                    'escapeAttr' => true,
                ],
                'filters' => [
                    new ToInt(),
                ],
                'validators' => [
                    new Digits(),
                    (new Callback())->setCallback(function ($value, $context) {
                        $answers = $context['answers'] ?? [];

                        if (!isset($answers[$value])) {
                            return false;
                        }

                        $target = $answers[$value];

                        $text = '';
                        if (is_array($target)) {
                            $text = $target['text'] ?? '';
                        } elseif (is_string($target)) {
                            $text = $target;
                        }

                        return trim((string)$text) !== '';
                    }),
                ],
                'type' => ElementModel::class,
            ]
        );

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

        $collection->add(TextField::get());

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
     * @return MultipleChoiceRequestModel
     */
    public function getDataModel(): MultipleChoiceRequestModel
    {
        $model = new MultipleChoiceRequestModel();

        foreach ($this->getData() as $field => $value) {
            if (property_exists($model, $field)) {
                $model->$field = $value;
            }
        }

        return $model;
    }
}
