<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Wedding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class WeddingApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/weddings",
     *     summary="List weddings for current user or all (admin), paginated",
     *     tags={"Weddings"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page (max 50)",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/WeddingBasic")
     *                 ),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="last_page", type="integer")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 15), 50);

        if (Gate::allows('is-admin')) {
            $weddings = Wedding::with('user')->paginate($perPage);
        } else {
            $user = Auth::user();
            $weddings = Wedding::where('user_id', $user->id)->paginate($perPage);
        }

        return $this->success($weddings);
    }

    /**
     * @OA\Post(
     *     path="/weddings",
     *     summary="Create a new wedding for the authenticated user",
     *     tags={"Weddings"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="wedding_date", type="string", format="date"),
     *             @OA\Property(property="location", type="string"),
     *             @OA\Property(property="notes", type="string"),
     *             @OA\Property(property="budget", type="number", format="float"),
     *             @OA\Property(property="guest_count", type="integer"),
     *             @OA\Property(property="status", type="string", enum={"gestionando","confirmada","cancelada","archivada"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Wedding created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/WeddingBasic")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "name": {"The name field is required."},
     *                     "guest_count": {"The guest count must be at least 0."}
     *                 }
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
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
            'status' => 'nullable|string|in:gestionando,confirmada,cancelada,archivada',
        ]);

        $data['user_id'] = Auth::id();

        if (!isset($data['status'])) {
            $data['status'] = 'gestionando';
        }

        $wedding = Wedding::create($data);

        return $this->success($wedding, 'Wedding created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/weddings/{id}",
     *     summary="Get a single wedding",
     *     tags={"Weddings"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Wedding ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Wedding")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wedding not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wedding not found")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function show(int $id)
    {
        $wedding = Wedding::with(['services', 'user'])->find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        if (!Gate::allows('is-admin') && !Gate::allows('owns-model', $wedding)) {
            return $this->error('Forbidden', 403);
        }

        return $this->success($wedding);
    }

    /**
     * @OA\Put(
     *     path="/weddings/{id}",
     *     summary="Update a wedding (owner or admin)",
     *     tags={"Weddings"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Wedding ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="wedding_date", type="string", format="date"),
     *             @OA\Property(property="location", type="string"),
     *             @OA\Property(property="notes", type="string"),
     *             @OA\Property(property="budget", type="number", format="float"),
     *             @OA\Property(property="guest_count", type="integer"),
     *             @OA\Property(property="status", type="string", enum={"gestionando","confirmada","cancelada","archivada"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wedding updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/WeddingBasic")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed or empty payload",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No data provided or invalid JSON"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 nullable=true,
     *                 example={
     *                     "name": {"The name field must be a string."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wedding not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wedding not found")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function update(Request $request, int $id)
    {
        $wedding = Wedding::find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        if (!Gate::allows('is-admin') && !Gate::allows('owns-model', $wedding)) {
            return $this->error('Forbidden', 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'wedding_date' => 'sometimes|date|nullable',
            'location' => 'sometimes|string|max:255|nullable',
            'notes' => 'sometimes|string|nullable',
            'budget' => 'sometimes|numeric|nullable',
            'guest_count' => 'sometimes|integer|min:0|nullable',
            'status' => 'sometimes|string|in:gestionando,confirmada,cancelada,archivada|nullable',
        ]);

        if (empty($data)) {
            return $this->error('No data provided or invalid JSON', 422);
        }

        $wedding->update($data);

        return $this->success($wedding, 'Wedding updated successfully', 200);
    }

    /**
     * @OA\Delete(
     *     path="/weddings/{id}",
     *     summary="Delete a wedding (owner or admin)",
     *     tags={"Weddings"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Wedding ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wedding deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wedding not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wedding not found")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function destroy(int $id)
    {
        $wedding = Wedding::find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        if (!Gate::allows('is-admin') && !Gate::allows('owns-model', $wedding)) {
            return $this->error('Forbidden', 403);
        }

        $wedding->delete();

        return $this->success(null, 'Wedding deleted successfully', 200);
    }

    /**
     * @OA\Get(
     *     path="/weddings/{wedding}/services",
     *     summary="List services attached to a wedding",
     *     tags={"Weddings"},
     *     @OA\Parameter(
     *         name="wedding",
     *         in="path",
     *         description="Wedding ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/WeddingService")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wedding not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wedding not found")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function services(int $id)
    {
        $wedding = Wedding::with('services')->find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        if (!Gate::allows('is-admin') && !Gate::allows('owns-model', $wedding)) {
            return $this->error('Forbidden', 403);
        }

        return $this->success($wedding->services);
    }

    /**
     * @OA\Post(
     *     path="/weddings/{wedding}/services",
     *     summary="Attach a service to a wedding (uses service unit price by default)",
     *     tags={"Weddings"},
     *     @OA\Parameter(
     *         name="wedding",
     *         in="path",
     *         description="Wedding ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"service_id"},
     *             @OA\Property(property="service_id", type="integer"),
     *             @OA\Property(property="price", type="number", format="float", nullable=true, description="Unit price agreed for this wedding; if omitted, the current service price is used"),
     *             @OA\Property(property="quantity", type="integer", nullable=true),
     *             @OA\Property(property="notes", type="string", nullable=true),
     *             @OA\Property(property="status", type="string", enum={"consultado","confirmado","cancelado"}, nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Service attached to wedding successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/ServiceBasic")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wedding not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wedding not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function attachService(Request $request, int $id)
    {
        $wedding = Wedding::find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        if (!Gate::allows('is-admin') && !Gate::allows('owns-model', $wedding)) {
            return $this->error('Forbidden', 403);
        }

        $data = $request->validate([
            'service_id' => 'required|integer|exists:services,id',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|in:consultado,confirmado,cancelado',
        ]);

        $serviceId = $data['service_id'];
        $service = Service::find($serviceId);

        $pivotData = [
            // If price is not provided, use the current service price
            'price' => $data['price'] ?? ($service ? $service->price : null),
            'quantity' => $data['quantity'] ?? 1,
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'] ?? 'consultado',
        ];

        // avoid duplicate because of unique index; update if already attached
        $wedding->services()->syncWithoutDetaching([
            $serviceId => $pivotData,
        ]);

        $service = Service::find($serviceId);

        return $this->success($service, 'Service attached to wedding successfully', 201);
    }

    /**
     * @OA\Put(
     *     path="/weddings/{wedding}/services/{service}",
     *     summary="Update pivot data for a service in a wedding",
     *     tags={"Weddings"},
     *     @OA\Parameter(
     *         name="wedding",
     *         in="path",
     *         description="Wedding ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="service",
     *         in="path",
     *         description="Service ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="price", type="number", format="float", nullable=true),
     *             @OA\Property(property="quantity", type="integer", nullable=true),
     *             @OA\Property(property="notes", type="string", nullable=true),
     *             @OA\Property(property="status", type="string", enum={"consultado","confirmado","cancelado"}, nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wedding service updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/ServiceBasic")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed or empty payload",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No data provided or invalid JSON"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 nullable=true,
     *                 example={
     *                     "price": {"The price field must be a number."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wedding or service not found / not attached",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wedding not found or Service not attached to wedding")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function updateService(Request $request, int $id, int $serviceId)
    {
        $wedding = Wedding::find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        if (!Gate::allows('is-admin') && !Gate::allows('owns-model', $wedding)) {
            return $this->error('Forbidden', 403);
        }

        if (!$wedding->services()->where('service_id', $serviceId)->exists()) {
            return $this->error('Service not attached to wedding', 404);
        }

        $data = $request->validate([
            'price' => 'sometimes|numeric|nullable',
            'quantity' => 'sometimes|integer|min:1|nullable',
            'notes' => 'sometimes|string|nullable',
            'status' => 'sometimes|string|in:consultado,confirmado,cancelado|nullable',
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
     * @OA\Delete(
     *     path="/weddings/{wedding}/services/{service}",
     *     summary="Detach a service from a wedding",
     *     tags={"Weddings"},
     *     @OA\Parameter(
     *         name="wedding",
     *         in="path",
     *         description="Wedding ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="service",
     *         in="path",
     *         description="Service ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service detached from wedding successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wedding not found or service not attached",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wedding not found or Service not attached to wedding")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function detachService(int $id, int $serviceId)
    {
        $wedding = Wedding::find($id);

        if (!$wedding) {
            return $this->error('Wedding not found', 404);
        }

        if (!Gate::allows('is-admin') && !Gate::allows('owns-model', $wedding)) {
            return $this->error('Forbidden', 403);
        }

        if (!$wedding->services()->where('service_id', $serviceId)->exists()) {
            return $this->error('Service not attached to wedding', 404);
        }

        $wedding->services()->detach($serviceId);

        return $this->success(null, 'Service detached from wedding successfully', 200);
    }
}
