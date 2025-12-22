<?php
namespace Psk\LmsModule\Forms\Requests\Questions\Types;

use Psk\LmsModule\Models\Requests\Questions\Types\TrueOrFalseRequestModel;
use Ox3a\Form\Model\FormModel;
use Ox3a\Form\Model;
use Zend\Filter;
use Zend\Validator;


class TrueOrFalseFormModel extends FormModel
{

    /**
     * @return void
     */
    public function init()
    {
        $this->setAttribute("action", "");

        $this->add(
            [
                'name' => "courseId",
                'attributes' => [
                    'required' => true,
                ],
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'validators' => [
                    new \Zend\Validator\Digits(),
                    new \Zend\Validator\Db\RecordExists([
                        'adapter' => $this->getOption('db')->getAdapter(),
                        'table' => 'lms_courses',
                        'field' => 'id'
                    ]),
                ],
                'type' => \Ox3a\Form\Model\HiddenModel::class,
            ]
        );
        $this->add(
            [
                'name' => "categoryId",
                'options' => [
                    'label' => "Категория",
                    'options' => $this->getOption('categories'),
                    'escapeAttr' => true,
                ],
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'type' => \Ox3a\Form\Model\SelectModel::class,
            ]
        );
        $this->add(
            [
                'name' => "question",
                'attributes' => [
                    'escapeAttr' => false,
                    'required' => true,
                    'placeholder' => "Введите вопрос",
                ],
                'options' => [
                    'label' => "Вопрос",
                    'escapeAttr' => false,
                ],
                'filters' => [
                    new \Zend\Filter\HtmlEntities(),
                    new \Zend\Filter\StringTrim(),
                ],
                'type' => \Ox3a\Form\Model\ElementModel::class,
            ]
        );
        $this->add(
            [
                'name' => "type",
                'attributes' => [
                    'required' => true,
                ],
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'validators' => [
                    new \Zend\Validator\Digits(),
                ],
                'type' => \Ox3a\Form\Model\HiddenModel::class,
            ]
        );
        $this->add(
            [
                'name' => "isCorrect",
                'options' => [
                    'continueIfEmpty' => true,
                    'label' => "Верный",
                    'options' => [
                        1 => "Да",
                        0 => "Нет",
                    ],
                    'escapeAttr' => true,
                ],
                'filters' => [
                    new \Zend\Filter\Boolean(),
                ],
                'type' => \Ox3a\Form\Model\RadioGroupModel::class,
            ]
        );
        $this->add(
            [
                'type' => \Ox3a\Form\Model\ButtonModel::class,
                'name' => "submit",
                'options' => [
                    'label' => "&nbsp;",
                    'title' => "Сохранить",
                ],
            ]
        );

    }

    /**
     * @return TrueOrFalseRequestModel
     */
    public function getDataModel()
    {
        $model = new TrueOrFalseRequestModel();

        foreach ($this->getData() as $field => $value) {
            if (property_exists($model, $field)) {
                $model->$field = $value;
            }
        }

        return $model;
    }

}
