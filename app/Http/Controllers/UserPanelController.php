<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPanelController extends Controller
{

    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $orderStats = [
            'total_orders' => Order::where('user_id', $user->id)->orWhere('email', $user->email)->count(),
            'pending_orders' => Order::where('user_id', $user->id)->orWhere('email', $user->email)->where('status', 'pending')->count(),
            'completed_orders' => Order::where('user_id', $user->id)->orWhere('email', $user->email)->where('status', 'completed')->count(),
            'total_spent' => Order::where('user_id', $user->id)->orWhere('email', $user->email)->sum('total_amount')
        ];

        return view('user-panel.dashboard', compact('user', 'recentOrders', 'orderStats'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->with('items.productVariant.product', 'items.productVariant.color')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user-panel.orders', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        $user = Auth::user();

        // Ensure user can only view their own orders
        if ($order->user_id !== $user->id && $order->email !== $user->email) {
            abort(403, 'Unauthorized access to order details.');
        }

        $order->load('items.productVariant.product', 'items.productVariant.color', 'items.productVariant.images', 'coupon');

        return view('user-panel.order-details', compact('order'));
    }

    public function addresses()
    {
        $user = Auth::user();
        $addresses = UserAddress::where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user-panel.addresses', compact('addresses'));
    }

    public function createAddress()
    {
        return view('user-panel.address-form');
    }

    public function storeAddress(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'is_default' => 'boolean'
        ]);

        // If this is set as default, unset other defaults
        if ($validated['is_default'] ?? false) {
            UserAddress::where('user_id', $user->id)->update(['is_default' => false]);
        }

        $validated['user_id'] = $user->id;
        UserAddress::create($validated);

        return redirect()->route('user-panel.addresses')
            ->with('success', 'Address added successfully!');
    }

    public function editAddress(UserAddress $address)
    {
        $user = Auth::user();

        if ($address->user_id !== $user->id) {
            abort(403, 'Unauthorized access to address.');
        }

        return view('user-panel.address-form', compact('address'));
    }

    public function updateAddress(Request $request, UserAddress $address)
    {
        $user = Auth::user();

        if ($address->user_id !== $user->id) {
            abort(403, 'Unauthorized access to address.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'is_default' => 'boolean'
        ]);

        // If this is set as default, unset other defaults
        if ($validated['is_default'] ?? false) {
            UserAddress::where('user_id', $user->id)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('user-panel.addresses')
            ->with('success', 'Address updated successfully!');
    }

    public function deleteAddress(UserAddress $address)
    {
        $user = Auth::user();

        if ($address->user_id !== $user->id) {
            abort(403, 'Unauthorized access to address.');
        }

        $address->delete();

        return redirect()->route('user-panel.addresses')
            ->with('success', 'Address deleted successfully!');
    }

    public function setDefaultAddress(UserAddress $address)
    {
        $user = Auth::user();

        if ($address->user_id !== $user->id) {
            abort(403, 'Unauthorized access to address.');
        }

        // Unset other defaults
        UserAddress::where('user_id', $user->id)->update(['is_default' => false]);

        // Set this as default
        $address->update(['is_default' => true]);

        return redirect()->route('user-panel.addresses')
            ->with('success', 'Default address updated successfully!');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user-panel.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->route('user-panel.profile')
            ->with('success', 'Profile updated successfully!');
    }
}
