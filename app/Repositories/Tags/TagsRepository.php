<?php

namespace App\Repositories\Tags;

use App\Models\Tag;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TagsRepository implements TagsRepositoryInterface
{
    protected $model;

    public function __construct(Tag $model) {
        $this->model = $model;
    }

    public function getAll()
    {
        try {
            $data = $this->model::where('status', 'active')->orderBy('name', 'asc')->get();

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getTagLimit($limit)
    {
        try {
            $data = $this->model::where('status', 'active')->orderBy('name', 'asc')->limit(10)->get();

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    protected function errorResult($msg)
    {
        $message = array(
            'errorMsg' => $msg
        );

        return $message;
    }
}


