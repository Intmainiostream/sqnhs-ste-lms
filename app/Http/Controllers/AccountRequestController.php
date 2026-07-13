<?php

namespace App\Http\Controllers;

use App\Models\AccountChangeRequest;
use Illuminate\Http\Request;

class AccountRequestController extends Controller
{
    public function index()
    {
        $requests = AccountChangeRequest::with('user.student')
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->get();

        return view('admin.account-requests', compact('requests'));
    }

    public function approve(AccountChangeRequest $accountChangeRequest)
    {
        $user = $accountChangeRequest->user;

        if ($accountChangeRequest->new_username) {
            $user->username = $accountChangeRequest->new_username;
        }
        if ($accountChangeRequest->new_email) {
            $user->email = $accountChangeRequest->new_email;
        }
        if ($accountChangeRequest->new_password) {
            $user->password = $accountChangeRequest->new_password;
        }
        $user->save();

        if ($accountChangeRequest->changes && $user->student) {
            $user->student->fill($accountChangeRequest->changes);
            $user->student->save();
        }

        $accountChangeRequest->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Request approved and applied.');
    }

    public function reject(Request $request, AccountChangeRequest $accountChangeRequest)
    {
        $data = $request->validate([
            'admin_remarks' => 'nullable|string|max:255',
        ]);

        $accountChangeRequest->update([
            'status' => 'rejected',
            'admin_remarks' => $data['admin_remarks'] ?? null,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Request rejected.');
    }
}