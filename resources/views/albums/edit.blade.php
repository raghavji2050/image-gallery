@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  {{ __('Edit Album') }}
                  <a href="{{ route('albums.index') }}" class="btn btn-sm btn-primary float-right">Back</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('albums.update', $album->id) }}" method="post">
                      @csrf
                      @method('PATCH')
                      <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $album->name) }}">
                      </div>
                      @error('name')
                      <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                      <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
