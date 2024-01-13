<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class LocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $campaignId = Session::get('campaign_id');
        return [
            'name' => ['required', 'string'],
            'type' => ['required', 'string'],
            'description' => 'string',
            'location_id' => [
                'nullable',
                'integer',
                Rule::exists('locations', 'id')->where(function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })
            ],
            'map' => ['image', 'dimensions:max_height=2560,max_width=2560', 'max:8192'],
            'permissions' => ['sometimes', 'nullable', 'array'],
            'permissions.*.view' => ['required', 'boolean'],
            'permissions.*.edit' => ['required', 'boolean'],
            'permissions.*.delete' => ['required', 'boolean'],
        ];
    }
}
