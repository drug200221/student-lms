<?php

use Ox3a\Form\Model\ElementModel;
use Ox3a\Form\Model\FormModel;
use Ox3a\Form\Model\HiddenModel;
use Ox3a\Form\Model\SelectModel;
use Zend\Filter\HtmlEntities;
use Zend\Filter\StringTrim;
use Zend\Filter\ToInt;
use Zend\Validator\Db\RecordExists;
use Zend\Validator\Digits;

return static function(FormModel $form): array {
    return [
        [
            'name' => "courseId",
            'attributes' => [
                'required' => true,
            ],
            'filters' => [
                new ToInt(),
            ],
            'validators' => [
                new Digits(),
                new RecordExists([
                    'adapter' => $form->getOption('db')->getAdapter(),
                    'table' => 'lms_courses',
                    'field' => 'id'
                ]),
            ],
            'type' => HiddenModel::class,
        ],
        [
            'name' => "categoryId",
            'options' => [
                'label' => "Категория",
                'options' => $form->getOption('categories'),
                'escapeAttr' => true,
            ],
            'filters' => [
                new ToInt(),
            ],
            'type' => SelectModel::class,
        ],
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
                new HtmlEntities(),
                new StringTrim(),
            ],
            'type' => ElementModel::class,
        ],
        [
            'name' => "type",
            'attributes' => [
                'required' => true,
            ],
            'filters' => [
                new ToInt(),
            ],
            'validators' => [
                new Digits(),
            ],
            'type' => HiddenModel::class,
        ]
    ];
};

