<?php

namespace Osem\Commenist\Events;

use Illuminate\Queue\SerializesModels;
use Osem\Commenist\Entity\Comment;

class CommentCreated
{
    use SerializesModels;

    public $comment;

    /**
     * CommentCreated constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
