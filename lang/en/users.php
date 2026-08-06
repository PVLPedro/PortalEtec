<?php

return [

    'index' => [
        'filters' => [
            'role' => 'User Type',
            'role_placeholder' => 'Role',
            'role_options' => [
                'aluno' => 'Student',
                'professor' => 'Teacher',
                'coordenador' => 'Coordinator',
            ],

            'school_class' => 'Class',
            'school_class_placeholder' => 'Class',

            'course' => 'Assigned Course',
            'course_placeholder' => 'Course',

            'grade' => 'Grade',
            'grade_placeholder' => 'Grade',

            'rm' => 'Student ID',
            'rm_placeholder' => 'Student ID',
        ],
    ],

    'table' => [
        'select_users' => 'Select users',
        'cancel_selection' => 'Cancel selection',
        'select_all_tooltip' => 'Select all',
        'clear_selection_tooltip' => 'Clear selection',
        'invert_selection_tooltip' => 'Invert selection',

        'add_to_class_button' => 'Add to Class',
        'add_to_class_modal' => [
            'title' => 'Add to Class',
            'select_class_placeholder' => 'Select a Class',
            'create_new_class_button' => 'Create new class',
            'course_placeholder' => 'Select a course',
            'grade_placeholder' => 'Select a grade',
            'shift_placeholder' => 'Select a shift',
            'back_to_existing_button' => 'Add to an existing one',
            'cancel' => 'Cancel',
            'confirm' => 'Confirm',
        ],

        'delete_selected_button' => 'Delete selected',
        'delete_selected_modal' => [
            'title' => 'User Deletion',
            'confirm_label' => 'Confirm your password to delete',
            'selected_suffix' => 'selected user(s):',
            'password_placeholder' => 'Your password',
            'cancel' => 'Cancel',
            'submit' => 'Delete selected',
            'confirm_js' => 'Delete the selected users? This action cannot be undone.',
        ],

        'headers' => [
            'name' => 'Name',
            'email' => 'Email',
            'role' => 'Role',
            'actions' => 'Actions',
        ],

        'edit_tooltip' => 'Edit',
    ],

    'edit' => [
        'label' => [
            'name' => 'Name',
            'email' => 'Email',
            'delete_password' => 'Confirm your password to delete this user',
        ],
        'submit' => 'Save',
        'delete_button' => 'Delete user',
        'delete_confirm_js' => 'Delete this user? This action cannot be undone.',
    ],

];