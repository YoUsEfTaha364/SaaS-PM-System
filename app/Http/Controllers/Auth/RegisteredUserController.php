<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
          


        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);



        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);




        $token = session("workspace_invitation_token");
        if ($token) {
            $invitation = WorkspaceInvitation::where("token", $token)->first();


            if ($invitation) {

                $workspace = Workspace::find($invitation->workspace_id);

                if ($invitation->email == $user->email) {

                    if ($workspace) {
                        $workspace->users()->attach($user->id, [
                            "role" => $invitation->role
                        ]);
                        $invitation->delete();
                    }
                }
            }

            session()->forget("workspace_invitation_token");
        }

        return redirect(route('dashboard', absolute: false));
    }
}
