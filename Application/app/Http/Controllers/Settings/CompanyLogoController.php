<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings; // Presupunând că ai un model pentru setări
use App\Models\TemporaryFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class CompanyLogoController extends Controller
{
    /**
     * Afișează pagina pentru încărcarea logo-ului.
     */
    public function show()
    {
        
        $currentLogo = Settings::where('key', 'company_logo')->first();

        return Inertia::render('Settings/Logo', [
            'currentLogoUrl' => $currentLogo ? $currentLogo->value : null,
        ]);
    }

    /**
     * Salvează noul logo al companiei.
     */
    public function store(Request $request)
    {
        $request->validate([
            'logo_file_id' => 'required|integer|exists:temporary_files,id',
        ]);

        $fileId = $request->input('logo_file_id');
        $tmpFile = TemporaryFiles::find($fileId);

        if (blank($tmpFile)) {
            return back()->with('error', 'Fișierul temporar nu a fost găsit.');
        }

       
        $sourcePath = storage_path($tmpFile->file_path);
        $destinationDirectory = storage_path('app/public/company');
        
        if (!File::isDirectory($destinationDirectory)) {
            File::makeDirectory($destinationDirectory, 0755, true, true);
        }

        $destinationPath = $destinationDirectory . '/' . $tmpFile->file_name;
        File::copy($sourcePath, $destinationPath);

        
        $publicPath = '/storage/company/' . $tmpFile->file_name;
        
        Settings::updateOrCreate(
    ['key' => 'company_logo'], 
    [
        'value' => $publicPath,   
        'type'  => 'file'          
    ]
);

       
        File::delete($sourcePath);
        $tmpFile->delete();

        return redirect()->route('settings.logo.show')->with('success', 'Logo-ul a fost actualizat cu succes.');
    }
    public function destroy()
    {
        $setting = Settings::where('key', 'company_logo')->first();

        if ($setting) {
            
            $filePath = Str::replaceFirst('/storage/', 'public/', $setting->value);
            $fullPath = storage_path('app/' . $filePath);

            
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
            $setting->delete();
        }

        return back()->with('success', 'Logo-ul a fost șters cu succes.');
    }
}