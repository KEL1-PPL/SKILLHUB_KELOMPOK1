<?php

namespace App\Http\Controllers;

use App\Models\Diskusi;
use App\Models\Reply;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(Request $request, $diskusiId)
    {
        $request->validate([
            'content' => 'required',
        ]);

        Reply::create([
            'diskusi_id' => $diskusiId,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Jawaban berhasil dikirim.');
    }

    public function markAsBest($id)
    {
        $reply = Reply::findOrFail($id);

        // Reset best answer
        Reply::where('diskusi_id', $reply->diskusi_id)->update(['is_best_answer' => false]);

        // Tandai yang ini sebagai best answer
        $reply->update(['is_best_answer' => true]);

        return back()->with('success', 'Jawaban ditandai sebagai terbaik.');
    }

    public function destroy($id)
    {
        $reply = Reply::findOrFail($id);
        $reply->delete();

        return back()->with('success', 'Balasan berhasil dihapus.');
    }
}
