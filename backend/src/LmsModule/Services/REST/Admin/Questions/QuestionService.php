<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\REST\Admin\Questions;

use Psk\LmsModule\Helpers\ConflictResult;
use Psk\LmsModule\Models\Questions\QuestionModel;
use Psk\LmsModule\Repositories\Questions\QuestionRepository;
use Psk\LmsModule\Repositories\Tests\ResultRepository;
use Psk\LmsModule\Services\QuestionTypes\AccordanceService;
use Psk\LmsModule\Services\QuestionTypes\MultipleChoiceService;
use Psk\LmsModule\Services\QuestionTypes\MultipleResponseService;
use Psk\LmsModule\Services\QuestionTypes\OrderingService;
use Psk\LmsModule\Services\QuestionTypes\ShortResponseService;
use Psk\LmsModule\Services\QuestionTypes\TrueOrFalseService;
use Psk\RestModule\RestServiceInterface;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\InternalServerErrorResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;

/**
 * @internal
 */
final class QuestionService implements RestServiceInterface
{
    /** @var QuestionRepository */
    private $questionRepository;

    /** @var ResultRepository */
    private $resultRepository;

    /** @var OrderingService */
    private $orderingService;

    /** @var AccordanceService */
    private $accordanceService;

    /** @var TrueOrFalseService */
    private $trueOrFalseService;

    /** @var ShortResponseService */
    private $shortResponseService;

    /** @var MultipleChoiceService */
    private $multipleChoiceService;

    /** @var MultipleResponseService */
    private $multipleResponseService;

    public function __construct(
        QuestionRepository      $questionRepository,
        ResultRepository        $resultRepository,
        OrderingService         $orderingService,
        AccordanceService       $accordanceService,
        TrueOrFalseService      $trueOrFalseService,
        ShortResponseService    $shortResponseService,
        MultipleChoiceService   $multipleChoiceService,
        MultipleResponseService $multipleResponseService
    )
    {
        $this->questionRepository      = $questionRepository;
        $this->resultRepository        = $resultRepository;
        $this->orderingService         = $orderingService;
        $this->accordanceService       = $accordanceService;
        $this->trueOrFalseService      = $trueOrFalseService;
        $this->shortResponseService    = $shortResponseService;
        $this->multipleChoiceService   = $multipleChoiceService;
        $this->multipleResponseService = $multipleResponseService;
    }

    /**
     * @param array<string,mixed> $params
     * @return SuccessResult|void
     */
    public function find($params): AbstractResult
    {
        return new InternalServerErrorResult("Not implemented yet.");
    }

    /**
     * @param positive-int $id
     * @return NotFoundResult|SuccessResult
     * @throws \ReflectionException
     */
    public function get($id): AbstractResult
    {
        $id = (int)$id;
        $question = $this->questionRepository->findById($id);

        return $question ? new SuccessResult($question) : new NotFoundResult();
    }

    /**
     * @param array<string,mixed> $data
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function create($data): AbstractResult
    {
        $type = $data['type'] ?? 0;

        switch ($type) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                $service = QuestionModel::getTypes()[$type] . 'Service';
                return $this->$service->create($data);
            default:
                return new InternalServerErrorResult(QuestionModel::INCORRECT_TYPE);
        }
    }

    /**
     * @param positive-int $id
     * @param array<string,mixed> $data
     * @return NotFoundResult|SuccessResult|ValidationErrorsResult
     * @throws \ReflectionException
     */
    public function update($id, $data): AbstractResult
    {
        $id = (int)$id;
        $type = $data['type'] ? (int) $data['type'] : 0;

        if (!$question = $this->questionRepository->findById($id, $type)) {
            return new NotFoundResult();
        }

        switch ($question->getType()) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                $service = QuestionModel::getTypes()[$question->getType()] . 'Service';
                return $this->$service->update($question, $data);
            default:
                return new InternalServerErrorResult(QuestionModel::INCORRECT_TYPE);
        }
    }

    /**
     * @param positive-int $id
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function delete($id): AbstractResult
    {
        $id = (int)$id;

        if ($this->questionRepository->findById($id)) {
            return new NotFoundResult();
        }

        if ($this->resultRepository->findByQuestionId($id)) {
            return new ConflictResult(QuestionModel::CANNOT_BE_DELETED);
        }

        $this->questionRepository->delete($id);

        return new SuccessResult(QuestionModel::DELETE_SUCCESS);
    }
}
