<?php

namespace App\Repositories;

use App\Models\Character\Subrace;

class SubraceRepository
{

    public function destroy(Subrace $subrace): void
    {
        $subrace->abilities()->delete();
        $subrace->languages()->sync([]);
        $subrace->proficiencies()->sync([]);
        $subrace->spells()->sync([]);
        $subrace->traits()->sync([]);
        $subrace->delete();
    }
}