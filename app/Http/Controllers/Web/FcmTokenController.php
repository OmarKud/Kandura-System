<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'token' => ['required','string'],
        ]);

        FcmToken::updateOrCreate(
            ['token' => $data['token']],
            [
                'user_id' => $request->user()->id,
                'platform' => 'web',
                'last_seen_at' => now(),
            ]
        );

        return response()->json(['ok' => true]);
    }
    public function destroy(Request $request)
{
    FcmToken::where('user_id', auth()->id())->delete();
    return response()->json(['ok' => true]);
}

}
