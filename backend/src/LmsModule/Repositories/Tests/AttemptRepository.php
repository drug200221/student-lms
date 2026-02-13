<?php

namespace Psk\LmsModule\Repositories\Tests;

use Ox3a\Common\Service\ShareServiceInterface;
use Ox3a\Service\DbService;
use Psk\LmsModule\Models\Tests\AttemptModel;
use Psk\LmsModule\Repositories\Db\Tests\AttemptModel\AttemptConditions;
use Psk\LmsModule\Repositories\Db\Tests\AttemptModel\AttemptHydrator;
use Psk\LmsModule\Repositories\Db\Tests\AttemptModel\AttemptMapper;

final class AttemptRepository implements ShareServiceInterface
{
    /** @var AttemptMapper */
    private $attemptMapper;

    /** @var TestRepository */
    private $testRepository;

    /** @var ResultRepository */
    private $resultRepository;

    /** @var DbService  */
    private $dbService;

    public function __construct(
        AttemptMapper    $attemptMapper,
        TestRepository   $testRepository,
        ResultRepository $resultRepository,
        DbService        $dbService
    )
    {
        $this->attemptMapper    = $attemptMapper;
        $this->testRepository   = $testRepository;
        $this->resultRepository = $resultRepository;
        $this->dbService        = $dbService;
    }

    /**
     * @param positive-int $id
     * @return AttemptModel|null
     * @throws \ReflectionException
     */
    public function findById(int $id): ?AttemptModel
    {
        $conditions = new AttemptConditions();
        $conditions->getId()->equal($id);

        $list = $this->findBy($conditions);

        return $list ? $list[0] : null;
    }

    /**
     * @param positive-int $testId
     * @return AttemptModel[]
     * @throws \ReflectionException
     */
    public function findByTestId(int $testId): array
    {
        $conditions = new AttemptConditions();
        $conditions->getTestId()->equal($testId);

        return $this->findBy($conditions);
    }

    /**
     * @param AttemptConditions $conditions
     * @return AttemptModel[]
     * @throws \ReflectionException
     */
    public function findBy(AttemptConditions $conditions): array
    {
        $attempts = $this->attemptMapper->findBy($conditions);

        if (empty($attempts)) {
            return [];
        }

        $hydrator = new AttemptHydrator();

        $testIds = array_unique(array_map(static function ($attempt) {
            return $attempt->getTestId();
        }, $attempts));

        $attemptIds = array_map(static function ($attempt) {
            return $attempt->getId();
        }, $attempts);

        $tests = $this->testRepository->findByIds($testIds);
        $results = $this->resultRepository->findByAttemptIds($attemptIds);

        $testsById = [];
        foreach ($tests as $test) {
            $testsById[$test->getId()] = $test;
        }

        $resultsByAttempt = [];
        foreach ($results as $result) {
            $resultsByAttempt[$result->getAttemptId()][] = $result;
        }

        foreach ($attempts as $attempt) {
            $test = $testsById[$attempt->getTestId()] ?? null;
            $hydrator->hydrateProperty($attempt, 'test', $test);

            $attemptResults = $resultsByAttempt[$attempt->getId()] ?? [];
            $hydrator->hydrateProperty($attempt, 'results', $attemptResults);
        }

        return $attempts;
    }

    /**
     * @param positive-int $userId
     * @param positive-int$testId
     * @throws \ReflectionException
     */
    public function create(int $userId, int $testId): AttemptModel
    {
        $attempt = new AttemptModel();

        $attempt->setUserId($userId);
        $attempt->setTestId($testId);
        $attempt->setStartAt(new \DateTimeImmutable());
        $attempt->setIsFinished(false);
        $attempt->setGrade(0);

        $this->save($attempt);

        return $attempt;
    }

    /**
     * @param AttemptModel $attempt
     * @return void
     * @throws \ReflectionException
     */
    public function save(AttemptModel $attempt): void
    {
        $this->attemptMapper->save($attempt);
    }

    /**
     * @param positive-int $attemptId
     * @return void
     */
    public function delete(int $attemptId): void
    {
        $this->dbService->query('DELETE FROM lms_tests_results WHERE attempt_id = ?', $attemptId);

        $this->attemptMapper->delete($attemptId);
    }
}
