<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\QuestionTypes;

use Psk\LmsModule\Models\Questions\AnswerModel;
use Psk\LmsModule\Models\Questions\QuestionModel;
use Psk\LmsModule\Models\Requests\Questions\Types\MultiAnswerRequestModel;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;

/**
 * @internal
 */
final class OrderingService extends AbstractQuestionTypesService
{
    /**
     * @param array<string,mixed> $data
     * @return SuccessResult|ValidationErrorsResult
     * @throws \ReflectionException
     */
    public function create(array $data): AbstractResult
    {
        $form = $this->questionTypeFormFactories->initializeForm($data);

        if (!$form->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $question = new QuestionModel();

        $this->updateByRequest($question, $form->getDataModel());

        return new SuccessResult($this->questionRepository->findById($question->getId()));
    }

    /**
     * @param QuestionModel $question
     * @param array<string,mixed> $data
     * @return NotFoundResult|SuccessResult|ValidationErrorsResult
     * @throws \ReflectionException
     */
    public function update(QuestionModel $question, array $data): AbstractResult
    {
        $form = $this->questionTypeFormFactories->initializeForm($data);

        if (!$form->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $this->updateByRequest($question, $form->getDataModel());

        return new SuccessResult($this->questionRepository->findById($question->getId()));
    }

    /**
     * @param QuestionModel $question
     * @param MultiAnswerRequestModel $request
     * @return void
     * @throws \ReflectionException
     */
    private function updateByRequest(QuestionModel $question, MultiAnswerRequestModel $request): void
    {
        $this->questionRepository->setAndSave($question, $request);

        $answer = $this->questionRepository->balanceAnswers($question, 1)[0];

        $concat = implode(AnswerModel::SEPARATOR, array_column($request->answers, 'position'));

        $this->answerRepository->setAndSave($answer, $question->getId(), $concat, true);
    }
}
