<?php

namespace Modules\Comments\DTOs;

final class CreateCommentDTO
{
    public string $name;
    public string $text;
    public string $date;

    /**
     * @param string $name
     * @param string $text
     * @param string $date
     */
    public function __construct(string $name, string $text, string $date)
    {
        $this->name = $name;
        $this->text = $text;
        $this->date = $date;
    }

    /**
     * @return array{name: string, text: string, date: string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'text' => $this->text,
            'date' => $this->date,
        ];
    }
}