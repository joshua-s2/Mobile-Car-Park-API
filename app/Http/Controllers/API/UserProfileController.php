<?php


namespace App\Http\Controllers\API;


use App\Classes\Helper;
use App\User;
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
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
        ]);

        try {
            $this->user->update($data);

            return response()->json(['message' => 'Profile updated', 'data' => $data]);
        } catch (\Exception $e) {
            Helper::logException($e, "Error updating User Profile");
            return response()->json(['message' => $e->getMessage()], 501);
        }
    }

    public function manageProfile(Request $request)
    {
        $data = [
            'app_notifications' => $request->filled('app_notifications'),
            'push_notifications' => $request->filled('push_notifications'),
            'location_tracking' => $request->filled('location_tracking')
        ];

        // remove items not filled in the request
        array_walk($data, function ($item, $key) use (&$data) {
            if ($item == false) unset($data[$key]);
        });

        $this->user->settings()->updateOrCreate($data);

        return response()->json(['message' => 'Settings Updated', 'data' => $data]);
    }
}
