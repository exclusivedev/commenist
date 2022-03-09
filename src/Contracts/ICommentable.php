<?php

namespace Osem\Commenist\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Commentable model Interface
 *
 * Interface ICommentable
 * @package Osem\Commenist\Contracts
 */
interface ICommentable
{
    public function isCommentable();

    public function comments();

    public function scopeWithCommentsCount(Builder $query);

}