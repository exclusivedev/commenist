<?php

namespace Osem\Commenist\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Osem\Commenist\Contracts\Comment as CommentInterface;
use Osem\Commenist\Events\CommentCreated;
use Osem\Commenist\Events\CommentDeleted;
use Osem\Commenist\Events\CommentUpdated;

class Comment extends Model implements CommentInterface
{
    use SoftDeletes;

    protected $fillable = ['comment', 'rating', 'commenter_id'];

    protected $dispatchesEvents = [
        'created' => CommentCreated::class,
        'updated' => CommentUpdated::class,
        'deleted' => CommentDeleted::class,
    ];

    protected $dates = ['deleted_at', 'created_at'];
    /*    protected $with = ['children', 'commenter'];*/

    /**
     * Returns all comments that this comment is the parent of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(config('comments.models.comment'), 'parent_id');
    }

    /**
     * Recursive version of comments with commenter relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allChildrenWithCommenter()
    {
        return $this->hasMany(config('comments.models.comment'), 'parent_id')
            ->with('allChildrenWithCommenter', 'commenter');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeParentless($query)
    {
        return $query->doesntHave('parent');
    }

    /**
     * The user who posted the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commenter()
    {
        return $this->belongsTo(config('comments.models.commenter'));
    }

    /**
     * The model that was commented upon.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Returns the comment to which this comment belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(config('comments.models.comment'), 'parent_id');
    }

    /**
     * @return int
     */
    public function rating(): int
    {
        return $this->rating;
    }

    /**
     * @return int
     */
    public function votesCount(): int
    {
        return $this->votes()->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CommentVotes::class, 'comment_id');
    }

    /**
     * @param $commenterId
     * @param $vote
     */
    public function addNewVoteIntoRatingRecords($commenterId, $vote)
    {
        $this->votes()->save(new CommentVotes([
                'commenter_id' => $commenterId,
                'commenter_vote' => $vote
            ]
        ));
    }
}
