<!-- comments.blade.php -->
<div class="comment-box d-flex">
    <!-- User Image -->
    @php
        $img =
            $comment->user && $comment->user->image
                ? asset('uploads/user_profile/' . $comment->user->image)
                : asset('img/users/user-3.png');
    @endphp

    <img src="{{ $img }}" alt="User Image" class="comment-img">

    <!-- Comment Content -->
    <div class="comment-content">
        <h6 class="mb-1">{{ $comment->user->name }}
            <small class="comment-meta">{{ $comment->created_at->diffForHumans() }}</small>
        </h6>
        <div class="rating display">
            @if ($comment->rating)
                @for ($i = 5; $i > 0; $i--)
                    <label class="{{ $comment->rating >= $i ? 'opacity' : '' }}">☆</label>
                @endfor
            @endif
        </div>
        <p>{{ $comment->content }}</p>

        <!-- Reply Button -->
        @auth('web')
            <span class="comment-actions" data-productid="{{ $product->id }}"
                onclick="toggleReplyBox({{ $comment->id }}, {{ $product->id }})" id="reply-{{ $comment->id }}">Reply
            </span>
            @if (Auth::guard('web')->user()->id == $comment->user_id)
                <span class="text-primary edit-{{ $comment->id }}" data-commentid="{{ $comment->id }}"
                    data-productid="{{ $product->id }}" data-content="{{ $comment->content }}" id="editRply">Edit
                </span>
                @if ($comment->parent_id != NULL)
                    <span class="text-danger" id="deleteReply" data-commentid="{{ $comment->id }}">Delete</span>
                    </span>
                @endif
            @else
                @if (Auth::guard('web')->user()->id == $comment->product->user_id)
                    <a href="void(0)" class="text-danger" id="deleteReply"
                        data-commentid="{{ $comment->id }}">Delete</span>
                    </a>
                @endif
            @endif
            <!-- Reply Box (Hidden by default) -->
            <div id="replybox-{{ $comment->id }}"></div>
        @endauth


        <!-- Nested Replies -->
        @if ($comment->children->count())
            @foreach ($comment->children as $reply)
                @include('include.partials.comment', ['comment' => $reply, 'product' => $product])
            @endforeach
        @endif
    </div>
</div>
