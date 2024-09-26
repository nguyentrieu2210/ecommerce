<?php

namespace App\Repositories\Interfaces;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    public function getRatingSummary ($conditions = []);
    public function getRatingCount ($conditions = []);
}
