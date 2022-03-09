<?php

namespace Osem\Commenist\Policies;

use Osem\Commenist\Contracts\ICommentPolicy;
use Osem\Commenist\Contracts\Comment;

class CommentPolicy implements ICommentPolicy
{
    /**
     * @param $user
     * @return bool
     */
    public function store($user): bool
    {
        return true;
    }

    /**
     * @param $user
     * @param Comment $comment
     * @return bool
     */
    public function delete($user, Comment $comment): bool
    {
        return $user->id === $comment->commenter_id;
    }

    /**
     * @param $user
     * @param Comment $comment
     * @return bool
     */
    public function edit($user, Comment $comment): bool
    {
        return $user->id === $comment->commenter_id;
    }

    /**
     * @param $user
     * @param Comment $comment
     * @return bool
     */
    public function reply($user, Comment $comment): bool
    {
        return true;
    }

    /**
     * @param $user
     * @param Comment $comment
     * @return bool
     */
    public function vote($user, Comment $comment): bool
    {
        return $user->id !== $comment->commenter_id;
    }
}