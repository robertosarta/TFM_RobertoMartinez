<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ServiceApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/services",
     *     summary="List all services (paginated)",
     *     tags={"Services"},
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
     *                     @OA\Items(ref="#/components/schemas/ServiceBasic")
     *                 ),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="last_page", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 15), 50);
        $services = Service::with(['images' => function ($query) {
            // Traemos solo la imagen principal (o la primera) para aligerar la respuesta
            $query->orderByDesc('is_primary')->orderBy('sort_order')->limit(1);
        }])->paginate($perPage);
        return $this->success($services, 200);
    }

    /**
     * @OA\Get(
     *     path="/services/{id}/images",
     *     summary="List images for a service",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service ID",
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
     *                 @OA\Items(ref="#/components/schemas/ServiceImageBasic")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Service not found")
     *         )
     *     )
     * )
     */
    public function images(int $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return $this->error('Service not found', 404);
        }

        $images = $service->images()->orderBy('sort_order')->get();

        return $this->success($images);
    }

    /**
     * @OA\Post(
     *     path="/services/{id}/images",
     *     summary="Add an image to a service (business owner or admin)",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"image"},
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     description="Image file to upload"
     *                 ),
     *                 @OA\Property(
     *                     property="url",
     *                     type="string",
     *                     nullable=true,
     *                     description="Optional existing URL instead of uploading a file"
     *                 ),
     *                 @OA\Property(property="caption", type="string", nullable=true),
     *                 @OA\Property(property="is_primary", type="boolean", nullable=true),
     *                 @OA\Property(property="sort_order", type="integer", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Image added successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/ServiceImageBasic")
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
     *         description="Service not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Service not found")
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
    public function addImage(Request $request, int $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return $this->error('Service not found', 404);
        }

        if (!Gate::allows('is-admin') && (!Gate::allows('is-business') || !Gate::allows('owns-model', $service))) {
            return $this->error('Forbidden', 403);
        }

        $data = $request->validate([
            'image' => 'nullable|image|max:5120', // max 5MB
            'url' => 'nullable|string|max:2048',
            'caption' => 'nullable|string|max:255',
            // Accept common boolean representations (true/false, 1/0, on/off)
            'is_primary' => 'nullable|in:true,false,1,0,on,off',
            'sort_order' => 'nullable|integer',
        ]);

        if (!$request->hasFile('image') && empty($data['url'])) {
            return $this->error('Either image file or url is required', 422);
        }

        if ($request->hasFile('image')) {
            // Store in storage/app/public/services and expose via /storage/services/...
            $path = $request->file('image')->store('services', 'public');
            $url = asset('storage/' . $path);
        } else {
            $url = $data['url'];
        }

        $isPrimary = isset($data['is_primary'])
            ? filter_var($data['is_primary'], FILTER_VALIDATE_BOOLEAN)
            : false;

        if ($isPrimary) {
            $service->images()->update(['is_primary' => false]);
        }

        $image = $service->images()->create([
            'url' => $url,
            'caption' => $data['caption'] ?? null,
            'is_primary' => $isPrimary,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return $this->success($image, 'Image added successfully', 201);
    }

    /**
     * @OA\Post(
     *     path="/services",
     *     summary="Create a new service (business or admin)",
     *     tags={"Services"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "price"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(
     *                 property="address",
     *                 type="object",
     *                 @OA\Property(property="street", type="string"),
     *                 @OA\Property(property="city", type="string"),
     *                 @OA\Property(property="zip", type="string"),
     *             ),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="string"),
     *             @OA\Property(property="subcategory_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Service created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/ServiceBasic")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object", example={
     *                 "name": {"The name field is required."},
     *                 "price": {"The price field must be a number."}
     *             })
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function store(Request $request)
    {
        if (!Gate::allows('is-admin') && !Gate::allows('is-business')) {
            return $this->error('Forbidden', 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|array',
            'address.street' => 'nullable|string|max:255',
            'address.city' => 'nullable|string|max:255',
            'address.zip' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'subcategory_id' => 'nullable|integer|exists:subcategories,id'
        ]);

        $service = Service::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'description' => $request->description,
            'price' => $request->price,
            'user_id' => Auth::id(),
            'subcategory_id' => $request->subcategory_id
        ]);
        return $this->success($service, 'Service created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/services/{id}",
     *     summary="Get a single service",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service ID",
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
     *             @OA\Property(property="data", ref="#/components/schemas/ServiceBasic")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Service not found")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        $service = Service::find($id);
        if (!$service) {
            return $this->error('Service not found', 404);
        }
        return $this->success($service, 200);
    }

    /**
     * @OA\Put(
     *     path="/services/{id}",
     *     summary="Update a service (owner or admin)",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(
     *                 property="address",
     *                 type="object",
     *                 @OA\Property(property="street", type="string"),
     *                 @OA\Property(property="city", type="string"),
     *                 @OA\Property(property="zip", type="string"),
     *             ),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="string"),
     *             @OA\Property(property="subcategory_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service updated successfully",
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
     *             @OA\Property(property="errors", type="object", nullable=true, example={"name": {"The name field must be a string."}})
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
     *     @OA\Response(response=404, description="Service not found"),
     *     security={{"sanctum": {}}}
     * )
     */
    public function update(Request $request, int $id)
    {
        $service = Service::find($id);

        if(!$service) {
            return $this->error('Service not found', 404);
        }
        
        // Verificamos que el usuario autenticado es el propietario del servicio y business, o un admin
        if (!Gate::allows('is-admin') && (!Gate::allows('is-business') || !Gate::allows('owns-model', $service))) {
            return $this->error('Forbidden', 403);
        }
        
        // Validamos solo los campos que se envian en el request
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|email',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|array',
            'address.street' => 'sometimes|string|max:255',
            'address.city' => 'sometimes|string|max:255',
            'address.zip' => 'sometimes|string|max:20',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'subcategory_id' => 'sometimes|integer|exists:subcategories,id'
        ]);

        // Evitamos respuestas 200 sin cambios cuando el JSON esta vacio
        if (empty($data)) {
            return $this->error('No data provided or invalid JSON', 422);
        }

        $service->update($data);
        return $this->success($service, 'Service updated successfully', 200);
    }

    /**
     * @OA\Delete(
     *     path="/services/{id}",
     *     summary="Delete a service (owner or admin)",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service deleted successfully",
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
     *         description="Service not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Service not found")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function destroy(int $id)
    {
        $service = Service::find($id);

        if(!$service) {
            return $this->error('Service not found', 404);
        }

        if (!Gate::allows('is-admin') && (!Gate::allows('is-business') || !Gate::allows('owns-model', $service))) {
            return $this->error('Forbidden', 403);
        }

        $service->delete();
        return $this->success(null, 'Service deleted successfully', 200);
    }

    /**
     * @OA\Delete(
     *     path="/services/{service}/images/{image}",
     *     summary="Delete an image from a service (business owner or admin)",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="service",
     *         in="path",
     *         description="Service ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="image",
     *         in="path",
     *         description="Image ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Image deleted successfully",
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
     *         description="Service not found or image not found for this service",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Service not found or Image not found for this service")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function deleteImage(int $serviceId, int $imageId)
    {
        $service = Service::find($serviceId);

        if (!$service) {
            return $this->error('Service not found', 404);
        }

        if (!Gate::allows('is-admin') && (!Gate::allows('is-business') || !Gate::allows('owns-model', $service))) {
            return $this->error('Forbidden', 403);
        }

        $image = $service->images()->find($imageId);

        if (!$image) {
            return $this->error('Service not found or Image not found for this service', 404);
        }

        // Intentamos borrar también el fichero físico si es una URL local de /storage
        if ($image->url) {
            $path = parse_url($image->url, PHP_URL_PATH); // ej: /storage/services/xxxx.jpg
            if ($path && strpos($path, '/storage/') === 0) {
                $relativePath = substr($path, strlen('/storage/')); // ej: services/xxxx.jpg
                Storage::disk('public')->delete($relativePath);
            }
        }

        $image->delete();

        return $this->success(null, 'Image deleted successfully', 200);
    }
}
