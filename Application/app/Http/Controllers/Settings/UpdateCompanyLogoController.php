<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\CompanyLogoFormRequest;
use App\Models\Settings;
use App\Models\TemporaryFiles;

class UpdateCompanyLogoController extends Controller
{
    public function __invoke(CompanyLogoFormRequest $formRequest)
    {
        $tmp = TemporaryFiles::find($formRequest->logo_file_id);

        if (!$tmp) {
            return redirect()
                ->back()
                ->with('error', 'Error while uploading the logo. Please try again.');
        }

        $tmp_storage = storage_path($tmp->file_path);
        $new_storage = storage_path('app/public/company/' . $tmp->file_name);
        
        if (!file_exists($tmp_storage)) {
            return redirect()
                ->back()
                ->with('error', 'Error while uploading the logo. Please try again.');
        }

        if (! file_exists($new_storage)) {
            copy($tmp_storage, $new_storage);
        }

        Settings::updateOrCreate(
            ['key' => 'logo'],
            [
                'type'  => 'string',
                'value' => '/storage/company/' . $tmp->file_name
            ]
        );

        return redirect()
            ->route('settings.logo')
            ->with('success', 'Logo updated successfully.');
    }
}
