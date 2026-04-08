<?php

namespace Modules\Comments\Assemblers;

use CodeIgniter\HTTP\IncomingRequest;
use Modules\Comments\DTOs\CommentListQueryDTO;
use Modules\Comments\DTOs\CreateCommentDTO;

final class CommentListQueryDTOAssembler
{
    /**
     * @param IncomingRequest $request
     *
     * @return CommentListQueryDTO
     */
    public function assemble(IncomingRequest $request): CommentListQueryDTO
    {
        $page = max(1, (int) ($request->getGet('page') ?? 1));

        return new CommentListQueryDTO(
            $page,
            $request->getGet('sort_by')  ?? 'id',
            $request->getGet('sort_dir') ?? 'desc'
        );
    }
}