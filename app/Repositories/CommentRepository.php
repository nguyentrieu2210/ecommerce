<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Interfaces\CommentRepositoryInterface;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    protected $model;

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    public function getRatingSummary ($conditions = []) {
        $query = $this->model;
        if(count($conditions)) {
            foreach($conditions as $item) {
                $query = $query->where($item[0], $item[1], $item[2]);
            }
        }
        return $query->selectRaw('COUNT(*) as total_ratings, AVG(star_rating) as average_rating')->first();
    }

    public function getRatingCount($conditions = []) {
        $query = $this->model;
        if(count($conditions)) {
            foreach($conditions as $item) {
                $query = $query->where($item[0], $item[1], $item[2]);
            }
        }
        return $query->selectRaw('star_rating, COUNT(*) as count')->groupBy('star_rating')->pluck('count', 'star_rating');
    }
}
