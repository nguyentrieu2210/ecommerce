<?php

namespace App\Services;

use App\Services\Interfaces\CommentServiceInterface;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class CommentService
 * @package App\Services
 */
class CommentService implements CommentServiceInterface
{

    protected $commentRepository;

    public function __construct(
        CommentRepositoryInterface $commentRepository
    )
    {
        $this->commentRepository = $commentRepository;
    }

    public function paginate($payload = []) {
        $condition = [];
        $limit = 20;
        if(isset($payload['limit'])) {
            $limit = $payload['limit'];
        }
        $fieldSelects = $this->fieldSelect();
        if(isset($payload['confirmComment']) && (int) $payload['confirmComment'] !== 0) {
            $condition[] = ['publish', '=', $payload['confirmComment']];
        }
        if(isset($payload['keyword']) && $payload['keyword'] !== "") {
            $condition['keyword'] = $payload['keyword'];
        }
        return $this->commentRepository->pagination($fieldSelects, 
                                                $condition, 
                                                ['customers'], 
                                                $limit, 
                                                ['id', 'DESC'],
                                                ['name', 'content', 'phone', 'star_rating', 'likes_count']
                                            );
    }

    public function update ($id, $payload) {
        DB::beginTransaction();
        try {
            $flag = $this->commentRepository->updateById($id, $payload);
            DB::commit();
            return $flag;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $flag = $this->commentRepository->destroy($id);
            DB::commit();
            return $flag;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    private function fieldSelect () {
        return [
            'id',
            'name',
            'album',
            'content',
            'likes_count',
            'star_rating',
            'model',
            'phone',
            'publish'
        ];
    }
}
