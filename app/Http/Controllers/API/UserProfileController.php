<?php


namespace App\Http\Controllers\API;


use App\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserProfileController
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function show()
    {
        return response()->json([
            'data' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'phone:NG', Rule::unique('users')->ignore($this->user->id)],
            'first_name' => ['required', 'string', 'min:3', 'max:255'],
            'last_name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        try {
            $this->user->updated($data);

            return response()->json(['message' => 'Profile updated', 'data' => $data]);
        } catch (\Exception $e) {
            Helper::logException($e, "Error updating User Profile");
            return response()->json(['message' => $e->getMessage()], 501);
        }
    }
}
