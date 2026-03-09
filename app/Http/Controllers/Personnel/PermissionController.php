<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Notifications\PermissionRequestedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Auth::user()->permissions()->orderBy('created_at', 'desc')->get();
        return view('personnel.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('personnel.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'end_date' => 'required|date|after_or_equal:today',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('permissions', 'public');
        }

        $permission = Auth::user()->permissions()->create([
            'type' => $request->type,
            'start_date' => now()->toDateString(),
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        // Notifier les administrateurs et responsables
        $admins = User::whereIn('role', ['admin', 'responsable'])->get();
        Notification::send($admins, new PermissionRequestedNotification($permission, Auth::user()));

        return redirect()->route('personnel.permissions.index')->with('success', 'Votre demande de permission a été soumise avec succès.');
    }

    public function destroy(Permission $permission)
    {
        // Vérifier si la permission appartient à l'utilisateur et est en attente
        if ($permission->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }

        if ($permission->status !== 'pending') {
            return redirect()->back()->with('error', 'Impossible de supprimer une demande déjà traitée.');
        }

        // Supprimer le fichier s'il existe
        if ($permission->attachment) {
            Storage::disk('public')->delete($permission->attachment);
        }

        $permission->delete();

        return redirect()->route('personnel.permissions.index')->with('success', 'Votre demande de permission a été supprimée.');
    }
}
