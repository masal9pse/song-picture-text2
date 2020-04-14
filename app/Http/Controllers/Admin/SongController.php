<?php

namespace App\Http\Controllers\Admin;  // \Adminを追加

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Http\Requests\CreateSongTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
 /**
  * Create a new controller instance.
  *
  * @return void
  */
 public function __construct()
 {
  $this->middleware('auth:admin');
 }

 /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Http\Response
  */
 public function create(Request $request)
 {
  $search = $request->input('search');
  $query = DB::table('songs');

  // もしキーワードがあったら
  if ($search !== null) {
   // 半角スペースを半角に
   $search_split = mb_convert_kana($search, 's');

   // 空白で区切る
   $search_split2 = preg_split('/[\s]+/', $search_split, -1, PREG_SPLIT_NO_EMPTY);

   foreach ($search_split2 as $value) {
    $query->where('title', 'like', '%' . $value . '%');
   }
  }

  $query->select('id', 'title', 'detail', 'file_name', 'created_at');
  $query->orderBy('created_at', 'desc');
  $songs = $query->paginate(10);

  return view('admin.create', [
   'songs' => $songs
  ]);
 }

 public function store(CreateSongTask $request)
 {
  $song = new Song;
  $song->title = $request->input('title');
  $song->detail = $request->input('detail');
  if ($request->file('image_file')) {
   $song->file_name = $request->file('image_file')->store('public/img');
  }

  $song->file_name = basename($song->file_name);
  // dd($song);
  // Song::create(['file_name' => basename($song->file_name)]);

  $song->save();
  return redirect()->route('admin.create')->with(['success' => 'ファイルを保存しました']);;
 }

 public function show($id)
 {
  $song = Song::find($id);

  return view('admin.show', [
   'song' => $song
  ]);
 }

 public function edit($id)
 {
  $song = Song::find($id);

  return view('admin.edit', compact('song'));
 }

 public function update(Request $request, $id)
 {
  $song = Song::find($id);

  $song->title = $request->input('title');
  $song->detail = $request->input('detail');

  $song->save();
  // dd($song);
  return redirect()->route('admin.create');
 }

 public function destroy($id)
 {
  $song = Song::find($id);
  // dd($song);
  $song->delete();

  return redirect()->route('admin.create');
 }
}
