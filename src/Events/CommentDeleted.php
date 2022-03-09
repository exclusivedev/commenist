<?php

namespace Osem\Commenist\Events;

use Illuminate\Queue\SerializesModels;
use Osem\Commenist\Entity\Comment;

class CommentDeleted
{
    use SerializesModels;

    public $comment;

    /**
     * CommentDeleted constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
