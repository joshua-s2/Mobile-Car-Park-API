<?php


namespace App\Http\Controllers\API;


use App\Classes\Helper;
use App\User;
use Illuminate\Http\Request;

class UserProfileController
{
    public function show()
    {
        return response()->json([
            'data' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'phone:NG'],
            'first_name' => ['required', 'string', 'min:3', 'max:255'],
            'last_name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        try {
            User::updated($data);

            return response()->json(['message' => 'Profile updated', 'data' => $data]);
        } catch (\Exception $e) {
            Helper::logException($e, "Error updating User Profile");
            return response()->json(['message' => $e->getMessage()], 501);
        }
    }
}
