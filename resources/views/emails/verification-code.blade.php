<!DOCTYPE html>
<html lang="pt-BR">
    <body style="margin:0;padding:24px;background:#f4f4f5;font-family:Arial,Helvetica,sans-serif;color:#18181b;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center">
                    <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;padding:32px;">
                        <tr>
                            <td>
                                <h1 style="font-size:20px;margin:0 0 16px;">Portal Etec</h1>

                                <p style="margin:0 0 12px;">Olá, {{ $user->name }}.</p>

                                @if ($purpose === 'new_device')
                                    <p style="margin:0 0 12px;">
                                        Alguém tentou acessar a sua conta no Portal Etec a partir de um dispositivo que
                                        ainda não conhecemos. Se foi você, digite o código abaixo na tela de
                                        confirmação.
                                    </p>
                                @elseif ($purpose === 'first_access')
                                    <p style="margin:0 0 12px;">
                                        Este endereço de e-mail foi informado no primeiro acesso de uma conta de aluno
                                        no Portal Etec. Se foi você, digite o código abaixo na tela de confirmação.
                                    </p>
                                @else
                                    <p style="margin:0 0 12px;">
                                        Uma conta de {{ $user->role->value }} foi criada no Portal Etec usando este
                                        endereço de e-mail. Se foi você, digite o código abaixo na tela de
                                        confirmação para liberar o acesso.
                                    </p>
                                @endif

                                <p style="margin:24px 0;text-align:center;font-size:32px;font-weight:bold;letter-spacing:8px;">
                                    {{ $code }}
                                </p>

                                <p style="margin:0 0 12px;">
                                    O código vale por {{ $minutes }} minutos. Não compartilhe este código com ninguém.
                                </p>

                                <p style="margin:24px 0 8px;font-weight:bold;">Não foi você?</p>
                                <p style="margin:0 0 12px;">
                                    Se você não reconhece essa tentativa, ignore o código e cancele a confirmação
                                    clicando no link abaixo. Isso bloqueia a tentativa de acesso.
                                </p>
                                <p style="margin:0;">
                                    <a href="{{ $cancelUrl }}" style="color:#2563eb;">Cancelar esta confirmação</a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
