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

            'rm' => 'Digite seu RM de Aluno',
            'etec_student' => 'Selecione sua Etec',
            'etec_worker' => 'Selecione suas Etecs',

            'email' => 'Email institucional',
            'password' => 'Crie uma senha',
        ],
        'already_account' => 'Já possui uma conta? Entre com ela',

        'sign_up' => 'Criar Conta',
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
    ],

    'failed' => 'As credenciais não coincidem.',
    'incorrect_password' => 'A senha está incorreta.',
    'throttle' => 'Muitas tentativas. Tente novamente em :seconds segundos.',
];
