<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('content.pages.system-management.parties');
    }

    /**
     * Get all parties via API.
     */
    public function apiIndex(Request $request)
    {
        try {
            $parties = Party::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $parties,
                'message' => 'Parties retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve parties: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get parties by type via API.
     */
    public function apiByType(Request $request, $type)
    {
        try {
            $validTypes = ['customer', 'vendor'];

            if (!in_array($type, $validTypes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid party type'
                ], 400);
            }

            $parties = Party::ofType($type)->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $parties,
                'message' => ucfirst($type) . 's retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve ' . $type . 's: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'party_type' => 'required|in:customer,vendor',
                'title' => 'required|string|max:255|unique:parties,title',
                'short_name' => 'required|string|max:15|unique:parties,short_name',
                'address' => 'required|string',
                'contact' => 'required|string|max:255',
                'email' => 'required|email|unique:parties,email',
                'contact_person' => 'required|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'iban' => 'nullable|string|max:255',
                'ntn' => 'required|string|max:255|unique:parties,ntn',
            ], [
                'party_type.required' => 'Party type is required',
                'party_type.in' => 'Party type must be customer or vendor',
                'title.required' => 'Title is required',
                'title.unique' => 'This title already exists',
                'short_name.required' => 'Short name is required',
                'short_name.max' => 'Short name must not exceed 15 characters',
                'short_name.unique' => 'This short name already exists',
                'address.required' => 'Address is required',
                'contact.required' => 'Contact is required',
                'email.required' => 'Email is required',
                'email.email' => 'Please provide a valid email address',
                'email.unique' => 'This email already exists',
                'contact_person.required' => 'Contact person is required',
                'ntn.required' => 'NTN is required',
                'ntn.unique' => 'This NTN already exists',
            ]);

            $party = Party::create($validatedData);

            return response()->json([
                'success' => true,
                'data' => $party,
                'message' => ucfirst($party->party_type) . ' added successfully!'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add party: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Party $party)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $party,
                'message' => 'Party retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve party: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Party $party)
    {
        try {
            $validatedData = $request->validate([
                'party_type' => 'required|in:customer,vendor',
                'title' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('parties', 'title')->ignore($party->id)
                ],
                'short_name' => [
                    'required',
                    'string',
                    'max:15',
                    Rule::unique('parties', 'short_name')->ignore($party->id)
                ],
                'address' => 'required|string',
                'contact' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('parties', 'email')->ignore($party->id)
                ],
                'contact_person' => 'required|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'iban' => 'nullable|string|max:255',
                'ntn' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('parties', 'ntn')->ignore($party->id)
                ],
            ], [
                'party_type.required' => 'Party type is required',
                'party_type.in' => 'Party type must be customer or vendor',
                'title.required' => 'Title is required',
                'title.unique' => 'This title already exists',
                'short_name.required' => 'Short name is required',
                'short_name.max' => 'Short name must not exceed 15 characters',
                'short_name.unique' => 'This short name already exists',
                'address.required' => 'Address is required',
                'contact.required' => 'Contact is required',
                'email.required' => 'Email is required',
                'email.email' => 'Please provide a valid email address',
                'email.unique' => 'This email already exists',
                'contact_person.required' => 'Contact person is required',
                'ntn.required' => 'NTN is required',
                'ntn.unique' => 'This NTN already exists',
            ]);

            $party->update($validatedData);

            return response()->json([
                'success' => true,
                'data' => $party->fresh(),
                'message' => ucfirst($party->party_type) . ' updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update party: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle party status.
     */
    public function toggleStatus(Party $party)
    {
        try {
            $newStatus = $party->status === 'active' ? 'inactive' : 'active';
            $party->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'data' => $party->fresh(),
                'message' => ucfirst($party->party_type) . ' ' . ($newStatus === 'active' ? 'activated' : 'deactivated') . ' successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle party status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Party $party)
    {
        try {
            $partyType = $party->party_type;
            $party->delete();

            return response()->json([
                'success' => true,
                'message' => ucfirst($partyType) . ' deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete party: ' . $e->getMessage()
            ], 500);
        }
    }
}
