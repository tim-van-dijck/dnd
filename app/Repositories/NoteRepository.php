<?php

namespace App\Repositories;

use App\Models\Campaign\Note;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NoteRepository
{
    /**
     * @param int $campaignId
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function get(int $campaignId, int $page = 1, int $pageSize = 20): LengthAwarePaginator
    {
        return Note::where('campaign_id', $campaignId)->paginate($pageSize, ['*'], 'page[number]', $page);
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
    }

    /**
     * @param int $campaignId
     * @param int $noteId
     * @return Note
     */
    public function find(int $campaignId, int $noteId): Note
    {
        return Note::where(['campaign_id' => $campaignId, 'id' => $noteId])->firstOrFail();
    }

    /**
     * @param int $campaignId
     * @param int $noteId
     * @param array $data
     */
    public function update(int $campaignId, int $noteId, array $data)
    {
        $note = Note::where([
            'campaign_id' => $campaignId,
            'id' => $noteId
        ])->firstOrFail();
        $note->name = $data['name'];
        $note->content = $data['content'];
        $note->private = $data['private'] ?? false;
        $note->save();
    }

    /**
     * @param int $campaignId
     * @param int $noteId
     */
    public function destroy(int $campaignId, int $noteId)
    {
        Note::where(['campaign_id' => $campaignId, 'id' => $noteId])->delete();
    }
}