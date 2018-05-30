<?php

namespace App\Contracts\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ReviewRepository.
 *
 * @package namespace App\Contracts\Repositories;
 */
interface ReviewRepository extends RepositoryInterface
{
    public function getNewsFeed($userId);
}
