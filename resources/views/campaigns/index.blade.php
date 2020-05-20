@extends('layout.app')

@section('content')
    <div class="campaigns">
        <h1>Campaigns</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <a class="uk-button uk-button-primary" href="/campaigns/create">
                    <i class="fas fa-plus"></i> Add campaign
                </a>
                @if ($campaigns->count() > 0)
                <table class="uk-table uk-table-divider">
                    <thead>
                    <tr>
                        <th class="uk-table-shrink"></th>
                        <th>Name</th>
                        <th>Role</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($campaigns as $campaign)
                    <tr>
                        <td>
                            @can('update', $campaign)
                            <a href="{{ route('campaigns.edit', ['campaign' => $campaign]) }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                        </td>
                        <td><a href="{{ route('campaigns.show', ['campaign' => $campaign]) }}">{{ $campaign->name }}</a></td>
                        <td>{{ $campaign->role }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                    <p class="uk-text-center">
                        <span>You don't have any campaigns yet.</span>
                    </p>
                @endif
            </div>
        </div>
    </div>
@stop