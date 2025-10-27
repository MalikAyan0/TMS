<?php

namespace App\Http\Controllers;

use App\Models\NatureOfAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NatureOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $accounts = NatureOfAccount::orderBy('code', 'asc')->get();
            if (request()->wantsJson()) {
              return response()->json([
                'success' => true,
                'data' => $accounts
              ]);
            }
            return view('content.pages.system-management.nature-of-accounts', compact('accounts'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve accounts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'code' => 'required|integer|min:1000|max:9999|unique:nature_of_accounts,code',
                'type' => 'required|in:asset,liability,equity,revenue,expense',
                'description' => 'nullable|string|max:1000',
            ]);

            $account = NatureOfAccount::create($validated);

            return response()->json([
                'success' => true,
                'data' => $account,
                'message' => 'Account created successfully!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(NatureOfAccount $natureOfAccount): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $natureOfAccount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NatureOfAccount $natureOfAccount): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'code' => 'required|integer|min:1000|max:9999|unique:nature_of_accounts,code,' . $natureOfAccount->id,
                'type' => 'required|in:asset,liability,equity,revenue,expense',
                'description' => 'nullable|string|max:1000',
            ]);

            $natureOfAccount->update($validated);

            return response()->json([
                'success' => true,
                'data' => $natureOfAccount->fresh(),
                'message' => 'Account updated successfully!'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(NatureOfAccount $natureOfAccount): JsonResponse
    {
        try {
            $newStatus = $natureOfAccount->status === 'active' ? 'inactive' : 'active';
            $natureOfAccount->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'data' => $natureOfAccount->fresh(),
                'message' => "Account {$newStatus} successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle account status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NatureOfAccount $natureOfAccount): JsonResponse
    {
        try {
            $natureOfAccount->delete();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
