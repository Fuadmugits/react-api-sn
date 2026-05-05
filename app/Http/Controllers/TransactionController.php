<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Transaction::with('details.product', 'customer')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $totalAmount = 0;
            $transactionItems = [];

            // Prepare items and calculate total
            foreach ($validated['items'] as $item) {
                $product = \App\Models\Product::lockForUpdate()->find($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $transactionItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];

                // Deduct stock
                $product->stock -= $item['quantity'];
                $product->save();
            }

            // Create Transaction
            $transaction = Transaction::create([
                'customer_id' => $validated['customer_id'] ?? null,
                'transaction_date' => now(),
                'total_amount' => $totalAmount,
            ]);

            // Create Transaction Details
            foreach ($transactionItems as $item) {
                $transaction->details()->create($item);
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json($transaction->load('details.product', 'customer'), 201);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return response()->json($transaction->load('details.product', 'customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        return response()->json(['message' => 'Transactions cannot be updated directly.'], 403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        return response()->json(['message' => 'Transactions cannot be deleted directly.'], 403);
    }
}
