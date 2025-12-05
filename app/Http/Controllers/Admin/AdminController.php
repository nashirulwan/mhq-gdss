<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Juri;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'total_peserta' => User::role('peserta')->count(),
            'total_juri' => User::role('juri')->count(),
            'total_penilaian' => Penilaian::count(),
            'active_users' => User::active()->count(),
            'recent_users' => User::with('profile')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'recent_penilaians' => Penilaian::with(['peserta', 'juri', 'kriteria'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];

        // User growth data for chart
        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Role distribution
        $roleDistribution = [
            'admin' => User::role('admin')->count(),
            'juri' => User::role('juri')->count(),
            'peserta' => User::role('peserta')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'userGrowth', 'roleDistribution'));
    }

    /**
     * Display all users management.
     */
    public function users(Request $request)
    {
        $query = User::with('profile');

        // Filter by role
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,juri,peserta',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'), // Fix: use has() instead of boolean()
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        try {
            $user->update($data);

            return redirect()->route('admin.users')
                ->with('success', "User {$user->name} berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal update user: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user status.
     */
    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User {$user->name} berhasil {$status}.");
    }

    /**
     * Delete user.
     */
    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', "User {$user->name} berhasil dihapus.");
    }

    /**
     * Display system settings.
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Display analytics page.
     */
    public function analytics()
    {
        // Penilaian statistics
        $penilaianStats = Penilaian::selectRaw('
                COUNT(*) as total_penilaians,
                AVG(nilai) as avg_nilai,
                MIN(nilai) as min_nilai,
                MAX(nilai) as max_nilai
            ')->first();

        // Top performers
        $topPerformers = Peserta::with('user')
            ->select('nama_lengkap', DB::raw('AVG(penilaians.nilai) as avg_score'))
            ->join('penilaians', 'pesertas.id', '=', 'penilaians.peserta_id')
            ->groupBy('pesertas.id', 'nama_lengkap')
            ->orderByDesc('avg_score')
            ->take(5)
            ->get();

        // Juri activity
        $juriActivity = Juri::with('user')
            ->withCount(['penilaians'])
            ->orderByDesc('penilaians_count')
            ->take(5)
            ->get();

        return view('admin.analytics', compact(
            'penilaianStats',
            'topPerformers',
            'juriActivity'
        ));
    }

    /**
     * Export data (placeholder).
     */
    public function export(Request $request)
    {
        // Placeholder for export functionality
        return back()->with('info', 'Fitur export akan segera tersedia.');
    }
}
