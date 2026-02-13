<?php
namespace Psk\LmsModule\Forms\Requests\Tests;

use Psk\LmsModule\Models\Requests\Tests\AddAttemptRequestModel;
use Ox3a\Form\Model\FormModel;
use Ox3a\Form\Model;
use Zend\Filter;
use Zend\Validator;


class AddAttemptFormModel extends FormModel
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
                'filters' => [
                    new \Zend\Filter\ToInt(),
                ],
                'validators' => [
                    new \Zend\Validator\Digits(),
                    (new \Zend\Validator\Db\RecordExists($this->getOption('testId')))
                        ->setAdapter($this->getOption('db')->getAdapter()),
                ],
                'type' => \Ox3a\Form\Model\HiddenModel::class,
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
     * @return AddAttemptRequestModel
     */
    public function getDataModel()
    {
        $model = new AddAttemptRequestModel();

        foreach ($this->getData() as $field => $value) {
            if (property_exists($model, $field)) {
                $model->$field = $value;
            }
        }

        return $model;
    }

}
