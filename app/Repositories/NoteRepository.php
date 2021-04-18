<?php

namespace App\Repositories;

use App\Models\Campaign\Note;
use App\Services\AuthService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class NoteRepository
{
    /** @var LogRepository */
    private $logRepository;

    /**
     * NoteRepository constructor.
     */
    public function __construct()
    {
        $this->logRepository = app(LogRepository::class);
    }

    /**
     * @param int $campaignId
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function get(int $campaignId, int $page = 1, int $pageSize = 20): LengthAwarePaginator
    {
        $query = Note::query()
            ->where('notes.campaign_id', $campaignId)
            ->leftJoin('user_permissions', function ($join) {
                $join->on('notes.id', '=', 'user_permissions.entity_id')
                    ->where([
                        'user_permissions.entity' => 'note',
                        'user_permissions.user_id' => Auth::user()->id
                    ]);
            });

        if (Auth::user()->can('viewAny', Note::class)) {
            $query->where(function ($query) {
                $query->where('private', 0)
                    ->orWhere('user_permissions.view', 1);
            });
        } else {
            $query->where('user_permissions.view', 1);
        }
        return $query->paginate($pageSize, ['notes.*'], 'page[number]', $page);
    }

    /**
     * @param int $campaignId
     * @param array $data
     */
    public function store(int $campaignId, array $data)
    {
        $note = new Note();
        $note->campaign_id = $campaignId;
        $note->name = $data['name'];
        $note->content = $data['content'];
        $note->private = $data['private'] ?? false;
        $note->save();

        if (array_key_exists('permissions', $data)) {
            AuthService::setCustomPermissions($campaignId, 'note', $note->id, $data['permissions']);
        }

        $this->logRepository->store($campaignId, 'note', $note->id, $note->name, 'created');
    }

    /**
     * @param int $campaignId
     * @param Note $note
     * @param array $data
     */
    public function update(int $campaignId, Note $note, array $data)
    {
        if ($campaignId != $note->campaign_id) {
            throw new ModelNotFoundException();
        }
        $note->name = $data['name'];
        $note->content = $data['content'];
        $note->private = $data['private'] ?? false;
        $note->save();

        if (array_key_exists('permissions', $data)) {
            AuthService::setCustomPermissions($campaignId, 'note', $note->id, $data['permissions']);
        }

        $this->logRepository->store($campaignId, 'note', $note->id, $note->name, 'updated');
    }

    /**
     * @param int $campaignId
     * @param Note $note
     */
    public function destroy(int $campaignId, Note $note)
    {
        if ($campaignId != $note->campaign_id) {
            throw new ModelNotFoundException();
        }
        $note->delete();
        $this->logRepository->store($campaignId, 'note', $note->id, $note->name, 'deleted');
    }
}