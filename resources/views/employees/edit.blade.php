@extends('adminlte::page')

@section('title', 'Edit Pegawai')

@section('content_header')
    <h1>Edit Pegawai</h1>
@stop

@section('content')

<div class="card">

    <div class="card-body">

        <form action="{{ route('employees.update', $employee->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label>Nama</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name', $employee->name) }}"
                       required>

                @error('name')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>


            <div class="form-group mb-3">
                <label>Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email', $employee->email) }}"
                       required>

                @error('email')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>


            <div class="form-group mb-3">
                <label>Password Baru</label>

                <input type="password"
                       name="password"
                       class="form-control">

                <small class="text-muted">
                    Kosongkan jika tidak ingin mengubah password
                </small>

                @error('password')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>


            <div class="form-group mb-3">
                <label>Role</label>

                <select name="role"
                        class="form-control"
                        required>

                    <option value="pegawai"
                        {{ $employee->role == 'pegawai' ? 'selected' : '' }}>
                        Pegawai
                    </option>

                    <option value="owner"
                        {{ $employee->role == 'owner' ? 'selected' : '' }}>
                        Owner
                    </option>

                </select>

                @error('role')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">
                Update
            </button>

            <a href="{{ route('employees.index') }}"
               class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>

</div>

@stop