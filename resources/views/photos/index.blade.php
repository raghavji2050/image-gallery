@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  {{ __('Photos') }}
                  <a href="{{ route('photos.create') }}" class="btn btn-sm btn-primary float-right">Create</a>
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
                            Album
                          </th>
                          <th>
                            Actions
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($photos as $index => $photo)
                        <tr>
                          <td>
                            <img src="{{ url('storage/' . $photo->path) }}" class="img-fluid col-lg-4" alt="" onclick="openModal();currentSlide({{ $index + 1 }})" class="hover-shadow">
                          </td>
                          <td>
                            {{ $photo->album->name }}
                          </td>
                          <td>
                            <a href="{{ route('photos.edit', $photo->id) }}" class="btn btn-sm btn-success">Edit</a>
                            <form action="{{ route('photos.destroy', $photo->id) }}" method="post">
                              @csrf
                              @method('DELETE')
                              <button href="route('photos.detail', $photo->id)" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                          </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="3" class="text-center">
                            No Photos
                          </td>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                </div>
                <div id="myPhoto" class="modal">
                  <span class="close cursor" onclick="closeModal()">&times;</span>
                  <div class="modal-content">
                    @foreach($photos as $index => $photo)
                      <div class="mySlides">
                        <div class="numbertext">{{ $index + 1 }} / {{ $photos->count() }}</div>
                        <img src="{{ url('storage/' . $photo->path) }}" style="width:100%">
                      </div>
                    @endforeach
                    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                    <a class="next" onclick="plusSlides(1)">&#10095;</a>
                    <div class="caption-container">
                      <p id="caption"></p>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
