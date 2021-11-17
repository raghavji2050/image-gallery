@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  {{ __('Albums') }}
                  <a href="{{ route('albums.create') }}" class="btn btn-sm btn-primary float-right">Create</a>
                </div>
                <div class="card-body">
                  @include('includes.session')
                    <table class="table">
                      <thead>
                        <tr>
                          <th>
                            Name
                          </th>
                          <th>
                            Photos
                          </th>
                          <th>
                            Actions
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($albums as $album)
                        <tr>
                          <td>
                            {{ $album->name }}
                          </td>
                          <td>
                            {{ $album->photos()->count() }}
                          </td>
                          <td class="d-flex">
                            <a href="{{ route('albums.edit', $album->id) }}" class="btn btn-sm btn-success mr-1">Edit</a>
                            @if($album->photos()->exists())
                              <a href="{{ route('photos.index', ['album_id' => $album->id]) }}" class="btn btn-sm btn-primary mr-1">View</a>
                            @endif
                            <form action="{{ route('albums.destroy', $album->id) }}" method="post">
                              @csrf
                              @method('DELETE')
                              <button href="route('albums.detail', $album->id)" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                          </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="3" class="text-center">
                            No Albums
                          </td>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
