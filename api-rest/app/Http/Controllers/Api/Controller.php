<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Api Rest Stef - Documentation",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="admin@example.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     in="header",
 *     bearerFormat="JWT",
 * )
 *
 * @OA\Tag(
 *     name="Login",
 * )
 * 
 * @OA\Tag(
 *     name="Register",
 * )
 * 
 * @OA\Tag(
 *     name="Users",
 *     description="User related endpoints",
 * )
 * 
 * @OA\Tag(
 *     name="Games",
 *     description="Game related endpoints",
 * )
 * 
 * @OA\Tag(
 *     name="Ranking",
 *     description="Ranking related endpoints",
 * )
 *
 */

class Controller
{
}
