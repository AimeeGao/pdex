<?php

namespace Modules\Student\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Individual;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Student\Http\Requests\StoreIndividualMultiStepRequest;
use Modules\Student\Http\Requests\UpdateIndividualMultiStepRequest;
use Modules\Student\Http\Resources\IndividualResource;
use Auth;

/**
 * @OA\Info(
 *     title="PDEX API Documentation",
 *     version="1.0.0",
 *     description="Post-Secondary Data Exchange (PDEX) API endpoints for managing student profiles, institutional data, and ministry oversight.",
 *     @OA\Contact(
 *         email="admin@pdex.gov.bc.ca",
 *         name="PDEX Support"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://127.0.0.1:8232",
 *     description="PDEX API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Laravel Sanctum token authentication"
 * )
 * 
 * @OA\Tag(
 *     name="Students",
 *     description="API endpoints for managing student profiles"
 * )
 */
class StudentApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/students",
     *     summary="Get student profiles",
     *     description="Retrieve a list of student profiles with pagination",
     *     operationId="getStudents",
     *     tags={"Students"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Individual")
     *             ),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Individual::class);

        $perPage = $request->get('per_page', 15);
        $perPage = min(max($perPage, 1), 100); // Ensure between 1 and 100

        $individuals = Individual::with(['addresses', 'employments', 'identities'])
            ->paginate($perPage);

        return response()->json([
            'data' => IndividualResource::collection($individuals->items()),
            'current_page' => $individuals->currentPage(),
            'last_page' => $individuals->lastPage(),
            'per_page' => $individuals->perPage(),
            'total' => $individuals->total(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/students/{id}",
     *     summary="Get student profile by ID",
     *     description="Retrieve a specific student profile by ID",
     *     operationId="getStudent",
     *     tags={"Students"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Individual")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Individual not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $individual = Individual::with(['addresses', 'employments', 'identities'])
            ->findOrFail($id);

        $this->authorize('view', $individual);

        return response()->json([
            'data' => new IndividualResource($individual)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/students",
     *     summary="Create student profile",
     *     description="Create a new student profile",
     *     operationId="createStudent",
     *     tags={"Students"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Student profile data",
     *         @OA\JsonContent(ref="#/components/schemas/IndividualCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Student profile created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Student profile created successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/Individual")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function store(StoreIndividualMultiStepRequest $request): JsonResponse
    {
        $this->authorize('create', Individual::class);
        
        $validated = $request->validated();
        $validated['user_guid'] = auth()->user()->guid;

        try {
            $individual = Individual::create($validated);

            return response()->json([
                'message' => 'Student profile created successfully.',
                'data' => new IndividualResource($individual)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create student profile.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/students/{id}",
     *     summary="Update student profile",
     *     description="Update an existing student profile",
     *     operationId="updateStudent",
     *     tags={"Students"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Student profile data",
     *         @OA\JsonContent(ref="#/components/schemas/IndividualUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Student profile updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/Individual")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Individual not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function update(UpdateIndividualMultiStepRequest $request, $id): JsonResponse
    {
        $individual = Individual::findOrFail($id);
        $this->authorize('update', $individual);

        $validated = $request->validated();

        try {
            $individual->update($validated);

            return response()->json([
                'message' => 'Student profile updated successfully.',
                'data' => new IndividualResource($individual->fresh())
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update student profile.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/students/{id}",
     *     summary="Delete student profile",
     *     description="Delete a student profile",
     *     operationId="deleteStudent",
     *     tags={"Students"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student profile deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Student profile deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Individual not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $individual = Individual::findOrFail($id);
        $this->authorize('delete', $individual);

        try {
            $individual->delete();

            return response()->json([
                'message' => 'Student profile deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete student profile.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
