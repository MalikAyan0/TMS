<?php

namespace App\Http\Controllers;

use App\Models\UserStatus;
use App\Services\UserStatusService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserStatusController extends Controller
{
    protected UserStatusService $userStatusService;

    public function __construct(UserStatusService $userStatusService)
    {
        $this->userStatusService = $userStatusService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        try {
            if ($request->expectsJson()) {
                $statuses = $this->userStatusService->getAllStatuses();

                $formattedStatuses = $statuses->map(function ($status) {
                    return $this->userStatusService->formatStatusForResponse($status);
                });

                return response()->json([
                    'success' => true,
                    'data' => $formattedStatuses,
                    'stats' => $this->userStatusService->getStatusStats(),
                ]);
            }

            // Return view for web interface
            return view('content.pages.system-management.user-status');
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch user statuses',
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Failed to fetch user statuses: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'label' => 'required|string|max:255|unique:user_statuses,label',
                'color' => 'required|string|in:primary,secondary,success,danger,warning,info',
                'description' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $status = $this->userStatusService->createStatus($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'User status created successfully',
                'data' => $this->userStatusService->formatStatusForResponse($status),
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $status = $this->userStatusService->getStatusById($id);

            if (!$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'User status not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->userStatusService->formatStatusForResponse($status),
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'label' => 'required|string|max:255|unique:user_statuses,label,' . $id,
                'color' => 'required|string|in:primary,secondary,success,danger,warning,info',
                'description' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $status = $this->userStatusService->updateStatus($id, $validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully',
                'data' => $this->userStatusService->formatStatusForResponse($status),
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->userStatusService->deleteStatus($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete user status',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'User status deleted successfully',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggle(int $id): JsonResponse
    {
        try {
            $status = $this->userStatusService->toggleStatus($id);

            return response()->json([
                'success' => true,
                'message' => "User status {$status->status} successfully",
                'data' => $this->userStatusService->formatStatusForResponse($status),
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle user status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get active user statuses
     */
    public function active(): JsonResponse
    {
        try {
            $statuses = $this->userStatusService->getActiveStatuses();

            $formattedStatuses = $statuses->map(function ($status) {
                return $this->userStatusService->formatStatusForResponse($status);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedStatuses,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active user statuses',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search user statuses
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'required|string|min:1|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $statuses = $this->userStatusService->searchStatuses($request->input('search'));

            $formattedStatuses = $statuses->map(function ($status) {
                return $this->userStatusService->formatStatusForResponse($status);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedStatuses,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search user statuses',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user status statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = $this->userStatusService->getStatusStats();

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user status statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk operations on user statuses
     */
    public function bulk(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|string|in:activate,deactivate,delete',
                'ids' => 'required|array|min:1',
                'ids.*' => 'required|integer|exists:user_statuses,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $action = $request->input('action');
            $ids = $request->input('ids');

            switch ($action) {
                case 'activate':
                    $updated = $this->userStatusService->bulkUpdateStatuses($ids, ['status' => 'active']);
                    break;
                case 'deactivate':
                    $updated = $this->userStatusService->bulkUpdateStatuses($ids, ['status' => 'inactive']);
                    break;
                case 'delete':
                    $updated = 0;
                    foreach ($ids as $id) {
                        if ($this->userStatusService->deleteStatus($id)) {
                            $updated++;
                        }
                    }
                    break;
                default:
                    throw new Exception('Invalid bulk action');
            }

            return response()->json([
                'success' => true,
                'message' => "Bulk {$action} completed successfully",
                'updated_count' => $updated,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk operation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get color options
     */
    public function colors(): JsonResponse
    {
        try {
            $colors = $this->userStatusService->getColorOptions();

            return response()->json([
                'success' => true,
                'data' => $colors,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch color options',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
