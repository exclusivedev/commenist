<?php

namespace Osem\Commenist\Events;

use Illuminate\Queue\SerializesModels;
use Osem\Commenist\Entity\Comment;

class CommentUpdated
{
    use SerializesModels;

    public $comment;

    /**
     * CommentUpdated constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
