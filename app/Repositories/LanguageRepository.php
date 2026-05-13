<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;

class LanguageRepository extends Repository
{
    public static $path = '/languages';
    public static function model()
    {
        return Language::class;
    }

    public static function checkFileExitsOrNot(array $fileNames)
    {
        foreach ($fileNames as $name) {
            if (!self::isNameExists($name)) {
                self::create([
                    'title' => $name,
                    'name' => $name,
                ]);
            }
        }
    }

    public static function storeByRequest(LanguageRequest $request)
    {
        $thumbnailId = null;
        if ($request->hasFile('image')) {
            $thumbnail = MediaRepository::storeByRequest(
                $request->image,
                self::$path,
                'Image'
            );
            $thumbnailId = $thumbnail->id;
        }

        $filePath = base_path("lang/$request->name.json");

        $jsonData = file_get_contents(public_path('web/emptyLanguage.json'));

        file_put_contents($filePath, $jsonData);

        return self::create([
            'title' => $request->title,
            'name' => $request->name,
            'thumbnail_id' => $thumbnailId,
        ]);
    }

    public static function updateByRequest(Language $language, LanguageRequest $request, $filePath): Language
    {
        $thumbnailId = null;
        if ($request->hasFile('image')) {
            $thumbnail = MediaRepository::updateOrCreateByRequest(
                $request->image,
                self::$path,
                'Image',
                $language->thumbnail
            );
            $thumbnailId = $thumbnail->id;
        }

        file_put_contents($filePath, json_encode($request->data, JSON_PRETTY_PRINT));

        $language->update([
            'title' => $request->title,
            'thumbnail_id' => $thumbnailId ? $thumbnailId : $language->thumbnail_id,
        ]);

        return $language;
    }

    public static function isNameExists($name)
    {
        return self::query()->where('name', $name)->exists();
    }
}
