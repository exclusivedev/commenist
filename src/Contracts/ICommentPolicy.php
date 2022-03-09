<?php

namespace ExclusiveDev\Commenist\Contracts;

/**
 * Comment auth policy
 *
 * Interface ICommentPolicy
 * @package ExclusiveDev\Commenist\Contracts
 */
interface ICommentPolicy
{
    public function store($user);

    public function edit($user, Comment $comment);

    public function delete($user, Comment $comment);

    public function reply($user, Comment $comment);

    public function vote($user, Comment $comment);
}