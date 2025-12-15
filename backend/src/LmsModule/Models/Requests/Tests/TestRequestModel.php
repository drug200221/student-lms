<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Requests\Tests;

use Ox3a\Annotation\Form;
use Psk\MessengerModule\Annotations\RecordExistsValidator;

/**
 * @internal
 * @Form\Attribute(name="action", value="")
 */
class TestRequestModel
{
    /**
     * @Form\Element("hidden")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Validator(@RecordExistsValidator(table="lms_courses", field="id"))
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $courseId;

    /**
     * @Form\Element("select", label="Категория")
     * @Form\Attribute("categories", name="options")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $categoryId;

    /**
     * @Form\Element(label="Название", placeholder="Введите название")
     * @Form\Attribute(false, name="escapeAttr")
     * @Form\Attribute(true, name="required")
     * @Form\Filter(@Form\Filter\HtmlEntitiesFilter())
     * @Form\Filter(@Form\Filter\TrimFilter())
     * @var non-empty-string
     */
    public $title;

    /**
     * @Form\Element(label="Описание", placeholder="Введите описание")
     * @Form\Attribute(false, name="escapeAttr")
     * @Form\Filter(@Form\Filter\HtmlEntitiesFilter())
     * @Form\Filter(@Form\Filter\TrimFilter())
     * @var non-empty-string|null
     */
    public $description;

    /**
     * @Form\Element(type="checkbox", label="Все вопросы на странице")
     * @Form\Option(true, name="continueIfEmpty")
     * @Form\Filter(@Form\Filter\ToBoolFilter())
     * @var bool
     */
    public $isDisplayAllQuestions;

    /**
     * @Form\Element(type="checkbox", label="Показывать ответы")
     * @Form\Option(true, name="continueIfEmpty")
     * @Form\Filter(@Form\Filter\ToBoolFilter())
     * @var bool
     */
    public $isVisibleResult;

    /**
     * @Form\Element(type="checkbox", label="Перемешивать вопросы")
     * @Form\Option(true, name="continueIfEmpty")
     * @Form\Filter(@Form\Filter\ToBoolFilter())
     * @var bool
     */
    public $isRandomQuestion;

    /**
     * @Form\Element(label="Количество попыток")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var non-negative-int
     */
    public $attemptCount;

    /**
     * @Form\Element(label="Количество вопросов")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $questionCount;

    /**
     * @Form\Element(label="Ограничение времени")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var non-negative-int
     */
    public $timeLimit;

    /**
     * @Form\Element(label="Дата начала", type="date")
     * @Form\Attribute(true, name="required")
     * @Form\Attribute(false, name="escapeAttr")
     * @Form\Filter(@Form\Filter\HtmlEntitiesFilter())
     * @Form\Filter(@Form\Filter\TrimFilter())
     * @var string
     */
    public $startAt;

    /**
     * @Form\Element(label="Дата окончания", type="date")
     * @Form\Attribute(false, name="escapeAttr")
     * @Form\Filter(@Form\Filter\HtmlEntitiesFilter())
     * @Form\Filter(@Form\Filter\TrimFilter())
     * @var string
     */
    public $endAt;
}
