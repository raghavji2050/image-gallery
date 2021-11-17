<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
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
    public function index()
    {
      $albums = Album::ofUser($this->userId)->with('photos')->latest()->get();

      return view('albums.index', compact('albums'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('albums.create');
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
          'name' => 'required'
        ]);

        Album::create([
          'user_id' => $this->userId,
          'name'    => $request->name
        ]);

        return redirect()->route('albums.index')->with('message',['status' => 'success', 'text' => 'Album successfully created!']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $album = Album::ofUser($this->userId)->findOrFail($id);

        return view('albums.edit', compact('album'));
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
          'name' => 'required'
        ]);

        $album = Album::ofUser($this->userId)->findOrFail($id);

        $album->update([
          'name' => $request->name
        ]);

        return redirect()->route('albums.index')->with('message',['status' => 'success', 'text' => 'Album successfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $album = Album::ofUser($this->userId)->findOrFail($id);

        Storage::delete($album->photos()->pluck('path')->toArray());

        $album->delete();

        return redirect()->route('albums.index')->with('message',['status' => 'success', 'text' => 'Album successfully deleted!']);
    }
}
