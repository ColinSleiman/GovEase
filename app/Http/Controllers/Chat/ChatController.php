<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getContacts()
    {
        $user = Auth::user();
        
        if ($user->role->name === 'Citizen') {
            // Return all OfficeStaff
            $contacts = User::whereHas('role', function ($query) {
                $query->where('name', 'OfficeStaff');
            })
            ->withCount(['sentMessages as unread_count' => function ($query) use ($user) {
                $query->where('receiver_id', $user->id)->where('is_read', false);
            }])
            ->orderByDesc('unread_count')
            ->get();
            
            return response()->json($contacts);
        } elseif ($user->role->name === 'OfficeStaff') {
            // Return all Citizens who have messaged this OfficeStaff or whom this OfficeStaff has messaged
            $contactIds = Message::where('sender_id', $user->id)
                ->pluck('receiver_id')
                ->merge(Message::where('receiver_id', $user->id)->pluck('sender_id'))
                ->unique();
                
            $contacts = User::whereIn('id', $contactIds)
                ->withCount(['sentMessages as unread_count' => function ($query) use ($user) {
                    $query->where('receiver_id', $user->id)->where('is_read', false);
                }])
                ->orderByDesc('unread_count')
                ->get();
            
            return response()->json($contacts);
        }
        
        return response()->json([]);
    }

    public function getUnreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();
            
        return response()->json(['unread_count' => $count]);
    }

    public function getMessages(User $contact)
    {
        $userId = Auth::id();
        
        // Mark unread messages from this contact as read
        Message::where('sender_id', $contact->id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        $messages = Message::where(function ($query) use ($userId, $contact) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $contact->id);
            })
            ->orWhere(function ($query) use ($userId, $contact) {
                $query->where('sender_id', $contact->id)
                      ->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
            
        return response()->json($messages);
    }

    public function sendMessage(Request $request, User $contact)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $contact->id,
            'message' => $request->message,
        ]);

        return response()->json($message);
    }
}
