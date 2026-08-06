<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'welcome' => 'Bem-vindo(a) ao Portal Etec!',

    'login' => [
        'label' => [
            'email' => 'Digite seu email institucional',
            'password' => 'Digite sua senha',
        ],
        'remember_me' => 'Continuar conectado',

        'forgot_password' => 'Esqueceu sua senha?',

        'no_account' => 'Não possui uma conta? Crie uma',

        'log_in' => 'Entrar',
    ],

    'register' => [
        'label' => [
            'name' => 'Seu nome completo',

            'role' => 'Tipo de usuário',
            'role_select' => 'Selecione um cargo',

            'rm' => 'RM',

            'etec_student' => 'Selecione sua Etec',
            'etec_worker' => 'Selecione suas Etecs',

            'email' => 'Email institucional',
            'password' => 'Crie uma senha',
        ],
        'already_account' => 'Já possui uma conta? Entre com ela',

        'sign_up' => 'Criar Conta',

        'max_etec' => 'Você só pode selecionar :max Etec',
    ],

    'rules' => [
        'password' => 'A senha precisa conter:',
        'password_min_max' => 'De 8 a 20 caracteres',
        'password_special' => 'Um caractere especial (! @ # $...)',
        'password_number' => 'Um caractere numérico (0 a 9)',
    ],

    'domain' => [
        'student' => '@aluno.cps.sp.gov.br',
        'worker' => '@cps.sp.gov.br',
    ],

    'placeholder' => [
        'name' => 'Nome',
        'email' => 'Email',
        'password' => 'Senha',
        'rm' => 'Digite seu RM de Aluno',
        'etec_worker' => 'Etec na qual trabalha (Pode selecionar múltiplas)',
        'etec_student' => 'Etec na qual estuda',
    ],

    'forgot_password' => [
        'description' => 'Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de e-mail que enviaremos um link de redefinição de senha para você escolher uma nova.',
        'label' => [
            'email' => 'Email',
        ],
        'submit' => 'Enviar Link de Redefinição de Senha',
    ],

    'confirm_password' => [
        'description' => 'Esta é uma área segura da aplicação. Confirme sua senha antes de continuar.',
        'label' => [
            'password' => 'Senha',
        ],
        'submit' => 'Confirmar',
    ],

    'reset_password' => [
        'label' => [
            'email' => 'Email',
            'password' => 'Senha',
            'confirm_password' => 'Confirmar Senha',
        ],
        'submit' => 'Redefinir Senha',
    ],

    'verify_email' => [
        'description' => 'Obrigado por se cadastrar! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar? Se você não recebeu o e-mail, ficaremos felizes em enviar outro.',
        'link_sent' => 'Um novo link de verificação foi enviado para o endereço de e-mail informado no cadastro.',
        'resend' => 'Reenviar E-mail de Verificação',
        'logout' => 'Sair',
    ],

    'failed' => 'As credenciais não coincidem.',
    'incorrect_password' => 'A senha está incorreta.',
    'throttle' => 'Muitas tentativas. Tente novamente em :seconds segundos.',
];