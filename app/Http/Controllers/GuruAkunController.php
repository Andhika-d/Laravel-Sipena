<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Guru;
use App\Models\User;

class GuruAkunController extends Controller
{
    public function index()
    {
        $gurus = Guru::whereNotIn('id', function ($query) {
            $query->select('guru_id')->from('users')->whereNotNull('guru_id');
        })->get();

        $users = User::where('role', '!=', 'admin')->get();

        return view('admin.DataMaster.users', compact('gurus', 'users'));
    }

    public function create()
    {
        $gurus = Guru::whereNotIn('id', function ($query) {
            $query->select('guru_id')->from('users')->whereNotNull('guru_id');
        })->get();

        return view('admin.guru-akun.create', compact('gurus'));
    }

    public function store(Request $request)
{
    $request->validate([
        'guru_id' => 'required|exists:gurus,id|unique:users,guru_id',
        'email' => 'required|email|ends_with:@sipena.com',
        'role'    => 'required|in:guru,kepsek',
        'password' => 'required|min:6',
    ]);

    $guru = Guru::find($request->guru_id);

    if (!$guru) {
        return redirect()->back()->withErrors(['guru_id' => 'Guru tidak ditemukan']);
    }

    User::create([
        'guru_id' => $guru->id,
        'name'    => $guru->nama,
        'email'   => $request->email,
        'role'    => $request->role,
        'password'=> Hash::make($request->password),
    ]);

    return redirect()->route('admin.datamaster.users')->with('success', 'Akun guru berhasil dibuat');
}

public function update(Request $request, $id)
{
    // Gabung prefix + domain
    if ($request->has('email_prefix')) {
        $request->merge([
            'email' => $request->email_prefix . '@sipena.com',
        ]);
    }

    $request->validate([
        'email' => 'required|email|ends_with:@sipena.com',
        'role'  => 'required|in:guru,kepsek',
        'password' => 'nullable|min:6',
    ]);

    $user = User::findOrFail($id);
    $user->email = $request->email;
    $user->role  = $request->role;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('admin.datamaster.users')->with('success', 'Akun berhasil diperbarui');
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('admin.datamaster.users')->with('success', 'Akun berhasil dihapus');
}

}
