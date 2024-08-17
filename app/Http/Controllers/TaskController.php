<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Member;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::with('members')->get();
        return view('tasks.list', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $members = Member::all();
        return view('tasks.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,on going,completed',
            'members' => 'required|array', // Validasi untuk memastikan anggota dipilih
            'members.*' => 'exists:members,id', // Validasi setiap anggota yang dipilih harus ada di tabel members
        ]);
    
        $task = Task::create($request->only(['title', 'description', 'due_date', 'status']));
    
        // Menyinkronkan anggota yang dipilih dengan tugas
        $task->members()->sync($request->input('members'));
    
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::with('members')->findOrFail($id);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::with('members')->findOrFail($id);
        $members = Member::all();
        return view('tasks.edit', compact('task', 'members'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,on going,completed',
            'members' => 'required|array', // Validasi untuk memastikan anggota dipilih
            'members.*' => 'exists:members,id', // Validasi setiap anggota yang dipilih harus ada di tabel members
        ]);

        $task = Task::findOrFail($id);
        $task->update($request->only(['title', 'description', 'due_date', 'status']));

        // Menyinkronkan anggota yang dipilih dengan tugas
        $task->members()->sync($request->input('members'));

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->members()->detach();
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
