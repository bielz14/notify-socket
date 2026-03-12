<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * List all users + unread count for current user
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();

        $unreadNotifications = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->with('sender')
            ->orderByDesc('created_at')
            ->get();

        return view('notifications.index', compact('users', 'unreadNotifications'));
    }

    /**
     * Send a notification to a user
     */
    public function send(Request $request, User $receiver)
    {
        $request->validate([
            'text' => ['required', 'string', 'max:5000'],
        ]);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $receiver->id,
            'text'        => $request->text,
        ]);

        $message->load('sender');

        MessageSent::dispatch($message);

        return response()->json([
            'id'          => $message->id,
            'text'        => $message->text,
            'sender_id'   => $message->sender_id,
            'sender_name' => $message->sender->name,
            'created_at'  => $message->created_at->format('H:i'),
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markRead(Message $message)
    {
        if ($message->receiver_id !== Auth::id()) {
            abort(403);
        }

        $message->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead()
    {
        Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
