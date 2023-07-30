<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Requests\JournalEntryRequest;
use App\Http\Resources\JournalEntryResource;
use App\Models\Campaign\JournalEntry;
use App\Services\AuthService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class JournalController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $entries = JournalEntry::query()
            ->where('campaign_id', Session::get('campaign_id'))
            ->orderBy('order')
            ->get();
        return JournalEntryResource::collection($entries);
    }

    public function store(JournalEntryRequest $request): JournalEntryResource
    {
        $campaignId = Session::get('campaign_id');

        $journalEntry = new JournalEntry($request->input());
        $journalEntry->campaign_id = $campaignId;
        $journalEntry->order = JournalEntry::query()->where('campaign_id', $campaignId)->count() + 1;
        $journalEntry->save();

        return new JournalEntryResource($journalEntry);
    }

    public function show(JournalEntry $journalEntry): JournalEntryResource
    {
        return new JournalEntryResource($journalEntry);
    }

    public function update(JournalEntryRequest $request, JournalEntry $journalEntry): JournalEntryResource
    {
        if (Session::get('campaign_id') != $journalEntry->campaign_id) {
            throw new ModelNotFoundException();
        }

        $journalEntry->fill($request->input());
        $journalEntry->save();
        return new JournalEntryResource($journalEntry);
    }

    public function destroy(JournalEntry $journalEntry): void
    {
        if (Session::get('campaign_id') != $journalEntry->campaign_id) {
            throw new ModelNotFoundException();
        }

        JournalEntry::query()
            ->where('campaign_id', Session::get('campaign_id'))
            ->where('order', '>', $journalEntry->order)
            ->decrement('order');

        $journalEntry->delete();
    }

    public function sort(Request $request): void
    {
        if (!AuthService::userHasCampaignPermission(Auth::user(), null, 'journal', 'edit')) {
            abort(403);
        }

        $list = $request->input('list', []);
        $entries = JournalEntry::whereIn('id', $list)->get()->keyBy('id');
        foreach ($list as $key => $id) {
            $entry = $entries[$id] ?? null;
            if ($entry) {
                $entry->order = $key + 1;
                $entry->save();
            }
        }
    }
}
