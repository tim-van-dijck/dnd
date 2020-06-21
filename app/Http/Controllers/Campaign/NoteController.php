<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Campaign\Note;
use App\Repositories\NoteRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class NoteController extends Controller
{
    /**
     * @param NoteRepository $noteRepository
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(NoteRepository $noteRepository, Request $request)
    {
        $page = $request->query('page', []);
        $notes = $noteRepository->get(Session::get('campaign_id'), $page['number'] ?? 1, $page['size'] ?? 20);
        return NoteResource::collection($notes);
    }

    /**
     * @param NoteRepository $noteRepository
     * @param Request $request
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function store(NoteRepository $noteRepository, Request $request)
    {
        $this->authorize('create', Note::class);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'content' => 'required|string',
            'private' => 'boolean'
        ]);
        $noteRepository->store(Session::get('campaign_id'), $request->input());
    }

    /**
     * @param NoteRepository $noteRepository
     * @param Note $note
     * @return Note
     * @throws AuthorizationException
     */
    public function show(Note $note): Note
    {
        $this->authorize('view', $note);
        if (Session::get('campaign_id') != $note->campaign_id) {
            throw new ModelNotFoundException();
        }
        return $note;
    }

    /**
     * @param NoteRepository $noteRepository
     * @param Request $request
     * @param Note $note
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(NoteRepository $noteRepository, Request $request, Note $note)
    {
        $this->authorize('edit', $note);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'content' => 'required|string',
            'private' => 'boolean'
        ]);
        $noteRepository->update(Session::get('campaign_id'), $note, $request->input());
    }

    /**
     * @param NoteRepository $noteRepository
     * @param Note $note
     * @throws AuthorizationException
     */
    public function destroy(NoteRepository $noteRepository, Note $note)
    {
        $this->authorize('destroy', $note);
        $noteRepository->destroy(Session::get('campaign_id'), $note);
    }
}