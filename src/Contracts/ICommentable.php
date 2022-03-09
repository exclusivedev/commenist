<?php

namespace ExclusiveDev\Commenist\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Commentable model Interface
 *
 * Interface ICommentable
 * @package ExclusiveDev\Commenist\Contracts
 */
interface ICommentable
{
    public function isCommentable();

    public function comments();

    public function scopeWithCommentsCount(Builder $query);

}