<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Notifications\PermissionStatusUpdatedNotification;
use Illuminate\Http\Request;

class PermissionManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");
            });
        }
        if ($request->filled('date_start')) {
            $query->whereDate('start_date', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('end_date', '<=', $request->date_end);
        }

        $permissions = $query->get();
        $prefix = request()->segment(1) === 'responsable' ? 'responsable' : 'admin';
        return view("{$prefix}.permissions.index", compact('permissions'));
    }

    public function updateStatus(Request $request, Permission $permission)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string',
        ]);

        $permission->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        // Notifier l'utilisateur
        $permission->user->notify(new PermissionStatusUpdatedNotification($permission));

        return redirect()->back()->with('success', 'Le statut de la demande a été mis à jour.');
    }
}
