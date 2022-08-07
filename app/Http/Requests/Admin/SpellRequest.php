<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SpellRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->admin && Auth::user()->active;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique('spells', 'name')->ignore($this->route('spell'))],
            'description' => ['required', 'string'],
            'higher_levels' => ['nullable', 'string'],
            'level' => ['required', 'integer', 'between:0,9'],
            'casting_time' => ['required', 'string'],
            'duration' => ['required', 'string'],
            'range' => ['required', 'string'],
            'school' => [
                'required',
                'string',
                Rule::in([
                    'Abjuration',
                    'Conjuration',
                    'Divination',
                    'Enchantment',
                    'Evocation',
                    'Illusion',
                    'Necromancy',
                    'Transmutation'
                ])
            ],
            'ritual' => ['boolean'],
            'concentration' => ['boolean'],
            'components' => ['required', 'array'],
            'components.*' => ['required', 'string', 'in:V,S,M'],
        ];
    }
}
