<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->admin && Auth::user()->active;
    }

    public function rules(): array
    {
        $race = $this->route('race');
        $ignoreId = empty($race) ? null : $race->id;

        return [
            'name' => ['required', 'string', Rule::unique('races', 'name')->ignore($ignoreId)],
            'description' => ['required', 'string'],
            'speed' => ['required', 'integer'],
            'size' => ['required', 'string', Rule::in(['Tiny', 'Small', 'Medium', 'Large', 'Huge', 'Gargantuan'])],

            'ability_bonuses' => ['required', 'array'],
            'ability_bonuses.*.ability' => ['required', 'string', Rule::in(['STR', 'DEX', 'CON', 'INT', 'WIS', 'CHA'])],
            'ability_bonuses.*.bonus' => ['required', 'integer'],
            'ability_bonuses.*.optional' => ['required', 'boolean'],

            'languages' => ['required', 'array'],
            'languages.*.id' => ['required', 'integer', 'exists:proficiencies'],
            'languages.*.optional' => ['required', 'boolean'],

            'proficiencies' => ['required', 'array'],
            'proficiencies.*.id' => ['required', 'integer', 'exists:proficiencies'],
            'proficiencies.*.optional' => ['required', 'boolean'],

            'traits' => ['nullable', 'array'],
            'traits.*.id' => ['nullable', 'integer', 'exists:proficiencies'],
            'traits.*.name' => ['required_without:traits.*.id', 'string', 'unique:traits,name'],
            'traits.*.description' => ['required_without:traits.*.id', 'string'],
        ];
    }
}
