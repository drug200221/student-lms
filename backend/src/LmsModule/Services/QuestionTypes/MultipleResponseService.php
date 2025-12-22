<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\QuestionTypes;

use Psk\LmsModule\Models\Questions\QuestionModel;
use Psk\LmsModule\Models\Requests\Questions\Types\MultiAnswerRequestModel;
use Psk\LmsModule\Repositories\Db\Questions\QuestionModel\QuestionHydrator;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;

/**
 * @internal
 */
final class MultipleResponseService extends AbstractQuestionTypesService
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

        return new SuccessResult($question);
    }

    /**
     * @param QuestionModel $question
     * @param array $data
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

        return new SuccessResult($question);
    }

    /**
     * @param QuestionModel $question
     * @param MultiAnswerRequestModel $request
     * @return void
     * @throws \ReflectionException
     */
    private function updateByRequest(
        QuestionModel              $question,
        MultiAnswerRequestModel $request
    ): void
    {
        $this->questionRepository->setAndSave($question, $request);

        $answers = $this->questionRepository->balanceAnswers($question, count($request->answers));

        foreach ($answers as $number => $answer) {
            $this->answerRepository->setAndSave(
                $answer,
                $question->getId(),
                $request->answers[$number]['text'],
                $request->answers[$number]['isCorrect']);
        }

        $hydrator = new QuestionHydrator();
        $hydrator->hydrateProperty($question, 'answers', $answers);
    }
}
