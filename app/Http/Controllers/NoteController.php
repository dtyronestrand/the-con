<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
public function store(Request $request)
{
    $request->user()->notes()->create(['content' => '','color' => '#fef08a']);
    return back();
}

public function update(Request $request, Note $note)
{
    if ($note->user_id !== $request->user()->id)
        abort(403);


    $note->update($request->only('content', 'color', 'width', 'height'));

    return back();
}
public function destroy(Request $request, Note $note)
{
    if ($note->user_id !== $request->user()->id)
        abort(403);

    $note->delete();

    return back();
}
}