<?php
namespace App\Repositories\Tag;

use App\Models\Tag;
use App\Repositories\BaseRepository;
use App\Repositories\Tag\TagRepository;

/**
 * Class TagRepositoryEloquent.
 *
 * @package namespace App\Repositories\Tag;
 */
class TagRepositoryEloquent extends BaseRepository implements TagRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function getModel()
    {
        return Tag::class;
    }
}
