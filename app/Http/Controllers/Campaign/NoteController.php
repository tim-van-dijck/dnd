<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Campaign\Note;
use App\Repositories\NoteRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(NoteRepository $noteRepository, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'content' => 'required|string',
            'private' => 'boolean'
        ]);
        $noteRepository->store(Session::get('campaign_id'), $request->input());
    }

    /**
     * @param NoteRepository $noteRepository
     * @param int $noteId
     * @return Note
     */
    public function show(NoteRepository $noteRepository, int $noteId): Note
    {
        return $noteRepository->find(Session::get('campaign_id'), $noteId);
    }

    /**
     * @param NoteRepository $noteRepository
     * @param Request $request
     * @param int $noteId
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(NoteRepository $noteRepository, Request $request, int $noteId)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'content' => 'required|string',
            'private' => 'boolean'
        ]);
        $noteRepository->update(Session::get('campaign_id'), $noteId, $request->input());
    }

    /**
     * @param NoteRepository $noteRepository
     * @param int $noteId
     */
    public function destroy(NoteRepository $noteRepository, int $noteId)
    {
        $noteRepository->destroy(Session::get('campaign_id'), $noteId);
    }
}