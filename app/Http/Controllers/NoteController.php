<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $request->user()->notes()->create([
            'title' => null,
            'content' => '',
            'color' => '#0056c5',
            'tags' => [],
        ]);

        return back();
    }

    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== $request->user()->id)
            abort(403);

        $validated = $request->validate([
            'title' => 'sometimes|nullable|string|max:255',
            'content' => 'sometimes|nullable|string',
            'color' => 'sometimes|nullable|string',
            'tags' => 'sometimes|nullable|array',
            'tags.*' => 'string|max:40',
            'pinned' => 'sometimes|boolean',
            'archived' => 'sometimes|boolean',
        ]);

        $note->update($validated);

        return back();
    }

    public function destroy(Request $request, Note $note)
    {
        if ($note->user_id !== $request->user()->id)
            abort(403);

        if ($request->boolean('keep_tasks')) {
            $note->tasks()->update(['note_id' => null]);
        }

        $note->delete();

        return back();
    }

    /**
     * Add a checklist item to a note as a real, linked task.
     */
    public function addTask(Request $request, Note $note)
    {
        if ($note->user_id !== $request->user()->id)
            abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $note->tasks()->create([
            'name' => $validated['name'],
            'user_id' => $note->user_id,
            'due_date' => $validated['due_date'] ?? null,
            'sub_tasks' => [],
            'attachments' => [],
        ]);

        return back();
    }

    /**
     * Turn a demoted (unlinked) checklist line back into a real task.
     */
    public function promoteTask(Request $request, Note $note)
    {
        if ($note->user_id !== $request->user()->id)
            abort(403);

        $validated = $request->validate([
            'index' => 'required|integer|min:0',
        ]);

        $demoted = $note->demoted_tasks ?? [];

        if (! array_key_exists($validated['index'], $demoted))
            abort(404);

        $entry = $demoted[$validated['index']];
        unset($demoted[$validated['index']]);
        $note->demoted_tasks = array_values($demoted);
        $note->save();

        $note->tasks()->create([
            'name' => $entry['text'] ?? 'Untitled task',
            'user_id' => $note->user_id,
            'due_date' => $entry['due_date'] ?? null,
            'sub_tasks' => [],
            'attachments' => [],
        ]);

        return back();
    }

    /**
     * Parse a single line of free text into a note (and, if it contains a
     * "[ ]" marker, a linked task) — the bottom-of-log quick capture prompt.
     */
    public function quickCapture(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:2000',
        ]);

        $text = trim($validated['text']);

        preg_match_all('/#([a-zA-Z0-9_-]+)/', $text, $matches);
        $tags = array_values(array_unique($matches[1]));
        $body = trim(preg_replace('/#[a-zA-Z0-9_-]+/', '', $text));

        $taskText = null;
        $dueDate = null;

        if (str_contains($body, '[ ]')) {
            [$before, $after] = array_map('trim', explode('[ ]', $body, 2));
            $taskText = $before !== '' ? $before : $after;
            $dueDate = $this->parseDuePhrase($after);
            $body = $before;
        }

        $title = $body !== '' ? $body : ($taskText ?? 'Untitled');

        $note = $request->user()->notes()->create([
            'title' => Str::limit($title, 120, ''),
            'content' => $body,
            'color' => '#0056c5',
            'tags' => $tags,
        ]);

        if ($taskText) {
            $note->tasks()->create([
                'name' => $taskText,
                'user_id' => $request->user()->id,
                'due_date' => $dueDate,
                'sub_tasks' => [],
                'attachments' => [],
            ]);
        }

        return back();
    }

    private function parseDuePhrase(?string $phrase): ?string
    {
        $phrase = trim($phrase ?? '');

        if ($phrase === '')
            return null;

        foreach ([$phrase, Str::of($phrase)->explode(' ')->first()] as $candidate) {
            if (! $candidate)
                continue;

            try {
                return Carbon::parse($candidate)->toDateString();
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }
}
