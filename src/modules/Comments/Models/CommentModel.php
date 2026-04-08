<?php

namespace Modules\Comments\Models;

use CodeIgniter\Model;

final class CommentModel extends Model
{
    protected $table      = 'comments';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'text', 'date'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'name' => 'required|valid_email|max_length[255]',
        'text' => 'required|min_length[1]|max_length[2000]',
        'date' => 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'    => 'Email обязателен для заполнения.',
            'valid_email' => 'Введите корректный email.',
            'max_length'  => 'Email не должен превышать 255 символов.',
        ],
        'text' => [
            'required'   => 'Текст комментария обязателен.',
            'min_length' => 'Текст комментария не может быть пустым.',
            'max_length' => 'Текст комментария не должен превышать 2000 символов.',
        ],
        'date' => [
            'required'    => 'Дата обязательна для заполнения.',
            'regex_match' => 'Дата должна быть в формате ГГГГ-ММ-ДД.',
        ],
    ];
}