@extends('layout.app')

@section('content')
    <div class="campaigns">
        <a class="uk-button uk-button-primary" href="/campaigns/create">
            <i class="fas fa-plus"></i> Add campaign
        </a>
        @if ($campaigns->count() > 0)
        <table class="uk-table uk-table-divider">
            <thead>
            <tr>
                <th class="uk-table-shrink"></th>
                <th>Name</th>
                <th>Class</th>
                <th>Level</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="character in characters.data">
                <td>
                    @can('update', $campaign)
                    <a href="/campaigns/{{ $campaign->id }}/edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endcan
                </td>
                <td>{{ $campaign->name }}</td>
                <td>{{ $campaign->role }}</td>
                <td></td>
            </tr>
            </tbody>
        </table>
        @else
            <p class="uk-text-center">
                <span>You don't have any campaigns yet.</span>
            </p>
        @endif
    </div>
@stop