<?php

namespace App\Enums;

enum Role: string
{
    case Aluno = 'aluno';
    case Professor = 'professor';
    case Coordenador = 'coordenador';
}