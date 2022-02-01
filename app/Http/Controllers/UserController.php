<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/** @OA\SecurityScheme(
*    securityScheme="bearerAuth",
*    in="header",
*    name="bearerAuth",
*    type="http",
*    scheme="bearer",
*    bearerFormat="passport",
* ),
*/
class UserController extends Controller
{
     /**
     * @OA\Get(
     *      path="/api/users",
     *      operationId="User",
     *      tags={"Users"},
     *      summary="Get list of users",
     *      security={{"bearerAuth": {}}},
     *      description="Returns list of users",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

    public function index()
    {
        return User::all();
    }

    /**
     * @OA\Post(
     *      path="/api/users",
     *      operationId="User",
     *      tags={"Users"},
     *      summary="Store a user",
     *      security={{"bearerAuth": {}}},
     *      description="Foydalanuvchi ma'lumotlarini saqlash",
     *       @OA\RequestBody (
    *        @OA\JsonContent(),
    *        @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *                type="object",
    *                required={"name", "email", "password"},
    *                @OA\Property(property="name", type="text"),
    *                @OA\Property(property="email", type="text", format="email"),
    *                @OA\Property(property="password", type="password"),         
    *            ),
    *      ),
    * ),
     *      @OA\Response(
     *          response=200,
     *          description="A New User successfully created",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function store(UserRequest $request)
    {
        User::create($request->validated());
        return response()->json(['message'=>'A New User successfully created'], 201);
    }

    /**
     * @OA\Get(
     *      path="/api/users/{userId}",
     *      operationId="User",
     *      tags={"Users"},
     *      summary="Get data of a user",
     *      security={{"bearerAuth": {}}},
     *      description="Returns data of user",
     * @OA\Parameter(
     *          name="userId",
     *          description="Id of a user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="return a user data",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *  @OA\Response(
     *          response=404,
     *          description="Bunday user topilmadi",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

    public function show($id)
    {
        $user = User::find($id) ?? null;
        if($user) {
            return $user;
        } else {
            return response()->json(['message'=>'Bunday user topilmadi'], 404);
        }
    }

/**
    *   @OA\Put(
    *   path="/api/users/{userId}",
    *   operationId="User",
    *   tags={"Users"},
    *   summary="Update a user data ",
    *   security={{"bearerAuth": {{}}}},
    *   description="Foydalanuvchi ma'lumotlarini yangilash",
    *   @OA\Parameter(
    *          name="userId",
    *          description="Id of a user",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="application/x-www-form-urlencoded",
    *            @OA\Schema(
    *               type="object",
    *               required={"name","email", "password"},
    *               @OA\Property(property="name", type="text"),
    *               @OA\Property(property="email", type="text"),
    *               @OA\Property(property="password", type="password")
    *            ),
    *        ),
    *    ),
    *      @OA\Response(
    *          response=200,
    *          description="A User data updated Successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=422,
    *          description="Validation Error",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(response=400, description="Bad request"),
    *      @OA\Response(response=404, description="Resource Not Found"),
    * )
    */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());
        return response()->json(['message'=>'A User data updated successfully'], 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/users/{userId}",
     *      operationId="User",
     *      tags={"Users"},
     *      summary="Delete data of a user",
     *      security={{"bearerAuth": {}}},
     *      description="Foydalanuvchi ma'lumotlarini o'chirish",
     * @OA\Parameter(
     *          name="userId",
     *          description="Id of a user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="return successfull operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *  @OA\Response(
     *          response=404,
     *          description="Bunday user topilmadi",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

   
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message'=>'User data deleted successfully'], 200);
    }

/**
    * @OA\Post(
    * path="/api/register",
    * operationId="Register",
    * tags={"Register"},
    * summary="User Register",
    * description="User Register here",
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               required={"name","email", "password"},
    *               @OA\Property(property="name", type="text"),
    *               @OA\Property(property="email", type="text"),
    *               @OA\Property(property="password", type="password")
    *            ),
    *        ),
    *    ),
    *      @OA\Response(
    *          response=201,
    *          description="Register Successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=200,
    *          description="Register Successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=422,
    *          description="Unprocessable Entity",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(response=400, description="Bad request"),
    *      @OA\Response(response=404, description="Resource Not Found"),
    * )
    */

      public function register(Request $request)
      {
          $validated = $request->validate([
              'name' => 'required',
              'email' => 'required|email|unique:users',
              'password' => 'required|confirmed'
          ]);

          $data = $request->all();
          $data['password'] = Hash::make($data['password']);
          $user = User::create($data);
          $success['token'] =  $user->createToken('authToken')->accessToken;
          $success['name'] =  $user->name;
          return response()->json(['success' => $success]);
      }

      /**
        * @OA\Post(
        * path="/api/login",
        * operationId="authLogin",
        * tags={"Login"},
        * summary="User Login",
        * description="Login User Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */

      public function login(Request $request)
      {
          $validator = $request->validate([
              'email' => 'email|required',
              'password' => 'required'
          ]);

          if (!auth()->attempt($validator)) {
              return response()->json(['error' => 'Unauthorised'], 401);
          } else {
              $success['token'] = auth()->user()->createToken('authToken')->accessToken;
              $success['user'] = auth()->user();
              return response()->json(['success' => $success])->setStatusCode(200);
          }
      }
}
