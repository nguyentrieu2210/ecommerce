<?php

namespace App\Services\Interfaces;

/**
 * Interface CommentServiceInterface
 * @package App\Services\Interfaces
 */
interface CommentServiceInterface
{
    public function paginate();
    public function update ($id, $payload);
    public function destroy($id);
}
