<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    // Endpoint untuk Payment Gateway (Bab 7.2)
    public function handlePayment(Request $request)
    {
    // Endpoint untuk Logistik (Bab 7.3)
    public function handleLogistics(Request $request)
    {
        $order = Order::where('order_number', $request->order_number)->first();
        if (!$order) return response()->json(['message' => 'Order Not Found'], 404);

        $oldStatus = $order->status;
        
        $order->update([
            'status' => 'shipped',
            'tracking_number' => $request->tracking_number
        ]);

        DB::table('order_status_logs')->insert([
            'order_id' => $order->id,
            'from_status' => $oldStatus,
            'to_status' => 'shipped',
            'actor_type' => 'webhook',
            'reason_code' => 'COURIER_PICKED_UP',
            'user_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Logistics Webhook Processed']);
    }
        // 1. Verifikasi Signature (HMAC) - Cegah Spoofing Webhook
        $signature = $request->header('X-Signature');
        $secret = 'rahasia_partku_123'; // Di dunia nyata, ini ada di .env
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            \Log::warning('Webhook signature tidak valid dari IP: ' . $request->ip());
            return response()->json(['message' => 'Invalid Signature'], 401);
        }

        $eventId = $request->event_id;
        $orderNumber = $request->order_number;
        $status = $request->status; // 'paid' atau 'failed'

        // 2. Idempotency Check (Bab 7.2.1)
        // Cek apakah event ini sudah pernah diproses
        $alreadyProcessed = DB::table('webhook_logs')->where('event_id', $eventId)->exists();
        if ($alreadyProcessed) {
            return response()->json(['message' => 'Duplicate Event, Ignored'], 200);
        }

        $order = Order::where('order_number', $orderNumber)->first();
        if (!$order) {
            return response()->json(['message' => 'Order Not Found'], 404);
        }

        DB::beginTransaction();
        try {
            $oldStatus = $order->status;

            // 3. Update Status Order
            $order->update(['status' => $status]);

            // 4. Catat Audit Trail (Bab 7.5.1)
            DB::table('order_status_logs')->insert([
                'order_id' => $order->id,
                'from_status' => $oldStatus,
                'to_status' => $status,
                'actor_type' => 'webhook',
                'actor_type' => 'webhook',
                'reason_code' => 'PG_NOTIFICATION',
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5. Simpan Log Webhook (Idempotency)
            DB::table('webhook_logs')->insert([
                'event_id' => $eventId,
                'event_type' => 'payment.' . $status,
                'payload' => $payload,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 6. Catat Jurnal Akuntansi (SIA) jika lunas
            if ($status === 'paid' && $oldStatus !== 'paid') {
                DB::table('journal_entries')->insert([
                    ['order_id' => $order->id, 'account_name' => 'Piutang Gateway', 'debit' => $order->total_amount, 'credit' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['order_id' => $order->id, 'account_name' => 'Pendapatan Penjualan', 'debit' => 0, 'credit' => $order->total_amount, 'created_at' => now(), 'updated_at' => now()]
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Webhook Processed Successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }
}