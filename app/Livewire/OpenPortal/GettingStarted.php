<?php

namespace App\Livewire\OpenPortal;

use App\Jobs\SendEmailNotification;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class GettingStarted extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $listeners = [
        'set-reply' => 'setReplyTo',
    ];

    public string $newReview = '';
    public $replyContent = [];
    public $showReplyBox = [];

    public mixed $user = null;


    public function mount(): void
    {

        $this->user = auth()->guard('web')->check()
            ? auth()->guard('web')->user()
            : auth()->guard('staff')->user();
    }


    public function postReview(): void
    {
        $this->validate([
            'newReview' => 'required|string|min:3',
        ]);

        $this->user->comments()->create([
            'content' => $this->newReview,

        ]);
        $this->newReview = '';
    }

    public function postReply($reviewId): void
    {
        $comment = Comment::findOrFail($reviewId);
        $this->validate([
            "replyContent.$reviewId" => 'required|string|min:3',
        ]);

        DB::beginTransaction();
        try {
            $this->user->replies()->create([
                'content' => $this->replyContent[$reviewId],
                'comment_id' => $reviewId,
            ]);

            $this->sendMessage($comment->user, $this->user->fullname, $comment->content);
            $this->replyContent[$reviewId] = '';
            $this->showReplyBox[$reviewId] = false;
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
        }


    }

    public function sendMessage($commenter, $replyer, $comment): void
    {
        $subject = "$replyer replied to your comment on " . config('app.name');
        $data = [
            'commenter' => $commenter,
            'replyer' => $replyer,
            'comment' => $comment
        ];

        dispatch(new SendEmailNotification($commenter,$subject, 'comment-reply', $data))->afterCommit();

    }

    #[On('review-updated')]
    public function render()
    {
        return view('livewire.open-portal.getting-started', [
            'reviews' => Comment::with('replies')->latest()->paginate(5)
        ]);
    }
}
