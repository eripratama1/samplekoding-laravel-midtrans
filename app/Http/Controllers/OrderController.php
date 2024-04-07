<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');
    }

    public function index()
    {
        return view('order', ['orders' => Order::latest()->get()]);
    }

    public function payment(Request $request)
    {
        // Generate Nomor Transaksi Acak
        $no_transaction = 'Trx-' . Str::upper(mt_rand(100000, 999999));

        /**
         * Buat Objek Order dan Isi Propertinya sesuai dengan tabel orders
         */
        $order = new Order;
        $order->no_transaction = $no_transaction;
        $order->name = $request->input('name');
        $order->qty = $request->input('qty');
        $order->price = $request->input('price');
        $order->grand_total = $request->input('grand_total');

        /**
         * Persiapkan object Data Transaksi
         * Object transaction details merupakan data array yang diperlukan midtrans
         * agar bisa memproses payment nantinya
         */
        $transaction_details = array(
            'order_id' => $order->no_transaction,
            'gross_amount' => $order->grand_total
        );

        /**
         * Object item details berisikan informasi tentang item barang yang diorder
         * baik qty,price maupun data lainnya.
         * Item details ini bersifat opsional bisa kalian tambahkan maupun tidak
         */
        $item_details = [
            array(
                'id' => $order->no_transaction,
                'price' => $order->price,
                'quantity' => $order->qty,
                'name' => 'Item -' . $order->name
            )
        ];

        /**
         * Object customer details berisikan informasi
         * data customer. data ini juga bersifat opsional
         */
        $customer_details = array(
            'first_name' => $order->name
        );

        /**
         * Membuat object tampungan data dengan nama $transaction_data
         * yang berisikan data dari masing-masing object berikut
         * (transaction_details,item_details & customer_details)
         */
        $transaction_data = array(
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details
        );

        try {
            /** Generate snap_token untuk proses payment yang daranya didapat dari object transaction_data */
            $snapToken = Snap::getSnapToken($transaction_data);
            $order->snap_token = $snapToken;
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $order->save();
        return to_route('order');
    }

    public function notificationHandler(Request $request)
    {
        /**
         * Dapatkan isi notifikasi dari request
         */
        $payload = $request->getContent();

        /**
         * Dapatkan isi dari request payload
         */
        $notification = json_decode($payload);

        /**
         * Ekstrak data notifikasi
         */
        $transaction_status = $notification->transaction_status;
        $payment_type = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraud = $notification->fraud_status;

        /**
         * Cari data transaksi (no_transactin) berdasarkan orderId dari request
         *
         */
        $data = Order::where('no_transaction', $orderId)->first();

        /**
         * Perbarui status pesanan berdasarkan status yang didapat dari callback
         */
        if ($transaction_status == 'capture') {
            if ($payment_type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $data->update([
                        'status' => 'pending'
                    ]);
                } else {
                    $data->update([
                        'status' => 'settlement'
                    ]);
                }
            }
        } elseif ($transaction_status == 'settlement') {
            $data->update([
                'status' => 'settlement'
            ]);
        } elseif ($transaction_status == 'pending') {
            $data->update([
                'status' => 'pending'
            ]);
        } elseif ($transaction_status == 'deny') {
            $data->update([
                'status' => 'denied'
            ]);
        } elseif ($transaction_status == 'settlement') {
            $data->update([
                'status' => 'settlement'
            ]);
        } elseif ($transaction_status == 'expire') {
            $data->update([
                'status' => 'expired'
            ]);
        } elseif ($transaction_status == 'cancel') {
            $data->update([
                'status' => 'canceled'
            ]);
        }
    }
}
