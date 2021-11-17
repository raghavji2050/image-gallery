@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  {{ __('Edit Photo') }}
                  <a href="{{ route('photos.index') }}" class="btn btn-sm btn-primary float-right">Back</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('photos.update', $photo->id) }}" method="post" enctype='multipart/form-data'>
                      @csrf
                      @method('PATCH')
                      <div class="form-group">
                        <label>Album:</label>
                        <select name="album" class="form-control @error('album') is-invalid @enderror">
                          <option value="">Select Album</option>
                          @foreach($albums as $album)
                            <option value="{{ $album->id }}" @if(old('album', $photo->album_id) == $album->id) selected @endif>{{ $album->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      @error('album')
                      <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                      <div class="form-group">
                        <label>Photo:</label>
                        <input type="file" name="photo" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" accept="image/*">
                      </div>
                      @error('photo')
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
