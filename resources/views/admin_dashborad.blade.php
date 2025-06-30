@extends('layout.layout')
@section('content')
<div class="mt-5">
    <div class="container">
        <div class="table-responsive">
            <!-- fixed typo: table-resposive -->
            <table id="students-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Age</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="edit-student-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="edit-student-id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit-email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="edit-age" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- jQuery & DataTables Scripts -->
<script>
    $(document).ready(function () {
                const table = $('#students-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin_dashborad') }}", // fixed route name
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'age', name: 'age' },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                $(document).on('click', '.edit-btn', function () {
                    const id = $(this).data('id');
                    $.get('/students/' + id, function (student) {
                        $('#edit-student-id').val(student.id);
                        $('#edit-name').val(student.name);
                        $('#edit-email').val(student.email);
                        $('#edit-age').val(student.age);
                        $('#editStudentModal').modal('show');
                    });
                });

                $('#edit-student-form').submit(function (e) {
                    e.preventDefault();
                    const id = $('#edit-student-id').val();

                    $.ajax({
                        url: '/students/' + id,
                        method: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            name: $('#edit-name').val(),
                            email: $('#edit-email').val(),
                            age: $('#edit-age').val()
                        },
                        success: function () {
                            $('#editStudentModal').modal('hide');
                            table.ajax.reload();
                            alert('Student updated successfully!');
                        },
                        error: function () {
                            alert('Update failed.');
                        }
                    });
                });

                $(document).on('click', '.delete-btn', function () {
                    const id = $(this).data('id');
                    if (confirm("Are you sure you want to delete this student?")) {
                        $.ajax({
                            url: '/students/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function () {
                                table.ajax.reload();
                                alert("Student deleted successfully.");
                            }
                        });
                    }
                });
            });
</script>
@endsection
