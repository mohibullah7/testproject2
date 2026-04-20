<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('created_at','DESC')->paginate(10);

        return view ('products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
      public function store(Request $request)
    {
        // Fixed validation rules
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'detail' => 'nullable|string',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048'
        ]);
        
        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }


            $product = Product::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'detail' => $validated['detail'],
                'image' => $imagePath,  
            ]);

            return redirect()
                ->route('products.index')
                ->with('success', 'Product added successfully!');
                
        } catch(\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to add product: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
     public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show form for editing product.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0|max:99999999.99',
        'detail' => 'nullable|string',
        'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        'remove_image' => 'nullable|boolean',
    ]);
    
    try {
        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image == 1) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        }
        
        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        
        // Remove remove_image from validated data
        unset($validated['remove_image']);
        
        $product->update($validated);
        
        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully!');
            
    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Failed to update product. ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Delete image if exists
            // if ($product->image && Storage::exists('public/' . $product->image)) {
            //     Storage::delete('public/' . $product->image);
            // }
            
            // $product->delete();

            if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
}

            $product->delete();
            
            return redirect()
                ->route('products.index')
                ->with('success', 'Product deleted successfully!');
                
        } catch(\Exception $e) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Failed to delete product.');
        }
    }


   public function buy()
{
    $products = Product::latest()->paginate(12);
    return view('products.buy', compact('products'));
}





public function createPaymentIntent(Request $request)
{
    try {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
            'total_amount' => 'required|numeric|min:0'
        ]);
        
        $product = Product::findOrFail($request->product_id);
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Please login to continue'], 401);
        }
        
        $amount = (int)($request->total_amount * 100);
        
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'metadata' => [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]
        ]);
        
        // Store purchase record
        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'stripe_session_id' => $paymentIntent->id,
            'status' => 'pending',
            'quantity' => $request->quantity,
            'total_amount' => $request->total_amount
        ]);
        
        return response()->json([
            'success' => true,
            'client_secret' => $paymentIntent->client_secret
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Payment Intent Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function confirmPayment(Request $request)
{
    try {
        $request->validate([
            'payment_intent_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required'
        ]);
        
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
        $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
        
        if ($paymentIntent->status === 'succeeded') {
            $purchase = Purchase::where('stripe_session_id', $request->payment_intent_id)->first();
            
            if ($purchase) {
                $purchase->update([
                    'status' => 'completed',
                    'completed_at' => now()
                ]);
            }
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Payment not successful']);
        
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function manage_sales()
{
    // Get all purchases with user and product relationships
    $purchases = Purchase::with(['user', 'product'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
    
    // Get statistics
    $totalSales = Purchase::where('status', 'completed')->sum('total_amount');
    $totalOrders = Purchase::where('status', 'completed')->count();
    $totalCustomers = Purchase::where('status', 'completed')->distinct('user_id')->count('user_id');
    $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
    
    return view('products.manage_sales', compact('purchases', 'totalSales', 'totalOrders', 'totalCustomers', 'averageOrderValue'));
}


public function deleteSale(Request $request)
{
    try {
        $request->validate([
            'sale_id' => 'required|exists:purchases,id'
        ]);
        
        $purchase = Purchase::findOrFail($request->sale_id);
        $purchase->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Sale record deleted successfully'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete sale record'
        ], 500);
    }
}


}
