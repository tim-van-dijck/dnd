<?php

use App\Models\Character\CharacterClass;
use App\Models\Character\Feature;
use App\Models\Character\Subclass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class FeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = json_decode(file_get_contents(resource_path('json/Features.json')), true);
        $classes  = CharacterClass::get()->keyBy('name');
        $subclasses = Subclass::get()->keyBy('name');
        foreach ($features as $featureArray) {
            $feature = new Feature();
            if (!empty($featureArray['class'])) {
                $feature->class_id = $this->getClassId($classes, $featureArray);
            }
            if (!empty($featureArray['subclass'])) {
                $feature->subclass_id = $this->getSubclassId($subclasses, $featureArray);
            }
            $feature->name = $featureArray['name'];
            $feature->level = $featureArray['level'] ?? 0;
            $feature->description = implode('<br>', $featureArray['desc']);
            $feature->save();
        }
    }

    /**
     * @param Collection $classes
     * @param array $featureArray
     * @return int
     */
    private function getClassId(Collection $classes, array $featureArray): int
    {
        $className = $featureArray['class']['name'];
        return $classes[$className]->id;
    }

    /**
     * @param Collection $subclasses
     * @param array $featureArray
     * @return int
     */
    private function getSubclassId(Collection $subclasses, array $featureArray): ?int
    {
        $className = $featureArray['class']['name'];
        return empty($subclasses[$className]) ? null : $subclasses[$className]->id;
    }
}
