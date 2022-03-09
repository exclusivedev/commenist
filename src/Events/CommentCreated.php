<?php

namespace ExclusiveDev\Commenist\Events;

use Illuminate\Queue\SerializesModels;
use ExclusiveDev\Commenist\Entity\Comment;

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
