<?php

namespace App\Http\Resources;

use App\Models\Character\Background;
use App\Models\Character\Proficiency;
use Illuminate\Http\Resources\Json\JsonResource;

class BackgroundResource extends JsonResource
{
    /** @var Background */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $background = $this->resource->toArray();
        $includes = explode(',', $request->query('include', ''));
        if (in_array('languages', $includes)) {
            $background['languages'] = $this->resource->languages()->get();
        }
        if (in_array('features', $includes)) {
            $background['features'] = $this->resource->features()->get();
        }
        if (in_array('proficiencies', $includes)) {
            $background['skills'] = $this->resource->proficiencies()
                ->where('type', 'Skills')
                ->get();
            $background['tools'] = $this->resource->proficiencies()
                ->where('type', '!=', 'Skills')
                ->get();
        }
        return $background;
    }
}
