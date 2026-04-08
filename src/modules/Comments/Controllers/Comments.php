<?php

namespace Modules\Comments\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Comments\Assemblers\CommentListQueryDTOAssembler;
use Modules\Comments\Assemblers\CreateCommentDTOAssembler;
use Modules\Comments\Exceptions\ValidationException;
use Modules\Comments\Services\CreateCommentService;
use Modules\Comments\Services\DeleteCommentService;
use Modules\Comments\Services\GetCommentsService;
use Modules\Comments\Models\CommentModel;
use Modules\Comments\Repositories\CommentRepository;

final class Comments extends Controller
{
    private GetCommentsService               $getCommentsService;
    private CreateCommentService             $createCommentService;
    private DeleteCommentService             $deleteCommentService;
    private CommentListQueryDTOAssembler     $commentListQueryDTOAssembler;
    private CreateCommentDTOAssembler        $createCommentDTOAssembler;

    public function __construct() {
        $repository = new CommentRepository(new CommentModel());

        $this->getCommentsService           = new GetCommentsService($repository);
        $this->createCommentService         = new CreateCommentService($repository);
        $this->deleteCommentService         = new DeleteCommentService($repository);
        $this->commentListQueryDTOAssembler = new CommentListQueryDTOAssembler();
        $this->createCommentDTOAssembler    = new CreateCommentDTOAssembler();
    }

    /**
     * @return string
     */
    public function index(): string
    {
        return view('comments/index');
    }

    /**
     * @return ResponseInterface
     */
    public function list(): ResponseInterface
    {
        $dto    = $this->commentListQueryDTOAssembler->assemble($this->request);
        $result = $this->getCommentsService->execute($dto);

        return $this->response->setJSON($result);
    }

    /**
     * @return ResponseInterface
     */
    public function store(): ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        $dto = $this->createCommentDTOAssembler->assemble($this->request);

        try {
            $id = $this->createCommentService->execute($dto);
        } catch (ValidationException $e) {
            return $this->response->setStatusCode(422)->setJSON([
                'errors' => $e->getErrors(),
            ]);
        }

        return $this->response->setStatusCode(201)->setJSON([
            'message' => 'Комментарий добавлен.',
            'id'      => $id,
        ]);
    }

    /**
     * @param int $id
     *
     * @return ResponseInterface
     */
    public function destroy(int $id): ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        if (!$this->deleteCommentService->execute($id)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Комментарий не найден.']);
        }

        return $this->response->setJSON(['message' => 'Комментарий удалён.']);
    }
}