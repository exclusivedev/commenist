<?php

namespace ExclusiveDev\Commenist\Events;

use Illuminate\Queue\SerializesModels;
use ExclusiveDev\Commenist\Entity\Comment;

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
