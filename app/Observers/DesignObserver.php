<?php

namespace App\Observers;

use App\Models\Design;
use App\Models\FcmToken;
use App\Models\User;
use App\Notifications\DesignCreated;
use App\service\FcmV1Service;
use Illuminate\Support\Facades\Notification;

class DesignObserver
{
   public function created(Design $design): void
    {
        $admins = User::permission('admin.design.manage')->get();

        if ($admins->isEmpty()) {
            return;
        }
        $adminIds = User::permission('admin.design.manage')->pluck('id')->all(); //

   $tokens = FcmToken::whereIn('user_id', $adminIds)->pluck('token')->toArray();
        if (!$tokens) return;

        app(FcmV1Service::class)->sendToTokens(
            $tokens,
            "New Design 🧾",
            "New Design created",
            ['type' => 'design', 'design_id' => $design->id]
        );
        Notification::send($admins, new DesignCreated($design));
    }}
