<?php

namespace ExclusiveDev\Commenist\Events;

use Illuminate\Queue\SerializesModels;
use ExclusiveDev\Commenist\Entity\Comment;

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
