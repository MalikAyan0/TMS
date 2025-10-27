<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $banks = Bank::orderBy('created_at', 'desc')->get();
          if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $banks
                ]);
            }
          return view('content.pages.system-management.banks', compact('banks'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch banks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.pages.system-management.banks');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:banks,name',
            'short_name' => 'required|string|max:10|unique:banks,short_name',
            'address' => 'required|string',
            'contact' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:banks,email',
            'contact_person' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $bank = Bank::create([
                'name' => $request->name,
                'short_name' => $request->short_name,
                'address' => $request->address,
                'contact' => $request->contact,
                'email' => $request->email,
                'contact_person' => $request->contact_person,
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank created successfully',
                'data' => $bank
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $bank
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bank not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        return response()->json([
            'success' => true,
            'data' => $bank
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bank $bank)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('banks')->ignore($bank->id)],
            'short_name' => ['required', 'string', 'max:10', Rule::unique('banks')->ignore($bank->id)],
            'address' => 'required|string',
            'contact' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('banks')->ignore($bank->id)],
            'contact_person' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $bank->update([
                'name' => $request->name,
                'short_name' => $request->short_name,
                'address' => $request->address,
                'contact' => $request->contact,
                'email' => $request->email,
                'contact_person' => $request->contact_person,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank updated successfully',
                'data' => $bank->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle bank status.
     */
    public function toggleStatus(Bank $bank)
    {
        try {
            $bank->status = $bank->status === 'active' ? 'inactive' : 'active';
            $bank->save();

            return response()->json([
                'success' => true,
                'message' => 'Bank status updated successfully',
                'data' => $bank
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update bank status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        try {
            $bank->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bank deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search banks.
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $banks = Bank::search($query)->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $banks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active banks.
     */
    public function getActive()
    {
        try {
            $banks = Bank::active()->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'data' => $banks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active banks',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
