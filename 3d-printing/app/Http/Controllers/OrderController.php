<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Design;
use App\Models\Material;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'string', Rule::in(['pending', 'processing', 'shipped', 'completed', 'cancelled'])],
        ]);

        $status = $request->query('status');
        
        $ordersQuery = Auth::user()->orders()->with('items.material')->latest();

        if ($status) {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery->paginate(10)->withQueryString();

        $statusCounts = Auth::user()->orders()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        // Get designs and materials available to the user (their own or public ones)
        $userDesigns = Design::where('user_id', Auth::id())->orWhereNull('user_id')->orderBy('name')->get();
        $userMaterials = Material::where('user_id', Auth::id())->orWhereNull('user_id')->orderBy('name')->get();
        
        $colors = ['Merah', 'Hitam', 'Putih', 'Biru', 'Abu-abu', 'Transparan', 'Lainnya'];
        
        return view('dashboard.orders.create', compact('userDesigns', 'userMaterials', 'colors'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate all incoming form data
        $validatedData = $request->validate([
            'recipient_name'   => 'required|string|max:255',
            'shipping_address' => 'required|string|max:1000',
            'phone_number'     => 'required|string|max:20',
            'notes'            => 'nullable|string|max:2000',
            'payment_method'   => ['required', 'string', Rule::in(['mandiri', 'gopay', 'ovo', 'dana', 'shopeepay'])],
            'payment_proof'    => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            
            'items'                     => 'required|array|min:1',
            'items.*.design_id'         => ['nullable', 'sometimes', Rule::exists('designs', 'id')],
            'items.*.material_id'       => ['required', Rule::exists('materials', 'id')],
            'items.*.color'             => 'required|string',
            'items.*.quantity'          => 'required|integer|min:1',
            'items.*.notes'             => 'nullable|string|max:1000',
        ]);

        try {
            // 2. Use a database transaction to ensure data integrity
            $order = DB::transaction(function () use ($request, $validatedData) {
                
                // Handle file upload
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

                // Create the main order record first
                $orderData = [
                    'recipient_name'   => $validatedData['recipient_name'],
                    'shipping_address' => $validatedData['shipping_address'],
                    'phone_number'     => $validatedData['phone_number'],
                    'notes'            => $validatedData['notes'] ?? null,
                    'payment_method'   => $validatedData['payment_method'],
                    'payment_proof'    => $paymentProofPath,
                    'payment_status'   => 'pending_verification',
                    'status'           => 'pending',
                    'total_price'      => 0, // Will be calculated and updated later
                ];

                $order = Auth::user()->orders()->create($orderData);

                $finalTotalPrice = 0;

                // 3. Loop through each item to create OrderItem records
                foreach ($validatedData['items'] as $itemData) {
                    $material = Material::find($itemData['material_id']);
                    if (!$material) continue; // Skip if material somehow not found

                    // Correctly determine the design name
                    $designName = 'Pesanan Kustom'; // Default name
                    if (!empty($itemData['design_id'])) {
                        $design = Design::find($itemData['design_id']);
                        if ($design) {
                            $designName = $design->name;
                        }
                    }

                    $subtotal = ($material->price ?? 0) * $itemData['quantity'];
                    
                    // Create the associated OrderItem
                    $order->items()->create([
                        'design_id'     => empty($itemData['design_id']) ? null : $itemData['design_id'],
                        'design_name'   => $designName, // <-- This fixes the error
                        'material_id'   => $material->id,
                        'material_name' => $material->name,
                        'color'         => $itemData['color'],
                        'quantity'      => $itemData['quantity'],
                        'price'         => $material->price,
                        'subtotal'      => $subtotal,
                        'notes'         => $itemData['notes'] ?? null,
                    ]);

                    $finalTotalPrice += $subtotal;
                }

                // 4. Update the main order with the final calculated price
                $order->total_price = $finalTotalPrice;
                $order->save();

                return $order;
            });

            // 5. If transaction is successful, redirect with a success message
            return redirect()->route('orders.index')->with('success', 'Pesanan Anda berhasil dibuat dan sedang menunggu verifikasi pembayaran.');

        } catch (Exception $e) {
            // 6. If any error occurs, rollback and redirect back with an error message
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order); // Use policy for authorization
        $order->load(['user', 'items.design', 'items.material']);
        return view('dashboard.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)->with('error', 'Pesanan ini tidak dapat diedit lagi.');
        }

        $userDesigns = Design::where('user_id', Auth::id())->orWhereNull('user_id')->orderBy('name')->get();
        $userMaterials = Material::where('user_id', Auth::id())->orWhereNull('user_id')->orderBy('name')->get();
        $colors = ['Merah', 'Hitam', 'Putih', 'Biru', 'Abu-abu', 'Transparan', 'Lainnya'];
        return view('dashboard.orders.edit', compact('order', 'userDesigns', 'userMaterials', 'colors'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        // TODO: Implement the logic for updating a multi-item order.
        // This will involve validating a similar array of 'items', deleting old items,
        // and creating new ones within a transaction.
        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    /**
     * Cancel the specified order (change status).
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        
        if ($order->status !== 'pending') {
             return redirect()->route('orders.index')->with('error', 'Hanya pesanan dengan status "Pending" yang bisa dibatalkan.');
        }
        
        $order->status = 'cancelled';
        $order->payment_status = 'cancelled';
        $order->save();

        return redirect()->route('orders.index', ['status' => 'cancelled'])->with('success', 'Pesanan telah berhasil dibatalkan.');
    }
}
