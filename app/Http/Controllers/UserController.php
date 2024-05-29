<?php

namespace App\Http\Controllers;

use App\Models\Adviser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     *Gets users except yourself
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::where('role', 'Adviser')->get();
        return $this->success($users);
    }
    /**
     * Search for advisers
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $advisers = Adviser::where(function ($query) use ($search) {
            $query->where('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%");
        })
            ->orwhereHas('Adviser', function ($query) use ($search) {
                $query->where('Nom_specialty', 'LIKE', "%{$search}%");
            })
            ->get();
        return $this->success($advisers, 'Users found successfully');
    }
}
