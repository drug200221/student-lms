<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\QuestionTypes;

use Psk\LmsModule\Forms\Factories\QuestionTypeFormFactories;
use Psk\LmsModule\Models\Questions\QuestionModel;
use Psk\LmsModule\Repositories\Questions\AnswerRepository;
use Psk\LmsModule\Repositories\Questions\QuestionCategoryRepository;
use Psk\LmsModule\Repositories\Questions\QuestionRepository;
use Psk\RestModule\Results\AbstractResult;

/**
 * @internal
 */
abstract class AbstractQuestionTypesService
{
    /** @var QuestionRepository */
    public $questionRepository;

    /** @var QuestionCategoryRepository */
    public $questionCategoryRepository;

    /** @var AnswerRepository */
    public $answerRepository;

    /** @var QuestionTypeFormFactories */
    public $questionTypeFormFactories;

    public function __construct(
        QuestionRepository         $questionRepository,
        QuestionCategoryRepository $questionCategoryRepository,
        AnswerRepository           $answerRepository,
        QuestionTypeFormFactories  $questionTypeFormFactories
    )
    {
        $this->questionRepository         = $questionRepository;
        $this->questionCategoryRepository = $questionCategoryRepository;
        $this->answerRepository           = $answerRepository;
        $this->questionTypeFormFactories  = $questionTypeFormFactories;
    }

    /**
     * @param array<string,mixed> $data
     * @return AbstractResult
     */
    abstract public function create(array $data): AbstractResult;

    /**
     * @param QuestionModel $question
     * @param array<string,mixed> $data
     * @return AbstractResult
     */
    abstract public function update(QuestionModel $question, array $data): AbstractResult;
}
