<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\UnauthorizedException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login()
    {
        $returnMessage = null;

        try
        {
            $credentials = request(['email', 'password']);

            if (!$token = JWTAuth::attempt($credentials)) {
                throw new UnauthorizedException('Unauthorized');
            }

            $returnMessage = $this->respondWithToken($token);
        }
        catch (UnauthorizedException $ex)
        {
            $returnMessage = response()->json(['message' => $ex->getMessage()], 401);
        }
        catch (Exception $ex)
        {
            $returnMessage = response()->json(['message' => $ex->getMessage()], 500);
        }
        finally
        {
            return $returnMessage;
        }
    }

    public function logout()
    {
        $returnMessage = null;

        try
        {
            if (empty(request()->bearerToken()))
            {
                throw new UnauthorizedException('Unauthorized');
            }

            auth('api')->logout();
            $returnMessage = response()->json(['message' => 'Successfully logged out'], 200);
        }
        catch (UnauthorizedException $ex)
        {
            $returnMessage = response()->json(['message' => $ex->getMessage()], 401);
        }
        catch (Exception $ex)
        {
            $returnMessage = response()->json(['message' => $ex->getMessage()], 500);
        }
        finally
        {
            return $returnMessage;
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh(JWTAuth::getToken()));
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'data' =>
            [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ]);
    }
}
