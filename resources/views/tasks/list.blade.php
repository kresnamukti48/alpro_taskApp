@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ $title ?? __('Task Management') }}</h1>

    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">New Task</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ $task->due_date }}</td>
                    <td>{{ ucfirst($task->status) }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                            <!-- Trigger Modal -->
                            <button type="button" class="btn btn-sm btn-info mr-2" data-toggle="modal" data-target="#taskDetailModal-{{ $task->id }}">Detail</button>

                            <form action="{{ route('tasks.destroy', $task->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure to delete this task?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modals for Each Task -->
    @foreach ($tasks as $task)
        <div class="modal fade" id="taskDetailModal-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="taskDetailModalLabel-{{ $task->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskDetailModalLabel-{{ $task->id }}">Task Title: {{ $task->title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4>Task Details:</h4>
                        <p><strong>Description:</strong> {{ $task->description }}</p>
                        <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($task->status) }}</p>
                        <h4>Assigned Members:</h4>
                        <ul>
                            @foreach ($task->members as $member)
                                <li>{{ $member->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
