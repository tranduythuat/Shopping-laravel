<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Tags\TagsRepositoryInterface;
use App\Traits\JsonData;
use Yajra\DataTables\DataTables;

class TagController extends Controller
{
    use JsonData;

    protected $tagsRepo;

    public function __construct(TagsRepositoryInterface $tagsRepo)
    {
        $this->tagsRepo = $tagsRepo;
    }
}
