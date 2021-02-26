<?php

namespace App\Http\Requests;

use App\Models\Character\CharacterClass;
use App\Models\Character\Feature;
use App\Models\Character\Subclass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        return array_merge(
            $this->generalRules(),
            $this->classRules(),
            [

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
            'info.subrace_id' => 'nullable|integer|exists:subraces,id',
            'info.age' => 'sometimes|integer',
            'info.dead' => 'boolean',
            'info.private' => 'boolean',
            'info.bio' => 'nullable|sometimes|string',
            'personality' => 'sometimes'
        ];
    }

    private function npcRules(): array
    {
        return array_merge($this->generalRules(), [
            'info.title' => 'nullable|string',
            'info.type' => 'sometimes|string',
        ]);
    }

    private function classRules()
    {
        $rules = [
            'classes' => 'required|array|min:1',
            'classes.*.class_id' => 'required|integer|exists:classes,id',
            'classes.*.subclass_id' => 'nullable|integer|exists:subclasses,id',
            'classes.*.level' => 'required|integer|between:1,20',
        ];
        $input = $this->input('classes', []);
        if (!empty($classes)) {
            foreach ($input as $class) {
                $features = Feature::join('feature_morph AS fm', 'fm.feature_id', '=', 'features.id')
                    ->where([
                        ['fm.entity_type', '=', CharacterClass::class],
                        ['fm.entity_id', '=', $class['class_id']],
                        ['fm.level', '<=', $class['level']],
                        ['fm.choose', '>', 1],
                        ['optional', '=', 0]
                    ])
                    ->orWhere([
                        ['fm.entity_type', '=', Subclass::class],
                        ['fm.entity_id', '=', $class['subclass_id']],
                        ['fm.level', '<=', $class['level']],
                        ['fm.choose', '>', 1],
                        ['optional', '=', 0]
                    ])
                    ->groupBy(['f.id', 'fm.entity_id', 'fm.choose'])
                    ->get(['f.id', 'fm.entity_id', 'fm.choose']);
                if (!empty($features)) {
                    $rules["classes.{$class['class_id']}.features"] = 'required|array';
                    foreach ($features as $feature) {
                        $rules["classes.{$class['class_id']}.features.{$feature->id}"] = [
                            'required',
                            'array',
                            "size:{$feature->choose}"
                        ];
                        $choiceIds = Feature::join('feature_choices AS fc', 'fc.choice_id', '=', 'f.id')
                            ->where('fc.feature_id', $feature->id)
                            ->groupBy('fc.choice_id')
                            ->get(['fc.choice_id'])
                            ->pluck('fc.choice_id')
                            ->toArray();

                        $rules["classes.{$class['class_id']}.features.{$feature->id}.*"] = [
                            Rule::in($choiceIds)
                        ];
                    }
                }
            }
        }
        return $rules;
    }
}
