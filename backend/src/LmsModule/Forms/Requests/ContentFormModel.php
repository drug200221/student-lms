<?php
namespace Psk\LmsModule\Forms\Requests;

use Psk\LmsModule\Models\Requests\ContentRequestModel;
use Ox3a\Form\Model\FormModel;
use Ox3a\Form\Model;
use Zend\Filter;
use Zend\Validator;


class ContentFormModel extends FormModel
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
                'name' => "parentId",
                'attributes' => [
                    'required' => true,
                ],
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'validators' => [
                    new \Zend\Validator\Digits(),
                    (new \Zend\Validator\Db\RecordExists($this->getOption('selectExists')))
                        ->setAdapter($this->getOption('db')->getAdapter()),
                ],
                'type' => \Ox3a\Form\Model\HiddenModel::class,
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
                'name' => "content",
                'attributes' => [
                    'escapeAttr' => false,
                ],
                'options' => [
                    'label' => "Контент",
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
     * @return ContentRequestModel
     */
    public function getDataModel()
    {
        $model = new ContentRequestModel();

        foreach ($this->getData() as $field => $value) {
            if (property_exists($model, $field)) {
                $model->$field = $value;
            }
        }

        return $model;
    }

}
