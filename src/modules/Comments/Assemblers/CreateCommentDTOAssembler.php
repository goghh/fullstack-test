<?php

namespace Modules\Comments\Assemblers;

use CodeIgniter\HTTP\IncomingRequest;
use Modules\Comments\DTOs\CommentListQueryDTO;
use Modules\Comments\DTOs\CreateCommentDTO;

final class CreateCommentDTOAssembler
{
    /**
     * @param IncomingRequest $request
     *
     * @return CreateCommentDTO
     */
    public function assemble(IncomingRequest $request): CreateCommentDTO
    {
        return new CreateCommentDTO(
            trim($request->getPost('name') ?? ''),
            trim($request->getPost('text') ?? ''),
            trim($request->getPost('date') ?? '')
        );
    }
}