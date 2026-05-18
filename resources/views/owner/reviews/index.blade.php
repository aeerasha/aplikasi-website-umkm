@extends('adminlte::page')

@section('title', 'Ulasan Pelanggan')

@section('content_header')
    <h1>Ulasan Pelanggan</h1>
@stop

@section('content')

<div class="card">
    
    <div class="card-header">
        <h3 class="card-title">Daftar Ulasan Pelanggan</h3>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($reviews->count() > 0)

            @foreach($reviews as $review)

                <div class="card mb-3 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <h5 class="mb-1">

                                    @if($review->is_anonymous)
                                        <span class="badge badge-secondary">
                                            Anonymous
                                        </span>
                                    @else
                                        {{ $review->customer_name }}
                                    @endif

                                </h5>

                                <small class="text-muted">
                                    {{ $review->created_at->format('d M Y H:i') }}
                                </small>
                            </div>

                            <div>
                                <span class="badge badge-info p-2">
                                    Meja {{ $review->table_number }}
                                </span>
                            </div>

                        </div>

                        <hr>

                        <div class="mb-2">
                            <strong>Rating:</strong>

                            @for($i = 1; $i <= 5; $i++)

                                @if($i <= $review->rating)
                                    ⭐
                                @else
                                    ☆
                                @endif

                            @endfor

                            ({{ $review->rating }}/5)
                        </div>

                        <div>
                            {{ $review->comment }}
                        </div>

                    </div>

                </div>

            @endforeach

        @else

            <div class="alert alert-info">
                Belum ada ulasan pelanggan.
            </div>

        @endif

    </div>

</div>

@stop