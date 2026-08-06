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

    'welcome' => 'Welcome to Portal Etec!',

    'login' => [
        'label' => [
            'email' => 'Enter your institutional email',
            'password' => 'Enter your password',
        ],
        'remember_me' => 'Remember me',

        'forgot_password' => 'Forgot your password?',

        'no_account' => "Don't have an account? Create one",

        'log_in' => 'Log in',
    ],

    'register' => [
        'label' => [
            'name' => 'Your full name',

            'role' => 'User type',
            'role_select' => 'Select a role',

            'rm' => 'Student ID',

            'etec_student' => 'Select your Etec',
            'etec_worker' => 'Select your Etecs',

            'email' => 'Institutional email',
            'password' => 'Create a password',
        ],
        'already_account' => 'Already have an account? Log in',

        'sign_up' => 'Create Account',

        'max_etec' => 'You can only select :max Etec',
    ],

    'rules' => [
        'password' => 'The password must contain:',
        'password_min_max' => 'Between 8 and 20 characters',
        'password_special' => 'One special character (! @ # $...)',
        'password_number' => 'One number (0 to 9)',
    ],

    'domain' => [
        'student' => '@aluno.cps.sp.gov.br',
        'worker' => '@cps.sp.gov.br',
    ],

    'placeholder' => [
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'rm' => 'Enter your Student ID',
        'etec_worker' => 'Etec you work at (you can select multiple)',
        'etec_student' => 'Etec you study at',
    ],

    'forgot_password' => [
        'description' => 'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
        'label' => [
            'email' => 'Email',
        ],
        'submit' => 'Email Password Reset Link',
    ],

    'confirm_password' => [
        'description' => 'This is a secure area of the application. Please confirm your password before continuing.',
        'label' => [
            'password' => 'Password',
        ],
        'submit' => 'Confirm',
    ],

    'reset_password' => [
        'label' => [
            'email' => 'Email',
            'password' => 'Password',
            'confirm_password' => 'Confirm Password',
        ],
        'submit' => 'Reset Password',
    ],

    'verify_email' => [
        'description' => "Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.",
        'link_sent' => 'A new verification link has been sent to the email address you provided during registration.',
        'resend' => 'Resend Verification Email',
        'logout' => 'Log Out',
    ],

    'failed' => 'These credentials do not match our records.',
    'incorrect_password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
];