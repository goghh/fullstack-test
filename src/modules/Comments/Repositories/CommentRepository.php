<?php

namespace Modules\Comments\Repositories;

use Modules\Comments\Models\CommentModel;

final class CommentRepository
{
    private CommentModel $model;

    /**
     * @param CommentModel $model
     */
    public function __construct(CommentModel $model)
    {
        $this->model = $model;
    }

    /**
     * @param int    $page
     * @param int    $perPage
     * @param string $sortBy
     * @param string $sortDir
     *
     * @return array{comments: array, total: int, totalPages: int, page: int}
     */
    public function getComments(int $page, int $perPage, string $sortBy, string $sortDir): array
    {
        $allowedSortBy  = ['id', 'date'];
        $allowedSortDir = ['asc', 'desc'];

        $sortBy  = in_array($sortBy, $allowedSortBy)   ? $sortBy  : 'id';
        $sortDir = in_array($sortDir, $allowedSortDir) ? $sortDir : 'desc';

        $offset = ($page - 1) * $perPage;

        $data  = $this->model->orderBy($sortBy, $sortDir)->findAll($perPage, $offset);
        $total = $this->model->countAll();

        return [
            'comments'   => $data,
            'total'      => $total,
            'totalPages' => (int) ceil($total / $perPage),
            'page'       => $page,
        ];
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function create(array $data): bool
    {
        return (bool)$this->model->insert($data);
    }

    /**
     * @return int
     */
    public function getLastInsertId(): int
    {
        return (int)$this->model->getInsertID();
    }

    /**
     * @param int $id
     *
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function deleteById(int $id): void
    {
        $this->model->delete($id);
    }

    /**
     * @return array<string, string>
     */
    public function getValidationErrors(): array
    {
        return $this->model->errors();
    }
}