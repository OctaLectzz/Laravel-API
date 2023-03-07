<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Comment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCommentOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $commentId = $request->route('comment');
        $comment = Comment::find($commentId);
        
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        
        // Check if the authenticated user is the owner of the comment
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }

        return $next($request);
    }
}
