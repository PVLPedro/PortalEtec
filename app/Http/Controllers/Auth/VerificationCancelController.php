<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use Illuminate\View\View;

class VerificationCancelController extends Controller
{
    public function __invoke(int $verification): View
    {
        $record = EmailVerification::with('user')->find($verification);

        if (!$record) {
            return view('auth.verification-cancelled', [
                'message' => 'Esta verificação já foi concluída ou cancelada. Nenhuma ação é necessária.',
            ]);
        }

        $user = $record->user;

        if ($record->purpose === 'register' && $user->email_verified_at === null) {
            $user->delete();

            return view('auth.verification-cancelled', [
                'message' => 'O cadastro feito com este e-mail foi cancelado e removido. Nenhuma ação é necessária.',
            ]);
        }

        $record->update(['cancelled_at' => now()]);

        return view('auth.verification-cancelled', [
            'message' => $record->purpose === 'new_device'
                ? 'O acesso por este dispositivo foi bloqueado. Se você não reconhece essa tentativa, recomendamos trocar a sua senha.'
                : 'A confirmação deste e-mail foi cancelada.',
        ]);
    }
}
