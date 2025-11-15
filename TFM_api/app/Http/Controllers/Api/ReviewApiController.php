<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/reviews",
     *     summary="List reviews (optionally filtered by service or user)",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="service_id",
     *         in="query",
     *         description="Filter by service ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Filter by user ID",
     *         required=false,
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
     *                 @OA\Items(ref="#/components/schemas/Review")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'service']);

        if ($serviceId = $request->query('service_id')) {
            $query->where('service_id', $serviceId);
        }

        if ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }

        $reviews = $query->get();

        return $this->success($reviews);
    }

    /**
     * @OA\Post(
     *     path="/reviews",
     *     summary="Create a new review for a service",
     *     tags={"Reviews"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"service_id","rating"},
     *             @OA\Property(property="service_id", type="integer"),
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5),
     *             @OA\Property(property="comment", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Review created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Review")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed or already reviewed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed or You have already reviewed this service"),
     *             @OA\Property(property="errors", type="object", nullable=true)
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'service_id' => 'required|integer|exists:services,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $existing = Review::where('user_id', $user->id)
            ->where('service_id', $data['service_id'])
            ->first();

        if ($existing) {
            return $this->error('You have already reviewed this service', 422);
        }

        $data['user_id'] = $user->id;

        $review = Review::create($data);

        return $this->success($review, 'Review created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/reviews/{id}",
     *     summary="Get a single review",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Review ID",
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
     *             @OA\Property(property="data", ref="#/components/schemas/Review")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Review not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Review not found")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        $review = Review::with(['user', 'service'])->find($id);

        if (!$review) {
            return $this->error('Review not found', 404);
        }

        return $this->success($review);
    }

    /**
     * @OA\Put(
     *     path="/reviews/{id}",
     *     summary="Update a review (owner or admin)",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Review ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, nullable=true),
     *             @OA\Property(property="comment", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Review")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed or empty payload",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No data provided or invalid JSON"),
     *             @OA\Property(property="errors", type="object", nullable=true)
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
     *         description="Review not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Review not found")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function update(Request $request, int $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return $this->error('Review not found', 404);
        }

        $user = Auth::user();

        if ($review->user_id !== $user->id && $user->role !== 'admin') {
            return $this->error('Forbidden', 403);
        }

        $data = $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'sometimes|nullable|string',
        ]);

        if (empty($data)) {
            return $this->error('No data provided or invalid JSON', 422);
        }

        $review->update($data);

        return $this->success($review, 'Review updated successfully', 200);
    }

    /**
     * @OA\Delete(
     *     path="/reviews/{id}",
     *     summary="Delete a review (owner or admin)",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Review ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review deleted successfully",
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
     *         description="Review not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Review not found")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function destroy(int $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return $this->error('Review not found', 404);
        }

        $user = Auth::user();

        if ($review->user_id !== $user->id && $user->role !== 'admin') {
            return $this->error('Forbidden', 403);
        }

        $review->delete();

        return $this->success(null, 'Review deleted successfully', 200);
    }
}
