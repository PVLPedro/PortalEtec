<?php

return [

    'title' => 'Profile',
    'save' => 'Save',
    'saved' => 'Saved.',

    'information' => [
        'title' => 'Profile Information',
        'description' => "Update your account's profile information and email address.",

        'label' => [
            'name' => 'Name',
            'email' => 'Email',
        ],

        'unverified_email' => 'Your email address is unverified.',
        'resend_verification' => 'Click here to re-send the verification email.',
        'verification_sent' => 'A new verification link has been sent to your email address.',
    ],

    'password' => [
        'title' => 'Update Password',
        'description' => 'Ensure your account is using a long, random password to stay secure.',

        'label' => [
            'current_password' => 'Current Password',
            'new_password' => 'New Password',
            'confirm_password' => 'Confirm Password',
        ],
    ],

    'delete' => [
        'title' => 'Delete Account',
        'description' => 'Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.',
        'button' => 'Delete Account',

        'modal' => [
            'title' => 'Are you sure you want to delete your account?',
            'description' => 'Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.',
            'password_placeholder' => 'Password',
            'cancel' => 'Cancel',
            'confirm_button' => 'Delete Account',
        ],
    ],

];