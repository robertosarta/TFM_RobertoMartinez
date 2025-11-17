<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="CategoryBasic",
 *     title="CategoryBasic",
 *     description="Category without nested subcategories",
 *     type="object",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Technology")
 * )
 *
 * @OA\Schema(
 *     schema="SubcategoryBasic",
 *     title="SubcategoryBasic",
 *     description="Subcategory without nested category",
 *     type="object",
 *     required={"id", "name", "category_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Laptops"),
 *     @OA\Property(property="category_id", type="integer", example=1)
 * )
 *
 * @OA\Schema(
 *     schema="UserBasic",
 *     title="UserBasic",
 *     description="User core fields without relations",
 *     type="object",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com")
 * )
 *
 * @OA\Schema(
 *     schema="ServiceBasic",
 *     title="ServiceBasic",
 *     description="Service fields without relations",
 *     type="object",
 *     required={"id", "name", "email", "phone", "price"},
 *     @OA\Property(property="id", type="integer", example=10),
 *     @OA\Property(property="name", type="string", example="Computer Repair"),
 *     @OA\Property(property="email", type="string", example="robertosarta@gmail.com"),
 *     @OA\Property(property="phone", type="string", example="+34 600 987 654"),
 *     @OA\Property(
 *         property="address",
 *         type="object",
 *         @OA\Property(property="street", type="string", example="456 Service St"),
 *         @OA\Property(property="city", type="string", example="Barcelona"),
 *         @OA\Property(property="zip", type="string", example="08001")
 *     ),
 *     @OA\Property(property="description", type="string", example="Repair and maintenance of laptops and PCs"),
 *     @OA\Property(property="price", type="string", example="49.99"),
 *     @OA\Property(property="user_id", type="integer", example=2),
 *     @OA\Property(property="subcategory_id", type="integer", example=5)
 * )
 *
 * @OA\Schema(
 *     schema="ServiceImageBasic",
 *     title="ServiceImageBasic",
 *     description="Image associated with a service",
 *     type="object",
 *     required={"id", "url"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="url", type="string", example="https://example.com/images/service1.jpg"),
 *     @OA\Property(property="caption", type="string", nullable=true, example="Foto del salón principal"),
 *     @OA\Property(property="is_primary", type="boolean", example=true),
 *     @OA\Property(property="sort_order", type="integer", example=1)
 * )
 *
 * @OA\Schema(
 *     schema="WeddingBasic",
 *     title="WeddingBasic",
 *     description="Wedding fields without relations",
 *     type="object",
 *     required={"id", "user_id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=2),
 *     @OA\Property(property="name", type="string", example="Boda de Marta y Luis"),
 *     @OA\Property(property="wedding_date", type="string", format="date", example="2025-06-15"),
 *     @OA\Property(property="location", type="string", example="Madrid"),
 *     @OA\Property(property="notes", type="string", example="Ceremonia al aire libre"),
 *     @OA\Property(property="budget", type="string", example="15000.00"),
 *     @OA\Property(property="guest_count", type="integer", example=120),
 *     @OA\Property(property="status", type="string", example="draft")
 * )
 *
 * @OA\Schema(
 *     schema="WeddingServicePivot",
 *     title="WeddingServicePivot",
 *     description="Pivot data for a service attached to a wedding (unit price and quantity)",
 *     type="object",
 *     @OA\Property(property="wedding_id", type="integer", example=1),
 *     @OA\Property(property="service_id", type="integer", example=10),
 *     @OA\Property(property="price", type="string", nullable=true, example="1000.00", description="Unit price agreed for this wedding"),
 *     @OA\Property(property="quantity", type="integer", nullable=true, example=1),
 *     @OA\Property(property="notes", type="string", nullable=true, example="Pago por horas"),
 *     @OA\Property(property="status", type="string", example="pending")
 * )
 *
 * @OA\Schema(
 *     schema="WeddingService",
 *     title="WeddingService",
 *     description="Service attached to a wedding with pivot data",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ServiceBasic"),
 *         @OA\Schema(
 *             type="object",
 *             @OA\Property(property="pivot", ref="#/components/schemas/WeddingServicePivot")
 *         )
 *     }
 * )
 */
class Schemas {}
