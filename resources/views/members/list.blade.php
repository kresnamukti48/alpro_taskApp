@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ $title ?? __('Member CRUD') }}</h1>

    <a href="{{ route('members.create') }}" class="btn btn-primary mb-3">New Member</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $member)
                <tr>
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td>{{ $member->name }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('members.edit', $member->id) }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                            <!-- Trigger Modal -->
                            <button type="button" class="btn btn-sm btn-info mr-2" data-toggle="modal" data-target="#memberDetailModal-{{ $member->id }}">Detail</button>

                            <form action="{{ route('members.destroy', $member->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure to delete this?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modals for Each Member -->
    @foreach ($members as $member)
        <div class="modal fade" id="memberDetailModal-{{ $member->id }}" tabindex="-1" role="dialog" aria-labelledby="memberDetailModalLabel-{{ $member->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="memberDetailModalLabel-{{ $member->id }}">Member Name: {{ $member->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4>Tasks:</h4>
                        <ul>
                            @foreach ($member->tasks as $task)
                                <li>{{ $task->title }}</li>
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
