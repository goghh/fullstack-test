<?php

namespace Modules\Comments\Services;

use Modules\Comments\DTOs\CreateCommentDTO;
use Modules\Comments\Exceptions\ValidationException;
use Modules\Comments\Repositories\CommentRepository;

final class CreateCommentService
{
    private CommentRepository $repository;

    /**
     * @param CommentRepository $repository
     */
    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateCommentDTO $dto
     *
     * @return int
     *
     * @throws ValidationException
     */
    public function execute(CreateCommentDTO $dto): int
    {
        if (!$this->repository->create($dto->toArray())) {
            throw new ValidationException($this->repository->getValidationErrors());
        }

        return $this->repository->getLastInsertId();
    }
}