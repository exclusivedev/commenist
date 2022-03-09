<?php

namespace ExclusiveDev\Commenist\Http\Controllers;

use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ExclusiveDev\Commenist\Entity\Comment;
use ExclusiveDev\Commenist\Http\Requests\EditRequest;
use ExclusiveDev\Commenist\Http\Requests\GetRequest;
use ExclusiveDev\Commenist\Http\Requests\ReplyRequest;
use ExclusiveDev\Commenist\Http\Requests\SaveRequest;
use ExclusiveDev\Commenist\Http\Resources\CommentResource;
use ExclusiveDev\Commenist\UseCases\CommentService;
use ExclusiveDev\Commenist\UseCases\VoteService;
use Illuminate\Contracts\Encryption\DecryptException;

class CommentsController extends Controller
{
	use AuthorizesRequests;

	protected $voteService;
	protected $policyPrefix;

	/**
	 * CommentsController constructor.
	 * @param VoteService $voteService
	 */
	public function __construct(VoteService $voteService)
	{
		$this->middleware([request()->hasHeader('authorization') ? 'auth:api' : 'web','cors'], ['except' => ['get']]);
		$this->policyPrefix = config('comments.policy_prefix');
		$this->voteService = $voteService;
	}

	/**
	 * Creates a new comment for given model.
	 * @param SaveRequest $request
	 * @return array|\Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function store(SaveRequest $request)
	{
		$this->authorize($this->policyPrefix . '.store');

		try {
			$decryptedModelData = decrypt($request->encrypted_key);

			$commentableId = $decryptedModelData['id'];
			$modelPath = $decryptedModelData['type'];

		} catch (DecryptException $e) {
			throw new \DomainException('Decryption error');
		}

		if (!CommentService::modelIsExists($modelPath)) {
			throw new \DomainException('Model don\'t exists');
		}

		if (!CommentService::isCommentable(new $modelPath)) {
			throw new \DomainException('Model is\'t commentable');
		}

		$model = $modelPath::findOrFail($commentableId);
		if ($request->has('comment'))
			$request->comments = [$request->comment];

		foreach ($request->comments as $comment) {			
			CommentService::createComment(
				new Comment(),
				Auth::user(),
				$model,
				CommentService::htmlFilter($comment)
			);
		}
		// $comment = CommentService::createComment(
		// 	new Comment(),
		// 	Auth::user(),
		// 	$model,
		// 	CommentService::htmlFilter($request->comment)
		// );

		// return $request->ajax()
		// 	? [
		// 		'success' => true,
		// 		'comment' => new CommentResource($comment)
		// 	]
		// 	: redirect()->to(url()->previous() . '#comment-' . $comment->id);
		return $request->ajax() ? ['message' => __('The comments were successfully stored.')] : redirect()->to(url()->previous());
	}

	/**
	 * @param GetRequest $request
	 * @return array
	 */
	public function get(GetRequest $request): array
	{
		$decryptedModelData = decrypt($request->encrypted_key);

		$modelId = $decryptedModelData['id'];
		$modelPath = $decryptedModelData['type'];

		$orderBy = CommentService::orderByRequestAdapter($request);

		if (!CommentService::modelIsExists($modelPath)) {
			throw new \DomainException('Model don\'t exists');
		}

		if (!CommentService::isCommentable(new $modelPath)) {
			throw new \DomainException('Model is\'t commentable');
		}

		$model = $modelPath::where('id', $modelId)->first();

		return CommentResource::collection(
				$model->commentsWithChildrenAndCommenter()
					->parentless()
					->orderBy($orderBy['column'], $orderBy['direction'])
					->get()
			)->toArray($request);
	}

	/**
	 * @param Comment $comment
	 * @param Request $request
	 * @return array
	 */
	public function show(Comment $comment, Request $request): array
	{
		return [
			'comment' => $request->input('raw') ? $comment : new CommentResource($comment)
		];
	}

	/**
	 * Updates the message of the comment.
	 * @param EditRequest $request
	 * @param Comment $comment
	 * @return array|\Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function update(EditRequest $request, Comment $comment)
	{
		$this->authorize($this->policyPrefix . '.edit', $comment);

		CommentService::updateComment(
			$comment,
			CommentService::htmlFilter($request->comment)
		);

		return $request->ajax()
			? ['success' => true, 'comment' => new CommentResource($comment)]
			: redirect()->to(url()->previous() . '#comment-' . $comment->id);
	}

	/**
	 * Deletes a comment.
	 * @param Request $request
	 * @param Comment $comment
	 * @return array|\Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function destroy(Request $request, Comment $comment)
	{
		$this->authorize($this->policyPrefix . '.delete', $comment);

		try {
			CommentService::deleteComment($comment);
			$response = response(['message' => 'success']);
		} catch (\DomainException $e) {
			$response = response(['message' => $e->getMessage()], 401);
		}

		if ($request->ajax()) {
			return $response;
		}

		return redirect()->back();
	}

	/**
	 * Reply to comment
	 *
	 * @param Request $request
	 * @param Comment $comment
	 * @return array|\Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function reply(ReplyRequest $request, Comment $comment)
	{
		$this->authorize($this->policyPrefix . '.reply', $comment);

		$reply = CommentService::createComment(
			new Comment(),
			Auth::user(),
			$comment->commentable,
			CommentService::htmlFilter($request->comment),
			$comment
		);

		return $request->ajax()
			? ['success' => true, 'comment' => new CommentResource($reply)]
			: redirect()->to(url()->previous() . '#comment-' . $reply->id);
	}

}
