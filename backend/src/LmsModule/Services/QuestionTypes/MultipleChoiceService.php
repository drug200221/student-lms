<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\QuestionTypes;

use Psk\LmsModule\Models\Questions\QuestionModel;
use Psk\LmsModule\Models\Requests\Questions\Types\MultipleChoiceRequestModel;
use Psk\LmsModule\Repositories\Db\Questions\QuestionModel\QuestionHydrator;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;

/**
 * @internal
 */
final class MultipleChoiceService extends AbstractQuestionTypesService
{
    /**
     * @param array<string,mixed> $data
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function create($data): AbstractResult
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
     * @param array<string,mixed> $data
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function update(QuestionModel $question, $data): AbstractResult
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
     * @param MultipleChoiceRequestModel $request
     * @return void
     * @throws \ReflectionException
     */
    private function updateByRequest(
        QuestionModel              $question,
        MultipleChoiceRequestModel $request
    ): void
    {
        $this->questionRepository->setAndSave($question, $request);

        $answers = $this->questionRepository->balanceAnswers($question, count($request->answers));

        foreach ($answers as $number => $answer) {
            $this->answerRepository->setAndSave(
                $answer,
                $question->getId(),
                $request->answers[$number]['text'],
                $request->correct === $number);
        }

        $hydrator = new QuestionHydrator();
        $hydrator->hydrateProperty($question, 'answers', $answers);
    }
}
