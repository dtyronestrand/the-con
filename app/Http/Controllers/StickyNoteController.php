<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StickyNote;

class StickyNoteController extends Controller
{
public function store(Request $request)
{
    $request->user()->stickNotes()->create(['content' => '','color' => '#fef08a']);
    return back();
}

public function update(Request $request, StickyNote $stickyNote)
{
    if ($stickyNote->user_id !== $request->user()->id) 
        abort(403);
    

    $stickyNote->update($request->only('content', 'color', 'width', 'height'));

    return back();
}
public function destroy(Request $request, StickyNote $stickyNote)
{
    if ($stickyNote->user_id !== $request->user()->id) 
        abort(403);
    
    $stickyNote->delete();

    return back();
}
}