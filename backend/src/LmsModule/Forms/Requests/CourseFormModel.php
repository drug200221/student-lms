<?php
namespace Psk\LmsModule\Forms\Requests;

use Psk\LmsModule\Models\Requests\CourseRequestModel;
use Ox3a\Form\Model\FormModel;
use Ox3a\Form\Model;
use Zend\Filter;
use Zend\Validator;


class CourseFormModel extends FormModel
{

    /**
     * @return void
     */
    public function init()
    {
        $this->setAttribute("action", "");

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
                'name' => "baseId",
                'attributes' => [
                    'required' => true,
                ],
                'options' => [
                    'label' => "База",
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
                'name' => "type",
                'attributes' => [
                    'required' => true,
                ],
                'options' => [
                    'label' => "Тип",
                    'escapeAttr' => true,
                ],
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'validators' => [
                    new \Zend\Validator\GreaterThan(["min" => 1, "inclusive" => true]),
                    new \Zend\Validator\LessThan(["max" => 3, "inclusive" => true]),
                    new \Zend\Validator\Digits(),
                ],
                'type' => \Ox3a\Form\Model\ElementModel::class,
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
     * @return CourseRequestModel
     */
    public function getDataModel()
    {
        $model = new CourseRequestModel();

        foreach ($this->getData() as $field => $value) {
            if (property_exists($model, $field)) {
                $model->$field = $value;
            }
        }

        return $model;
    }

}
