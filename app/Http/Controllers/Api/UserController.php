<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->search;

        $users = User::where(
                'name',
                'like',
                "%{$search}%"
            )
            ->get();

        return response()->json($users);
    }
}