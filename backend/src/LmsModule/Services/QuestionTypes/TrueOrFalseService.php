<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\QuestionTypes;

use Psk\LmsModule\Models\Questions\AnswerModel;
use Psk\LmsModule\Models\Questions\QuestionModel;
use Psk\LmsModule\Models\Requests\Questions\Types\TrueOrFalseRequestModel;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;

/**
 * @internal
 */
final class TrueOrFalseService extends AbstractQuestionTypesService
{
    /**
     * @param array<string,mixed> $data
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function create(array $data): AbstractResult
    {
        $form = $this->questionTypeFormFactories->initializeForm($data);

        if (!$form->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $question = new QuestionModel();
        $answers = [
            new AnswerModel(),
            new AnswerModel(),
        ];

        $this->updateByRequest($question, $answers, $form->getDataModel());

        return new SuccessResult($this->questionRepository->findById($question->getId()));
    }

    /**
     * @param QuestionModel $question
     * @param array<string,mixed> $data
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function update(QuestionModel $question, array $data): AbstractResult
    {
        $form = $this->questionTypeFormFactories->initializeForm($data);

        if (!$form->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $answers = $this->answerRepository->findByQuestionId($question->getId());

        $this->updateByRequest($question, $answers, $form->getDataModel());

        return new SuccessResult($this->questionRepository->findById($question->getId()));
    }

    /**
     * @param AnswerModel[] $answers
     * @param QuestionModel $question
     * @param TrueOrFalseRequestModel $request
     * @return void
     * @throws \ReflectionException
     */
    private function updateByRequest(
        QuestionModel           $question,
        array                   $answers,
        TrueOrFalseRequestModel $request
    ): void
    {
        $this->questionRepository->setAndSave($question, $request);

        $isCorrect = $request->isCorrect;

        $this->answerRepository->setAndSave($answers[0], $question->getId(), "Да",  $isCorrect);
        $this->answerRepository->setAndSave($answers[1], $question->getId(), "Нет", !$isCorrect);
    }
}
