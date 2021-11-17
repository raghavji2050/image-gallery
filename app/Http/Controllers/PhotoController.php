<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\Album;
use Illuminate\Support\Facades\Storage;
use Auth;

class PhotoController extends Controller
{
    public $userId;

    public function __construct()
    {
      $this->middleware(function ($request, $next) {
            $this->userId = auth()->user()->id;
            return $next($request);
      });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $photos = Photo::whereHas('album', function ($query) {
                          $query->ofUser($this->userId);
                        })
                        ->when($request->album_id, function ($query) use ($request) {
                          $query->where('album_id', $request->album_id);
                        })
                        ->with('album')
                        ->latest()
                        ->get();

        return view('photos.index', compact('photos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $albums = Auth::user()->albums()->latest()->get();
        return view('photos.create', compact('albums'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
        'album' => 'required',
        'photo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
      ]);

      $path = $request->file('photo')->store('photo', ['disk' => 'public']);

      Photo::create([
        'album_id' => $request->album,
        'path'     => $path
      ]);

      return redirect()->route('photos.index')->with('message', ['status' => 'success', 'text' => 'Photo successfully created!']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $photo = Photo::whereHas('album', function ($query) {
                        $query->ofUser($this->userId);
                      })->findOrFail($id);
      $albums = Auth::user()->albums()->latest()->get();

      return view('photos.edit', compact('photo', 'albums'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $request->validate([
        'album' => 'required',
        'photo' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
      ]);

      $photo = Photo::whereHas('album', function ($query) {
                        $query->ofUser($this->userId);
                      })->findOrFail($id);

      $path = $request->file('photo') ? $request->file('photo')->store('photo', ['disk' => 'public']): $photo->path;

      Storage::delete($photo->path);

      $photo->update([
        'album_id' => $request->album,
        'path'     => $path
      ]);

      return redirect()->route('photos.index')->with('message', ['status' => 'success', 'text' => 'Photo successfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $photo = Photo::whereHas('album', function ($query) {
                        $query->ofUser($this->userId);
                      })->findOrFail($id);

      Storage::delete($photo->path);

      $photo->delete();

      return redirect()->route('photos.index')->with('message',['status' => 'success', 'text' => 'Photo successfully deleted!']);
    }
}
