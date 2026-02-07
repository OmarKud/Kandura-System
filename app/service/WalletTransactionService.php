<?php

namespace App\service;

use App\Enum\TransactionType;
use App\Enum\TransactionTypeEnum;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletTransactionService
{
    private function makeReference(): string
    {
        // مثال: TX-20260205-153045-482193
        return 'TX-' . now()->format('Ymd-His') . '-' . random_int(100000, 999999);
    }

 
    public function recordOrderPayment(Order $order): Transaction
    {
        return DB::transaction(function () use ($order) {

            // شروط أمان
            if (strtolower((string)$order->status) !== 'completed') {
                throw new \RuntimeException('Order is not completed');
            }

          

            if (($order->payment_status ?? null) !== 'paid') {
                throw new \RuntimeException('Order is not paid');
            }

            $exists = Transaction::where('order_id', $order->id)
                ->where('type', TransactionTypeEnum::PAYMENT)
                ->exists();

            if ($exists) {
                return Transaction::where('order_id', $order->id)
                    ->where('type', TransactionTypeEnum::PAYMENT)
                    ->firstOrFail();
            }

            $final = (float) ($order->final_price ?? $order->price ?? 0);
            if ($final <= 0) {
                throw new \RuntimeException('Invalid order amount');
            }

            return Transaction::create([
                'reference' => $this->makeReference(),
                'user_id'   => $order->user_id,
                'order_id'  => $order->id,
                'admin_id'  => null,
                'type'      => TransactionTypeEnum::PAYMENT,
                'amount'    => abs($final),
                'meta'      => [
                    'order_status' => $order->status,
                    'payment_status' => $order->payment_status,
                ],
            ]);
        });
    }

  
    public function recordDeposit(User $targetUser, float $amount, ?User $admin = null): Transaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Deposit amount must be > 0');
        }

        return DB::transaction(function () use ($targetUser, $amount, $admin) {

            $wallet = Wallet::firstOrCreate(['user_id' => $targetUser->id], ['amount' => 0]);
            Wallet::where('id', $wallet->id)->lockForUpdate()->first();

            return Transaction::create([
                'reference' => $this->makeReference(),
                'user_id'   => $targetUser->id,
                'order_id'  => null,
                'admin_id'  => $admin?->id,
                'type'      => TransactionTypeEnum::DEPOSIT,
                'amount'    => abs($amount),
                'meta'      => [
                    'source' => 'admin_charge',
                ],
            ]);
        });
    }
}
