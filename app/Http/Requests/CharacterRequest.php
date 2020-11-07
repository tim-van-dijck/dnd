<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CharacterRequest extends FormRequest
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
        if ($this->input('type') == 'player') {
            return $this->pcRules();
        }
        return $this->npcRules();
    }

    private function pcRules(): array
    {
        return array_merge($this->generalRules(), [
            'classes' => 'required|array|min:1',
            'classes.*.class_id' => 'required|integer|exists:classes,id',
            'classes.*.subclass_id' => 'required|integer|exists:subclasses,id',
            'classes.*.level' => 'required|integer|between:1,20',

            'background_id' => 'nullable|exists:backgrounds,id',

            'proficiencies' => 'required',
            'proficiencies.languages' => 'sometimes|array',
            'proficiencies.languages.*' => 'required|integer|exists:languages,id',

            'proficiencies.skills' => 'required|array',
            'proficiencies.skills.*.id' => 'required|integer|exists:proficiencies,id',
            'proficiencies.skills.*.origin_id' => 'required|integer',
            'proficiencies.skills.*.origin_type' => 'required|string|in:class,subclass,race,subrace,background',

            'proficiencies.tools' => 'sometimes|array',
            'proficiencies.tools.*.id' => 'required|integer|exists:proficiencies,id',
            'proficiencies.tools.*.origin_id' => 'required|integer',
            'proficiencies.tools.*.origin_type' => 'required|string|in:class,subclass,race,subrace,background',

            'proficiencies.instruments' => 'sometimes|array',
            'proficiencies.instruments.*.id' => 'required|integer|exists:proficiencies,id',
            'proficiencies.instruments.*.origin_id' => 'required|integer',
            'proficiencies.instruments.*.origin_type' => 'required|string|in:class,subclass,race,subrace,background',

            'ability_scores' => 'required|array|size:6',
            'ability_scores.*' => 'required|integer|min:3|max:20',

            'personality.ideal' => 'sometimes|nullable|string',
            'personality.bond' => 'sometimes|nullable|string',
            'personality.flaw' => 'sometimes|nullable|string',
            'personality.trait' => 'sometimes|nullable|string'
        ]);
    }

    private function generalRules()
    {
        return [
            'info.name' => 'required|string',
            'info.alignment' => 'required|string|in:LG,NG,CG,LN,TN,CN,LE,NE,CE',
            'info.race_id' => 'required|integer|exists:races,id',
            'info.subrace_id' => 'sometimes|integer|exists:subraces,id',
            'info.age' => 'sometimes|integer',
            'info.dead' => 'boolean',
            'info.private' => 'boolean',
            'info.bio' => 'sometimes|string',
            'personality' => 'sometimes'
        ];
    }

    private function npcRules(): array
    {
        return array_merge($this->generalRules(), [
            'info.title' => 'string',
            'info.type' => 'sometimes|string',
        ]);
    }
}
