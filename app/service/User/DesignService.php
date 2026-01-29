<?php

namespace App\Service\User;

use App\Models\Design;
use App\Models\DesignImage;
use App\Models\DesignOptionSelection;
use App\Models\Measurement;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class DesignService
{
    /**
     * Create a new class instance.
     */
    protected $measurementservic;
    public function __construct(measurementservic $measurementservic)
    {
        $this->measurementservic = $measurementservic;
    }

    public function index($request)
    {
        $designs = Design::with([
            'measurement',
            'optionSelections.designOption',
        ]);
        if ($search = $request->input('search')) {
            $designs->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                ;


            });
        }


        if ($request->input('measurement_id')) {
            $designs->where('measurement_id', $request->input('measurement_id'));
        }
        if ($min_price=$request->input('min_price')) {
            $designs->where('price',">=",$min_price);
        }
         if ($max_price=$request->input('max_price')) {
            $designs->where('price',"<=", $max_price);
        }
        if ($request->input('user_id')) {
            $designs->where('user_id', $request->input('user_id'));
        }
        if ($design_option = $request->input('design_option_id')) {
            $designs->whereHas('optionSelections.designOption', function ($q) use ($design_option) {
                $q->where('id', $design_option);
            });


        return $designs->get();
    }}

    public function show($id)
    {
        $design = Design::with([
            'measurements',
            'optionSelections.designOption',
        ])->find($id);

        return $design;
    }

    public function create(array $data)
    {
        $userId = Auth::id();

        $options = $data['options'] ?? [];


    

       // $measurement = $this->measurementservic->create($measurement);

        $design = Design::create(array_merge(
            $data,
            ['user_id' => $userId]
        ));
        $design->measurements()->sync($data['measurement_ids']);


        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $design->images()->create([
                    'url' => ImageService::uploadImage($image, 'designs'),
                ]);
            }
        }
        $design = $design->fresh();

        foreach ($options as $option) {
            DesignOptionSelection::create([
                'design_id' => $design->id,
                'design_option_id' => $option['design_option_id'],
                'value' => $option['value'] ?? null,
            ]);
        }

        return $design->load([
            'images',
            'measurements',
            'optionSelections.designOption',
        ]);
    }

    public function update(array $data, $id)
    {

        $design = Design::with(
            'measurements',
            'optionSelections.designOption',
        )->where("user_id", Auth::id())->find($id);

        if ($design == null) {
            return null;
        }

        $images = $data['images'] ?? null;
        $options = $data['options'] ?? null;


        $design->update($data);
        if (isset($data['measurement_ids'])) {
        $design->measurements()->sync($data['measurement_ids']);
    }

        if (!is_null($images)) {
            foreach ($design->images as $img) {
                Storage::disk('public')->delete($img->url);
            }
            $design->images()->delete();

            foreach ($images as $file) {
                if ($file instanceof UploadedFile) {
                    $path = ImageService::uploadImage($file, 'designs');

                    $design->images()->create([
                        'url' => $path,
                    ]);
                }
            }
        }

        if (!is_null($options)) {
            $design->optionSelections()->delete();

            foreach ($options as $option) {
                DesignOptionSelection::create([
                    'design_id' => $design->id,
                    'design_option_id' => $option['design_option_id'],
                    'value' => $option['value'] ?? null,
                ]);
            }
        }

        return $design->load([
            'images',
            'measurements',
            'optionSelections.designOption',
        ]);
    }

    public function delete($id)
    {
        $design = Design::with(
            'measurement',
            'optionSelections.designOption',
        )->where("user_id", Auth::id())->find($id);

        if ($design == null) {
            return null;
        }

        foreach ($design->images as $img) {
            Storage::disk('public')->delete($img->url);
        }

        $design->delete();

        return true;
    }

}
