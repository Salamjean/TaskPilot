<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DailyLogController as AdminDailyLogController;
use App\Http\Controllers\Admin\ProgrammationController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Personnel\AttendanceController;
use App\Http\Controllers\Personnel\DailyLogController as PersonnelDailyLogController;
use App\Http\Controllers\Personnel\PersonnelController;
use App\Http\Controllers\Personnel\ProjectController as PersonnelProjectController;
use App\Http\Controllers\Prestataire\PrestataireController;
use App\Http\Controllers\Responsable\ResponsableController;
use App\Http\Controllers\User\UserAuthenticate;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques — Authentification (même page pour tous)
|--------------------------------------------------------------------------
*/
Route::get('/', [UserAuthenticate::class, 'login'])->name('login');
Route::post('/', [UserAuthenticate::class, 'handleLogin'])->name('handleLogin');

Route::get('/forgot-password', [UserAuthenticate::class, 'forgotPassword'])->name('password.request');
Route::post('/forgot-password', [UserAuthenticate::class, 'handleForgotPassword'])->name('password.email');
Route::get('/reset-password/{email}', [UserAuthenticate::class, 'resetPassword'])->name('password.reset');
Route::post('/reset-password', [UserAuthenticate::class, 'handleResetPassword'])->name('password.update');

// Routes accessibles sans restriction (lien depuis email)
Route::get('/validate-user-account/{email}', [UserAuthenticate::class, 'defineAccess'])->name('auth.defineAccess');
Route::post('/validate-user-account', [UserAuthenticate::class, 'submitDefineAccess'])->name('auth.submitDefineAccess');

Route::post('/logout', [UserAuthenticate::class, 'logout'])->name('logout')->middleware('auth');

// Notifications (Communes à tous les utilisateurs connectés)
Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('notifications.index')
    ->middleware('auth');

Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.markAsRead')
    ->middleware('auth');

Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])
    ->name('notifications.markAllRead')
    ->middleware('auth');

// Profil Utilisateur (Toutes les rôles)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Routes Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,responsable'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Projects: View & Create by all. Restricted to Admin: Delete, Update Status.
    Route::resource('projects', ProjectController::class)->except(['destroy']);

    // Users: View only for Responsable.
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Programmation: View only for Responsable.
    Route::get('/programmation', [ProgrammationController::class, 'index'])->name('programmation.index');

    // Tâches: View & Store for all.
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/{project}', [TaskController::class, 'show'])->name('show');
        Route::post('/{project}', [TaskController::class, 'store'])->name('store');
        Route::put('/task/{task}', [TaskController::class, 'update'])->name('update');
    });

    // Special restrictions for Admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['index']);

        Route::get('/programmation/{project}/edit', [ProgrammationController::class, 'edit'])->name('programmation.edit');
        Route::put('/programmation/{project}', [ProgrammationController::class, 'update'])->name('programmation.update');
    });

    // Actions now shared by Admin and Responsable
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::patch('projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.status');
    Route::patch('/tasks/task/{task}/reschedule', [TaskController::class, 'reschedule'])->name('tasks.reschedule');
    Route::delete('/tasks/task/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Rapports journaliers
    Route::get('/daily-logs', [AdminDailyLogController::class, 'index'])->name('daily-logs.index');
    Route::get('/daily-logs/create', [AdminDailyLogController::class, 'create'])->name('daily-logs.create');
    Route::post('/daily-logs', [AdminDailyLogController::class, 'store'])->name('daily-logs.store');

    // Pointages
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
});

/*
|--------------------------------------------------------------------------
| Routes Personnel
|--------------------------------------------------------------------------
*/
Route::prefix('personnel')->name('personnel.')->middleware(['auth', 'role:personnel'])->group(function () {
    Route::get('/dashboard', [PersonnelController::class, 'dashboard'])->name('dashboard');

    // Pointage
    Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');

    Route::middleware(['check.clock.in'])->group(function () {
        // Projets et Tâches du personnel
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', [PersonnelProjectController::class, 'index'])->name('index');
            Route::get('/{project}', [PersonnelProjectController::class, 'show'])->name('show');
        });
        Route::put('/tasks/{task}', [PersonnelProjectController::class, 'updateTask'])->name('tasks.update');

        // Rapport journalier
        Route::prefix('daily-logs')->name('daily-logs.')->group(function () {
            Route::get('/', [PersonnelDailyLogController::class, 'index'])->name('index');
            Route::get('/create', [PersonnelDailyLogController::class, 'create'])->name('create');
            Route::post('/', [PersonnelDailyLogController::class, 'store'])->name('store');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Routes Responsable
|--------------------------------------------------------------------------
*/
Route::prefix('responsable')->name('responsable.')->middleware(['auth', 'role:responsable'])->group(function () {
    Route::get('/dashboard', [ResponsableController::class, 'dashboard'])->name('dashboard');

    // Projects: Full access for Responsable
    Route::resource('projects', ProjectController::class);
    Route::patch('projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.status');

    // Users: Read-only access for Responsable
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Programmation: Read-only access for Responsable
    Route::get('/programmation', [ProgrammationController::class, 'index'])->name('programmation.index');

    // Tasks: Full access for Responsable
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/{project}', [TaskController::class, 'show'])->name('show');
        Route::post('/{project}', [TaskController::class, 'store'])->name('store');
        Route::put('/task/{task}', [TaskController::class, 'update'])->name('update');
        Route::patch('/task/{task}/reschedule', [TaskController::class, 'reschedule'])->name('reschedule');
        Route::delete('/task/{task}', [TaskController::class, 'destroy'])->name('destroy');
    });

    // Daily Logs: Read-only
    Route::get('/daily-logs', [AdminDailyLogController::class, 'index'])->name('daily-logs.index');

    // Pointages
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
});

/*
|--------------------------------------------------------------------------
| Routes Prestataire  (lecture seule — aucune action)
|--------------------------------------------------------------------------
*/
Route::prefix('prestataire')->name('prestataire.')->middleware(['auth', 'role:prestataire'])->group(function () {
    Route::get('/dashboard', [PrestataireController::class, 'dashboard'])->name('dashboard');

    // Projets (lecture seule)
    Route::get('/projects', [PrestataireController::class, 'projects'])->name('projects.index');
    Route::get('/projects/{project}', [PrestataireController::class, 'showProject'])->name('projects.show');

    // Tâches (lecture seule)
    Route::get('/tasks', [PrestataireController::class, 'tasks'])->name('tasks.index');
    Route::get('/tasks/{project}', [PrestataireController::class, 'showTasks'])->name('tasks.show');

    // Rapports journaliers (lecture seule)
    Route::get('/daily-logs', [PrestataireController::class, 'dailyLogs'])->name('daily-logs.index');

    // Utilisateurs (lecture seule)
    Route::get('/users', [PrestataireController::class, 'users'])->name('users.index');
});
