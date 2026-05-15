<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminUpgradeRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'invitation_code' => ['required', 'string'],
        ]);

        $user = $request->user();

        abort_unless($user->isUser(), 403);

        if ($user->hasPendingAdminUpgradeRequest()) {
            return back()->with('status', 'Your admin upgrade request is already pending.');
        }

        if ($request->string('invitation_code')->toString() !== '123456') {
            throw ValidationException::withMessages([
                'invitation_code' => 'The invitation code is invalid.',
            ]);
        }

        $user->forceFill([
            'admin_upgrade_requested_at' => now(),
        ])->save();

        return back()->with('status', 'Your admin upgrade request has been submitted.');
    }
}
