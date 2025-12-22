<?php
namespace Psk\LmsModule\Forms\Requests\Tests;

use Psk\LmsModule\Models\Requests\Tests\QuestionOfTestRequestModel;
use Ox3a\Form\Model\FormModel;
use Ox3a\Form\Model;
use Zend\Filter;
use Zend\Validator;


class QuestionOfTestFormModel extends FormModel
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
                ],
                'type' => \Ox3a\Form\Model\HiddenModel::class,
            ]
        );
        $this->add(
            [
                'name' => "testId",
                'attributes' => [
                    'required' => true,
                ],
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'validators' => [
                    (new \Zend\Validator\Db\RecordExists($this->getOption('testId')))
                        ->setAdapter($this->getOption('db')->getAdapter()),
                    new \Zend\Validator\Digits(),
                ],
                'type' => \Ox3a\Form\Model\HiddenModel::class,
            ]
        );
        $this->add(
            [
                'name' => "questionId",
                'attributes' => [
                    'required' => true,
                ],
                'options' => [
                    'label' => "Вопрос",
                    'options' => $this->getOption('questions'),
                    'escapeAttr' => true,
                ],
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'validators' => [
                    new \Zend\Validator\Digits(),
                    (new \Zend\Validator\Db\RecordExists($this->getOption('questionId')))
                        ->setAdapter($this->getOption('db')->getAdapter()),
                    (new \Zend\Validator\Db\NoRecordExists($this->getOption('questionOfTestIds')))
                        ->setAdapter($this->getOption('db')->getAdapter()),
                ],
                'type' => \Ox3a\Form\Model\SelectModel::class,
            ]
        );
        $this->add(
            [
                'name' => "point",
                'attributes' => [
                    'required' => true,
                ],
                'options' => [
                    'label' => "Баллов",
                    'escapeAttr' => true,
                ],
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'validators' => [
                    new \Zend\Validator\LessThan(["max" => 10, "inclusive" => true]),
                    new \Zend\Validator\GreaterThan(["min" => 1, "inclusive" => true]),
                    new \Zend\Validator\Digits(),
                ],
                'type' => \Ox3a\Form\Model\ElementModel::class,
            ]
        );
        $this->add(
            [
                'name' => "isRequired",
                'options' => [
                    'continueIfEmpty' => true,
                    'label' => "Обязателен ответ",
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
     * @return QuestionOfTestRequestModel
     */
    public function getDataModel()
    {
        $model = new QuestionOfTestRequestModel();

        foreach ($this->getData() as $field => $value) {
            if (property_exists($model, $field)) {
                $model->$field = $value;
            }
        }

        return $model;
    }

}
