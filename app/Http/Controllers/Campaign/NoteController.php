<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Campaign\Note;
use App\Repositories\NoteRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class NoteController extends Controller
{
    public function index(NoteRepository $noteRepository, Request $request): AnonymousResourceCollection
    {
        $page = $request->query('page', []);
        $notes = $noteRepository->get(Session::get('campaign_id'), $page['number'] ?? 1, $page['size'] ?? 20);
        return NoteResource::collection($notes);
    }

    /**
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function store(NoteRepository $noteRepository, Request $request): void
    {
        $this->authorize('create', Note::class);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'content' => 'required|string',
            'private' => 'boolean',
            'permissions' => 'sometimes|nullable|array',
            'permissions.*.view' => 'required|boolean',
            'permissions.*.edit' => 'required|boolean',
            'permissions.*.delete' => 'required|boolean',
        ]);
        $noteRepository->store(Session::get('campaign_id'), $request->input());
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Note $note): NoteResource
    {
        $this->authorize('view', $note);
        if (Session::get('campaign_id') != $note->campaign_id) {
            throw new ModelNotFoundException();
        }
        return new NoteResource($note);
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(NoteRepository $noteRepository, Request $request, Note $note): void
    {
        $this->authorize('update', $note);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'content' => 'required|string',
            'private' => 'boolean',
            'permissions' => 'sometimes|nullable|array',
            'permissions.*.view' => 'required|boolean',
            'permissions.*.edit' => 'required|boolean',
            'permissions.*.delete' => 'required|boolean',
        ]);
        $noteRepository->update(Session::get('campaign_id'), $note, $request->input());
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(NoteRepository $noteRepository, Note $note): void
    {
        $this->authorize('destroy', $note);
        $noteRepository->destroy(Session::get('campaign_id'), $note);
    }
}