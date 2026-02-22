<?php

namespace App\Http\Controllers\Gestion;

use App\Helpers\HelpFaq;
use Illuminate\View\View;

class HelpController extends BaseController
{
    public function index(): View
    {
        $user = auth()->user();
        $roleName = $user->role->name ?? '';
        $faqs = HelpFaq::getByRole($roleName);

        return view('gestion.help.index', [
            'faqs' => $faqs,
            'roleName' => $roleName,
        ]);
    }
}
