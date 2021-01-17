<?php

namespace App\Repositories\Tags;

interface TagsRepositoryInterface
{

    public function getAll();

    public function getTagLimit($limit);

}
