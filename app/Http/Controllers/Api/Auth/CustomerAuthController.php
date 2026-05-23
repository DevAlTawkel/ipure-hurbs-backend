<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:customers,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', Password::min(8)],
            'dob'      => ['nullable', 'date', 'before:today'],
        ]);

        $customer = Customer::create($data);

        $token = $customer->createToken('customer-app')->plainTextToken;

        return response()->json([
            'customer' => new CustomerResource($customer),
            'token'    => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $customer = Customer::where('email', $data['email'])->first();

        if (! $customer || ! Hash::check($data['password'], $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        $token = $customer->createToken('customer-app')->plainTextToken;

        return response()->json([
            'customer' => new CustomerResource($customer),
            'token'    => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user('customer')->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    public function profile(Request $request): JsonResponse
    {
        $customer = $request->user('customer');
        $customer->load('addresses');

        return response()->json([
            'customer'  => new CustomerResource($customer),
            'addresses' => AddressResource::collection($customer->addresses),
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $customer = $request->user('customer');

        $data = $request->validate([
            'name'  => ['sometimes', 'string', 'max:100'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'dob'   => ['sometimes', 'nullable', 'date', 'before:today'],
        ]);

        $customer->update($data);

        return response()->json(['customer' => new CustomerResource($customer)]);
    }

    public function storeAddress(Request $request): JsonResponse
    {
        $customer = $request->user('customer');

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'phone'      => ['required', 'string', 'max:20'],
            'line1'      => ['required', 'string', 'max:255'],
            'line2'      => ['nullable', 'string', 'max:255'],
            'city'       => ['required', 'string', 'max:100'],
            'state'      => ['required', 'string', 'max:100'],
            'country'    => ['sometimes', 'string', 'size:2'],
            'pincode'    => ['required', 'string', 'max:10'],
            'is_default' => ['boolean'],
        ]);

        if (! empty($data['is_default'])) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $address = $customer->addresses()->create($data);

        return response()->json(['address' => new AddressResource($address)], 201);
    }

    public function deleteAddress(Request $request, int $id): JsonResponse
    {
        $address = $request->user('customer')->addresses()->findOrFail($id);
        $address->delete();

        return response()->json(['message' => 'Address deleted.']);
    }
}
