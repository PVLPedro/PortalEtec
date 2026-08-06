<?php

return [

    'index' => [
        'filters' => [
            'role' => 'Tipo de Usuário',
            'role_placeholder' => 'Cargo',
            'role_options' => [
                'aluno' => 'Aluno',
                'professor' => 'Professor',
                'coordenador' => 'Coordenador',
            ],

            'school_class' => 'Turma pertencente',
            'school_class_placeholder' => 'Turma',

            'course' => 'Curso designado',
            'course_placeholder' => 'Curso',

            'grade' => 'Série',
            'grade_placeholder' => 'Série',

            'rm' => 'RM do Aluno',
            'rm_placeholder' => 'RM',
        ],
    ],

    'table' => [
        'select_users' => 'Selecionar usuários',
        'cancel_selection' => 'Cancelar seleção',
        'select_all_tooltip' => 'Selecionar todos',
        'clear_selection_tooltip' => 'Limpar seleção',
        'invert_selection_tooltip' => 'Inverter seleção',

        'add_to_class_button' => 'Adicionar à Turma',
        'add_to_class_modal' => [
            'title' => 'Adicionar à Turma',
            'select_class_placeholder' => 'Selecione uma Turma',
            'create_new_class_button' => 'Criar nova turma',
            'course_placeholder' => 'Selecione um curso',
            'grade_placeholder' => 'Selecione uma série',
            'shift_placeholder' => 'Selecione um turno',
            'back_to_existing_button' => 'Adicionar a uma existente',
            'cancel' => 'Cancelar',
            'confirm' => 'Confirmar',
        ],

        'delete_selected_button' => 'Excluir selecionados',
        'delete_selected_modal' => [
            'title' => 'Exclusão de Usuários',
            'confirm_label' => 'Confirme sua senha para excluir',
            'selected_suffix' => 'usuário(s) selecionado(s):',
            'password_placeholder' => 'Sua senha',
            'cancel' => 'Cancelar',
            'submit' => 'Excluir selecionados',
            'confirm_js' => 'Excluir os usuários selecionados? Esta ação não pode ser desfeita.',
        ],

        'headers' => [
            'name' => 'Nome',
            'email' => 'Email',
            'role' => 'Cargo',
            'actions' => 'Ações',
        ],

        'edit_tooltip' => 'Editar',
    ],

    'edit' => [
        'label' => [
            'name' => 'Nome',
            'email' => 'E-mail',
            'delete_password' => 'Confirme sua senha para excluir este usuário',
        ],
        'submit' => 'Salvar',
        'delete_button' => 'Excluir usuário',
        'delete_confirm_js' => 'Excluir este usuário? Esta ação não pode ser desfeita.',
    ],

];