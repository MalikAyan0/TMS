<?php

namespace App\Services;

use App\Models\UserStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class UserStatusService
{
    /**
     * Get all user statuses
     *
     * @return Collection
     */
    public function getAllStatuses(): Collection
    {
        return UserStatus::with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get active user statuses only
     *
     * @return Collection
     */
    public function getActiveStatuses(): Collection
    {
        return UserStatus::active()
            ->with(['creator', 'updater'])
            ->orderBy('label', 'asc')
            ->get();
    }

    /**
     * Get user status by ID
     *
     * @param int $id
     * @return UserStatus|null
     */
    public function getStatusById(int $id): ?UserStatus
    {
        return UserStatus::with(['creator', 'updater'])->find($id);
    }

    /**
     * Create a new user status
     *
     * @param array $data
     * @return UserStatus
     * @throws Exception
     */
    public function createStatus(array $data): UserStatus
    {
        try {
            DB::beginTransaction();

            // Validate unique label
            if (UserStatus::where('label', $data['label'])->exists()) {
                throw new Exception('Status label already exists');
            }

            $status = UserStatus::create([
                'label' => $data['label'],
                'color' => $data['color'] ?? 'primary',
                'description' => $data['description'] ?? null,
                'status' => 'active',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return $status->load(['creator', 'updater']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update user status
     *
     * @param int $id
     * @param array $data
     * @return UserStatus
     * @throws Exception
     */
    public function updateStatus(int $id, array $data): UserStatus
    {
        try {
            DB::beginTransaction();

            $status = UserStatus::findOrFail($id);

            // Validate unique label (exclude current record)
            if (UserStatus::where('label', $data['label'])->where('id', '!=', $id)->exists()) {
                throw new Exception('Status label already exists');
            }

            $status->update([
                'label' => $data['label'],
                'color' => $data['color'] ?? $status->color,
                'description' => $data['description'] ?? $status->description,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return $status->load(['creator', 'updater']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Toggle user status (active/inactive)
     *
     * @param int $id
     * @return UserStatus
     * @throws Exception
     */
    public function toggleStatus(int $id): UserStatus
    {
        try {
            DB::beginTransaction();

            $status = UserStatus::findOrFail($id);
            $status->toggleStatus();

            if (Auth::check()) {
                $status->updated_by = Auth::id();
                $status->save();
            }

            DB::commit();

            return $status->load(['creator', 'updater']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Soft delete user status
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteStatus(int $id): bool
    {
        try {
            DB::beginTransaction();

            $status = UserStatus::findOrFail($id);

            // Check if status is being used by any users
            if ($status->users()->exists()) {
                throw new Exception('Cannot delete status that is assigned to users');
            }

            if (Auth::check()) {
                $status->updated_by = Auth::id();
                $status->save();
            }

            $deleted = $status->delete();

            DB::commit();

            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Restore soft deleted user status
     *
     * @param int $id
     * @return UserStatus
     * @throws Exception
     */
    public function restoreStatus(int $id): UserStatus
    {
        try {
            DB::beginTransaction();

            $status = UserStatus::withTrashed()->findOrFail($id);
            $status->restore();

            if (Auth::check()) {
                $status->updated_by = Auth::id();
                $status->save();
            }

            DB::commit();

            return $status->load(['creator', 'updater']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get user status statistics
     *
     * @return array
     */
    public function getStatusStats(): array
    {
        return [
            'total' => UserStatus::count(),
            'active' => UserStatus::active()->count(),
            'inactive' => UserStatus::inactive()->count(),
            'deleted' => UserStatus::onlyTrashed()->count(),
        ];
    }

    /**
     * Search user statuses
     *
     * @param string $search
     * @return Collection
     */
    public function searchStatuses(string $search): Collection
    {
        return UserStatus::where(function ($query) use ($search) {
            $query->where('label', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        })
        ->with(['creator', 'updater'])
        ->orderBy('label', 'asc')
        ->get();
    }

    /**
     * Get color options for status
     *
     * @return array
     */
    public function getColorOptions(): array
    {
        return UserStatus::getColorOptions();
    }

    /**
     * Format status for API response
     *
     * @param UserStatus $status
     * @return array
     */
    public function formatStatusForResponse(UserStatus $status): array
    {
        return [
            'id' => $status->id,
            'label' => $status->label,
            'color' => $status->color,
            'description' => $status->description,
            'status' => $status->status,
            'is_active' => $status->isActive(),
            'created_at' => $status->created_at?->format('Y-m-d'),
            'updated_at' => $status->updated_at?->format('Y-m-d'),
            'creator' => $status->creator ? [
                'id' => $status->creator->id,
                'name' => $status->creator->name,
            ] : null,
            'updater' => $status->updater ? [
                'id' => $status->updater->id,
                'name' => $status->updater->name,
            ] : null,
        ];
    }

    /**
     * Bulk update status records
     *
     * @param array $statusIds
     * @param array $data
     * @return int Number of updated records
     * @throws Exception
     */
    public function bulkUpdateStatuses(array $statusIds, array $data): int
    {
        try {
            DB::beginTransaction();

            $updateData = [];
            if (isset($data['status'])) {
                $updateData['status'] = $data['status'];
            }
            if (isset($data['color'])) {
                $updateData['color'] = $data['color'];
            }
            if (Auth::check()) {
                $updateData['updated_by'] = Auth::id();
                $updateData['updated_at'] = now();
            }

            $updated = UserStatus::whereIn('id', $statusIds)->update($updateData);

            DB::commit();

            return $updated;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
