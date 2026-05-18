@extends('adminlte::page')

@section('title', 'Data Pegawai')

@section('content_header')
    <h1>Data Pegawai</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">
                List Pegawai
            </h3>

            <a href="{{ route('employees.create') }}"
               class="btn btn-primary">
                Tambah Pegawai
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($employees as $employee)

                        <tr>

                            <td>{{ $employee->name }}</td>

                            <td>{{ $employee->email }}</td>

                            <td>{{ $employee->role }}</td>

                            <td>

                                <a href="{{ route('employees.edit', $employee->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('employees.destroy', $employee->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus pegawai?')">

                                        Hapus

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center">
                                Belum ada data pegawai
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@stop