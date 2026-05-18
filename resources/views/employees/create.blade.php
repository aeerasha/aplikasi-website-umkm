@extends('adminlte::page')

@section('title', 'Tambah Pegawai')

@section('content_header')
    <h1>Tambah Pegawai</h1>
@stop

@section('content')

<div class="card">

    <div class="card-body">

        <form action="{{ route('employees.store') }}" method="POST">

            @csrf

            <div class="form-group mb-3">
                <label>Nama</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name') }}"
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
                       value="{{ old('email') }}"
                       required>

                @error('email')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>


            <div class="form-group mb-3">
                <label>Password</label>

                <input type="password"
                       name="password"
                       class="form-control"
                       required>

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

                    <option value="">-- Pilih Role --</option>

                    <option value="pegawai">
                        Pegawai
                    </option>

                    <option value="owner">
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
                Simpan
            </button>

            <a href="{{ route('employees.index') }}"
               class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>

</div>

@stop