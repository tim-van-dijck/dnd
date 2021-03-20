<?php

namespace App\Services\Character;

use App\Models\Character\Character;
use App\Models\Character\Proficiency;
use Dompdf\Dompdf;

class CharacterSheetBuilder
{
    public function getSheet(Character $character): Dompdf
    {
        $pdf = new Dompdf();
        $pdf->getOptions()->setChroot(storage_path());

        $pdf->loadHtml(view('pdf.character-sheet', $this->getData($character))->render());
        $filename = "Character Sheet {$character->name}.pdf";

        $pdf->render();
        $pdf->stream($filename);
    }

    private function getData(Character $character): array
    {
        $level = 0;
        $savingThrows = [];
        foreach ($character->classes as $class) {
            $level += $class->pivot->level;
            $savingThrows = array_merge($savingThrows, $class->saving_throws);
        }
        $proficiencyBonus = ceil($level / 4) + 1;
        $character->load('race');
        return [
            'abilityScores' => $character->ability_scores,
            'character' => $character,
            'level' => $level,
            'proficiencyBonus' => $proficiencyBonus,
            'savingThrows' => $savingThrows,
            'skills' => $this->getSkills($character)
        ];
    }

    private function getSkills(Character $character): array
    {
        $skills = Proficiency::query()
            ->where('type', 'Skills')
            ->orderBy('name')
            ->get(['name'])
            ->pluck('name')
            ->toArray();
        $proficiencies = $character->proficiencies()
            ->where('type', 'Skills')
            ->get(['name'])
            ->pluck('name')
            ->toArray();

        $result = [];
        foreach ($skills as $skill) {
            $result[$skill] = in_array($skill, $proficiencies);
        }
        return $result;
    }
}
