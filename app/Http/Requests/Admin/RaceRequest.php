<?php

namespace App\Http\Requests\Admin;

use App\Models\Character\CharacterClass;
use App\Models\Character\Feature;
use App\Models\Character\Subclass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->admin;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'speed' => ['required', 'integer'],
            'size' => ['required', 'string', Rule::in(['Tiny', 'Small', 'Medium', 'Large', 'Huge', 'Gargantuan'])],

            'ability_bonuses' => ['required', 'array'],
            'ability_bonuses.*.id' => ['required', 'string', Rule::in(['STR', 'DEX', 'CON', 'INT', 'WIS', 'CHA'])],
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
