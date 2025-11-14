<?php
namespace Psk\LmsModule\Forms\Requests\Course;

use Ox3a\Form\Model\FormModel;
use Psk\LmsModule\Models\Requests\Course\AddCourseRequestModel;


class AddCourseFormModel extends FormModel
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
                    'placeholder' => "Введите название",
                    'required' => true,
                ],
                'options' => [
                    'label' => "Название",
                    'escapeAttr' => false,
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
     * @return AddCourseRequestModel
     */
    public function getDataModel()
    {
        $model = new AddCourseRequestModel();

        foreach ($this->getData() as $field => $value) {
            if (property_exists($model, $field)) {
                $model->$field = $value;
            }
        }

        return $model;
    }

}
