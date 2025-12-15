<?php
namespace Psk\LmsModule\Forms\Requests\Tests;

use Psk\LmsModule\Models\Requests\Tests\TestRequestModel;
use Ox3a\Form\Model\FormModel;
use Ox3a\Form\Model;
use Zend\Filter;
use Zend\Validator;


class TestFormModel extends FormModel
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
                'validators' => [
                    new \Zend\Validator\Digits(),
                ],
                'type' => \Ox3a\Form\Model\SelectModel::class,
            ]
        );
        $this->add(
            [
                'name' => "title",
                'attributes' => [
                    'escapeAttr' => false,
                    'required' => true,
                    'placeholder' => "Введите название",
                ],
                'options' => [
                    'label' => "Название",
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
                'name' => "description",
                'attributes' => [
                    'escapeAttr' => false,
                    'placeholder' => "Введите описание",
                ],
                'options' => [
                    'label' => "Описание",
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
                'name' => "isDisplayAllQuestions",
                'options' => [
                    'continueIfEmpty' => true,
                    'label' => "Все вопросы на странице",
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
                'name' => "isVisibleResult",
                'options' => [
                    'continueIfEmpty' => true,
                    'label' => "Показывать ответы",
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
                'name' => "isRandomQuestion",
                'options' => [
                    'continueIfEmpty' => true,
                    'label' => "Перемешивать вопросы",
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
                'name' => "attemptCount",
                'options' => [
                    'label' => "Количество попыток",
                    'escapeAttr' => true,
                ],
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'validators' => [
                    new \Zend\Validator\Digits(),
                ],
                'type' => \Ox3a\Form\Model\ElementModel::class,
            ]
        );
        $this->add(
            [
                'name' => "questionCount",
                'options' => [
                    'label' => "Количество вопросов",
                    'escapeAttr' => true,
                ],
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'validators' => [
                    new \Zend\Validator\Digits(),
                ],
                'type' => \Ox3a\Form\Model\ElementModel::class,
            ]
        );
        $this->add(
            [
                'name' => "timeLimit",
                'options' => [
                    'label' => "Ограничение времени",
                    'escapeAttr' => true,
                ],
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'validators' => [
                    new \Zend\Validator\Digits(),
                ],
                'type' => \Ox3a\Form\Model\ElementModel::class,
            ]
        );
        $this->add(
            [
                'name' => "startAt",
                'attributes' => [
                    'required' => true,
                    'escapeAttr' => false,
                    'type' => "date",
                ],
                'options' => [
                    'label' => "Дата начала",
                    'escapeAttr' => true,
                ],
                'filters' => [
                    new \Zend\Filter\HtmlEntities(),
                    new \Zend\Filter\StringTrim(),
                    [
                        'name' => "Zend\\Filter\\StringTrim",
                    ],
                ],
                'type' => \Ox3a\Form\Model\ElementModel::class,
                'validators' => [
                    [
                        'name' => "Zend\\Validator\\Date",
                        'options' => [
                            'format' => "Y-m-d",
                        ],
                    ],
                ],
            ]
        );
        $this->add(
            [
                'name' => "endAt",
                'attributes' => [
                    'escapeAttr' => false,
                    'type' => "date",
                ],
                'options' => [
                    'label' => "Дата окончания",
                    'escapeAttr' => true,
                ],
                'filters' => [
                    new \Zend\Filter\HtmlEntities(),
                    new \Zend\Filter\StringTrim(),
                    [
                        'name' => "Zend\\Filter\\StringTrim",
                    ],
                ],
                'type' => \Ox3a\Form\Model\ElementModel::class,
                'validators' => [
                    [
                        'name' => "Zend\\Validator\\Date",
                        'options' => [
                            'format' => "Y-m-d",
                        ],
                    ],
                ],
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
     * @return TestRequestModel
     */
    public function getDataModel()
    {
        $model = new TestRequestModel();

        foreach ($this->getData() as $field => $value) {
            if (property_exists($model, $field)) {
                $model->$field = $value;
            }
        }

        return $model;
    }

}
