<?php

namespace ExclusiveDev\Commenist\Http\Controllers;

use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use ExclusiveDev\Commenist\Entity\Comment;
use ExclusiveDev\Commenist\Http\Requests\VoteRequest;
use ExclusiveDev\Commenist\UseCases\CommentService;
use ExclusiveDev\Commenist\UseCases\VoteService;

class VoteController extends Controller
{
    use ValidatesRequests, AuthorizesRequests;

    protected $commentService;
    protected $voteService;
    protected $policyPrefix;

    /**
     * CommentsController constructor.
     * @param VoteService $voteService
     */
    public function __construct(VoteService $voteService)
    {
        $this->middleware(['web', 'auth']);
        $this->policyPrefix = config('comments.policy_prefix');
        $this->voteService = $voteService;
    }

    public function vote(VoteRequest $request, Comment $comment)
    {
        $this->authorize($this->policyPrefix . '.vote', $comment);

        $this->voteService->make(Auth::user(), $comment, $request->vote);
        $rating = CommentService::ratingRecalculation($comment);
        $votesCount = $comment->votesCount();

        return $request->ajax() ? ['success' => true, 'count' => $votesCount, 'rating' => $rating] : redirect()->to(url()->previous() . '#comment-' . $comment->id);
    }
}
