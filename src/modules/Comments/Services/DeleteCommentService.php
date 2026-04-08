<?php

namespace Modules\Comments\Services;

use Modules\Comments\Repositories\CommentRepository;

final class DeleteCommentService
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
     * @param int $id
     *
     * @return bool
     */
    public function execute(int $id): bool
    {
        if (! $this->repository->findById($id)) {
            return false;
        }

        $this->repository->deleteById($id);

        return true;
    }
}