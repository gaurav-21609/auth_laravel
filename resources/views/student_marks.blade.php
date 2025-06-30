@extends('layout.layout')
@section('content')

<div class="container">
    <h2>Student ID Card</h2>
    <div class="card p-4 mb-4">
        <h4>Name: {{ $student->name }}</h4>
        <p>Email: {{ $student->email }}</p>
        <p>Age: {{ $student->age }}</p>
    </div>

    <h4>Marks</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Score</th>
                <th>Grade</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($student->marks as $mark)
            <tr>
                <td>{{ $mark->subject }}</td>
                <td>{{ $mark->score }}</td>
                <td>{{ $mark->grade }}</td>
                <td>
                    <form method="POST" action="{{ route('marks.destroy', $mark->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                            onclick="return confirm('Delete this mark?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h5>Add New Mark</h5>
    <form method="POST" action="{{ route('student.marks.store', $student->id) }}">
        @csrf
        <div class="form-group">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Score</label>
            <input type="number" name="score" class="form-control" required min="0" max="100">
        </div>
        <div class="form-group">
            <label>Grade (optional)</label>
            <input type="text" name="grade" class="form-control">
        </div>
        <button class="btn btn-success mt-2">Add Mark</button>
    </form>
</div>
@endsection
