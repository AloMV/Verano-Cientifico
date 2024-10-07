<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Call;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $postulants = User::role('admin')->count();
        $newPostulantsThisWeek = User::role('admin')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();        

        $projects = Project::count();
        $newprojectsThisWeek = Project::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        $calls = Call::count();
        $callsActives = Call::where('status', true)->count();

        $data = [
            'postulants' => $postulants,
            'newPostulantsThisWeek' => $newPostulantsThisWeek,
            'projects' => $projects,
            'newProjectsThisWeek' => $newProjectsThisWeek,
            'calls' => $calls,
            'callsActives' => $callsActives
        ];

        return Inertia::render("Dashboard", [
            'data' => $data,
        ]);
    }
}
