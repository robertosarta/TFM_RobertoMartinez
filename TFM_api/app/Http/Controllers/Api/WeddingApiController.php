<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Wedding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeddingApiController extends Controller
{
    /**
     * Display a listing of weddings.
     *
     * - Admin: sees all weddings.
     * - Normal user: sees only their own weddings.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $weddings = Wedding::with('user')->get();
        } else {
            $weddings = Wedding::where('user_id', $user->id)->get();
        }

        return $this->success($weddings);
    }

    /**
     * Store a newly created wedding for the authenticated user.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'wedding_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'guest_count' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:draft,confirmed,cancelled,archived',
        ]);

        $data['user_id'] = Auth::id();

        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        $wedding = Wedding::create($data);

        return $this->success($wedding, 'Wedding created successfully', 201);
    }

    /**
     * Display the specified wedding.
     */
    public function show(int $id)
    {
        $wedding = Wedding::with('services')->find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        $user = Auth::user();

        if ($user->role !== 'admin' && $wedding->user_id !== $user->id) {
            return $this->error('Forbidden', 403);
        }

        return $this->success($wedding);
    }

    /**
     * Update the specified wedding.
     */
    public function update(Request $request, int $id)
    {
        $wedding = Wedding::find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        $user = Auth::user();

        if ($user->role !== 'admin' && $wedding->user_id !== $user->id) {
            return $this->error('Forbidden', 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'wedding_date' => 'sometimes|date|nullable',
            'location' => 'sometimes|string|max:255|nullable',
            'notes' => 'sometimes|string|nullable',
            'budget' => 'sometimes|numeric|nullable',
            'guest_count' => 'sometimes|integer|min:0|nullable',
            'status' => 'sometimes|string|in:draft,confirmed,cancelled,archived|nullable',
        ]);

        if (empty($data)) {
            return $this->error('No data provided or invalid JSON', 422);
        }

        $wedding->update($data);

        return $this->success($wedding, 'Wedding updated successfully', 200);
    }

    /**
     * Remove the specified wedding from storage.
     */
    public function destroy(int $id)
    {
        $wedding = Wedding::find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        $user = Auth::user();

        if ($user->role !== 'admin' && $wedding->user_id !== $user->id) {
            return $this->error('Forbidden', 403);
        }

        $wedding->delete();

        return $this->success(null, 'Wedding deleted successfully', 200);
    }

    /**
     * List services attached to a wedding.
     */
    public function services(int $id)
    {
        $wedding = Wedding::with('services')->find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        $user = Auth::user();

        if ($user->role !== 'admin' && $wedding->user_id !== $user->id) {
            return $this->error('Forbidden', 403);
        }

        return $this->success($wedding->services);
    }

    /**
     * Attach a service to a wedding with optional pivot data.
     */
    public function attachService(Request $request, int $id)
    {
        $wedding = Wedding::find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        $user = Auth::user();

        if ($user->role !== 'admin' && $wedding->user_id !== $user->id) {
            return $this->error('Forbidden', 403);
        }

        $data = $request->validate([
            'service_id' => 'required|integer|exists:services,id',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|in:pending,confirmed,cancelled',
        ]);

        $serviceId = $data['service_id'];

        $pivotData = [
            'price' => $data['price'] ?? null,
            'quantity' => $data['quantity'] ?? 1,
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'] ?? 'pending',
        ];

        // avoid duplicate because of unique index; update if already attached
        $wedding->services()->syncWithoutDetaching([
            $serviceId => $pivotData,
        ]);

        $service = Service::find($serviceId);

        return $this->success($service, 'Service attached to wedding successfully', 201);
    }

    /**
     * Update pivot data for a service in a wedding.
     */
    public function updateService(Request $request, int $id, int $serviceId)
    {
        $wedding = Wedding::find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        $user = Auth::user();

        if ($user->role !== 'admin' && $wedding->user_id !== $user->id) {
            return $this->error('Forbidden', 403);
        }

        if (!$wedding->services()->where('service_id', $serviceId)->exists()) {
            return $this->error('Service not attached to wedding', 404);
        }

        $data = $request->validate([
            'price' => 'sometimes|numeric|nullable',
            'quantity' => 'sometimes|integer|min:1|nullable',
            'notes' => 'sometimes|string|nullable',
            'status' => 'sometimes|string|in:pending,confirmed,cancelled|nullable',
        ]);

        if (empty($data)) {
            return $this->error('No data provided or invalid JSON', 422);
        }

        $pivotData = array_filter([
            'price' => $data['price'] ?? null,
            'quantity' => $data['quantity'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'] ?? null,
        ], function ($value) {
            return !is_null($value);
        });

        $wedding->services()->updateExistingPivot($serviceId, $pivotData);

        $service = Service::find($serviceId);

        return $this->success($service, 'Wedding service updated successfully', 200);
    }

    /**
     * Detach a service from a wedding.
     */
    public function detachService(int $id, int $serviceId)
    {
        $wedding = Wedding::find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        $user = Auth::user();

        if ($user->role !== 'admin' && $wedding->user_id !== $user->id) {
            return $this->error('Forbidden', 403);
        }

        if (!$wedding->services()->where('service_id', $serviceId)->exists()) {
            return $this->error('Service not attached to wedding', 404);
        }

        $wedding->services()->detach($serviceId);

        return $this->success(null, 'Service detached from wedding successfully', 200);
    }
}
