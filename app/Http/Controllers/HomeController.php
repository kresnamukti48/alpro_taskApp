<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::count();
        $members = Member::count();

        // Menghitung jumlah tugas berdasarkan status
        $taskPending = Task::where('status', 'pending')->count();
        $taskOnGoing = Task::where('status', 'on going')->count();
        $taskCompleted = Task::where('status', 'completed')->count();

        $widget = [
            'members' => $members,
            'task_pending' => $taskPending,
            'task_on_going' => $taskOnGoing,
            'task_completed' => $taskCompleted,
        ];

        return view('home', compact('widget'));
    }
}
