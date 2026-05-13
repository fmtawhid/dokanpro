<?php

namespace App\Http\Controllers;

use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use App\Repositories\LanguageRepository;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = LanguageRepository::getAll();

        return view('language.index', compact('languages'));
    }

    public function create()
    {
        return view('language.create');
    }

    public function store(LanguageRequest $request)
    {
        $path = base_path('lang/') . $request->name . '.json';

        if (!file_exists($path)) {
            LanguageRepository::storeByRequest($request);

            return to_route('language.index')->withSuccess('Created Successfully');
        }

        return back()->withError('language already exits');
    }
   
    public function edit(Language $language)
    {
        $filePath = base_path('lang/') . $language->name . '.json'; // directory
        if (file_exists($filePath)) {
            $languageData = json_decode(file_get_contents($filePath), true);

            return view('language.edit', compact('languageData', 'language'));
        }

        return back()->withError('Base folder or file not found');
    }

    public function update(LanguageRequest $request, Language $language)
    {
        $filePath = base_path('lang/') . $language->name . '.json'; // directory

        if (file_exists($filePath)) {

            LanguageRepository::updateByRequest($language, $request, $filePath);

            return to_route('language.index')->withSuccess('Updated Successfully');
        }

        return back()->withError('File does not exist');
    }

    public function delete(Language $language)
    {
        $filePath = base_path('lang/') . $language->name . '.json'; // directory
        if ($language->name != 'en') {
            // unlink($filePath);
            LanguageRepository::delete($language->id);

            return to_route('language.index')->withSuccess('Deleted Successfully');
        }

        return back()->withError('File does not exist');
    }
}
