<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /** Daftar kelas yang valid untuk registrasi mandiri (mahasiswa). */
    private const ALLOWED_KELAS = ['TI-2D', 'TI-2E'];

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'allowedKelas' => self::ALLOWED_KELAS,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * Registrasi mandiri = role student. Akun admin/dosen tetap dibuat
     * lewat /admin/users supaya assignment role tidak bisa di-spoof
     * dari form publik.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nim'                   => ['required', 'string', 'max:20', 'unique:'.User::class.',nim'],
            'nama'                  => ['required', 'string', 'max:100'],
            'kelas'                 => ['required', 'string', Rule::in(self::ALLOWED_KELAS)],
            'email'                 => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'              => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nim'      => $validated['nim'],
            'nama'     => $validated['nama'],
            'kelas'    => $validated['kelas'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'student', // mutator User::setRoleAttribute → role_id
            'total_xp' => 0,
            'level'    => 1,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
