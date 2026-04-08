<?php

namespace Modules\Comments\Services;

use Modules\Comments\DTOs\CommentListQueryDTO;
use Modules\Comments\Repositories\CommentRepository;

final class GetCommentsService
{
    private const PER_PAGE = 3;

    private CommentRepository $repository;

    /**
     * @param CommentRepository $repository
     */
    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CommentListQueryDTO $dto
     *
     * @return array{comments: array, total: int, totalPages: int, page: int}
     */
    public function execute(CommentListQueryDTO $dto): array
    {
        return $this->repository->getComments($dto->page, self::PER_PAGE, $dto->sortBy, $dto->sortDir);
    }
}