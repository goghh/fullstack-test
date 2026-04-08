<?php

namespace Modules\Comments\DTOs;

final class CommentListQueryDTO
{
    public int    $page;
    public string $sortBy;
    public string $sortDir;

    /**
     * @param int    $page
     * @param string $sortBy
     * @param string $sortDir
     */
    public function __construct(int $page, string $sortBy, string $sortDir)
    {
        $this->page    = $page;
        $this->sortBy  = $sortBy;
        $this->sortDir = $sortDir;
    }
}