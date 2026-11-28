<div align="center">

# 🎓 Portal ETEC

**Uma plataforma de aprendizagem feita sob medida para as ETECs.**
Comunicação direta entre escola e aluno, turmas, disciplinas, comunicados e atividades em um só lugar.

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=black)
![Vite](https://img.shields.io/badge/Vite-8-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-18-4169E1?style=for-the-badge&logo=postgresql&logoColor=white)
![Pest](https://img.shields.io/badge/Tests-Pest-F28D1A?style=for-the-badge)

[Sobre](#-sobre-o-projeto) •
[Funcionalidades](#-funcionalidades) •
[Tecnologias](#-tecnologias) •
[Instalação](#-como-executar) •
[Estrutura](#-estrutura-do-projeto) •
[Equipe](#-equipe)

</div>

---

## 📖 Sobre o projeto

O **Portal ETEC** é o Trabalho de Conclusão de Curso (TCC) da turma de **Desenvolvimento de Sistemas** da **ETEC Professor Camargo Aranha**.

O projeto propõe uma solução tecnológica para **aprimorar a comunicação entre escola e aluno**, criando uma nova plataforma de aprendizagem inspirada no **Microsoft Teams** e no **Google Classroom**, mas **especializada na realidade das ETECs**: contas institucionais do Centro Paula Souza, cursos técnicos, turmas divididas em "Turma A / Turma B / Composto", turnos, séries e múltiplas unidades.

### 🎯 Problema que resolve

Ferramentas genéricas de sala de aula não conhecem a estrutura de uma ETEC. O Portal ETEC centraliza em um único ambiente o que hoje costuma ficar espalhado entre vários aplicativos: avisos, tarefas, prazos, materiais e organização das turmas.

### 👥 Para quem é

| Perfil | O que faz no portal |
| --- | --- |
| 🧑‍🎓 **Aluno** | Acompanha sua turma, disciplinas, comunicados e atividades, baixa materiais e comenta nas tarefas |
| 👩‍🏫 **Professor** | Cria e gerencia turmas, publica comunicados e atividades com prazo e anexos |
| 🧑‍💼 **Coordenador** | Gerencia usuários, cadastra alunos em lote, organiza turmas e vincula disciplinas |

---

## ✨ Funcionalidades

### 🔐 Autenticação e segurança
- Login separado por perfil (aluno, professor e coordenador)
- **Validação de domínio de e-mail institucional**: `@aluno.cps.sp.gov.br` para alunos e `@cps.sp.gov.br` para professores e coordenadores
- **Primeiro acesso do aluno** por RM, com cadastro de e-mail e **verificação por código** enviado por e-mail
- **Dispositivos confiáveis**: novos dispositivos precisam ser verificados
- Recuperação e troca de senha, confirmação de senha em ações sensíveis
- Controle de acesso por perfil (*middleware* de papéis e *policies*)

### 🏫 Turmas e disciplinas
- Criação, edição e exclusão de turmas
- Divisão por lado: **Turma A**, **Turma B** ou **Composto**
- Vínculo de disciplinas e professores a cada turma
- Visão "Minha turma" para o aluno

### 📢 Comunicados e 📝 Atividades
- Mural de **comunicados** por turma
- **Atividades** com título, descrição, prazo e anexos de arquivos
- Opção de **habilitar ou desabilitar comentários** em cada atividade
- Status de entrega: *Pendente, Entregue, Atrasada* e *Corrigida*
- Atividades direcionadas a um lado específico da turma

### 🛠️ Gestão (coordenação)
- Listagem, filtro, edição e exclusão de usuários (inclusive em massa)
- **Cadastro de alunos em lote** com exportação do resultado em **CSV**
- Adição de um ou vários usuários a uma turma
- Troca de contexto entre **diferentes ETECs**

### 🎨 Experiência de uso
- Painel inicial (*dashboard*) específico para cada perfil
- **Tema claro e escuro**
- Interface responsiva com Tailwind CSS e ícones Lucide
- Interface totalmente em **português (pt-BR)**
- Catálogo pré-carregado de ETECs, cursos, turnos e séries

---

## 🧰 Tecnologias

| Camada | Tecnologia |
| --- | --- |
| **Back-end** | PHP 8.3+, Laravel 13 |
| **Front-end** | Blade, Tailwind CSS 4, Alpine.js, Vite, Blade Lucide Icons |
| **Banco de dados** | PostgreSQL (desenvolvimento da equipe) · SQLite (padrão do `.env.example`) |
| **E-mail** | Mailgun (via Symfony Mailer) |
| **Testes** | Pest 4 |
| **Qualidade de código** | Laravel Pint (PSR-12), Prettier (com plugins Blade, PHP e Tailwind) |
| **Ferramentas** | Composer, npm, Laravel Pail, Laravel IDE Helper |

---

## 📚 Documentação do TCC

A documentação acadêmica fica em um repositório separado:

👉 **[TCC-3DA](https://github.com/Oda-Yuji/TCC-3DA)**

Nele estão a monografia (versões da documentação), relatórios, modelos ABNT, padronizações de Tailwind CSS e protótipos iniciais do banco de dados.

---

## 🗺️ Próximos passos

- [ ] Entrega de atividades pelo aluno com envio de arquivos
- [ ] Notas e feedback do professor
- [ ] Notificações por e-mail para novos comunicados e prazos
- [ ] Calendário de atividades
- [ ] Deploy em ambiente de produção

---

## 👨‍💻 Equipe

**ETEC Professor Camargo Aranha** · Curso Técnico em **Desenvolvimento de Sistemas**

| Integrante | GitHub |
| --- | --- |
| Davi de Vasconcelos Furigo | [@FurigoDavi](https://github.com/FurigoDavi) |
| Felipe Yuji Oda Majer | [@Oda-Yuji](https://github.com/Oda-Yuji) |
| Gustavo Lopes Gianelli | [@tronezitos](https://github.com/tronezitos) |
| Pedro Henrique Custódio Brito | [@PVLPedro](https://github.com/PVLPedro) |
| Samuel Lírio Dayne | [@OwiOmni](https://github.com/OwiOmni) |

---

## 📄 Licença

Projeto acadêmico desenvolvido para fins educacionais.

<div align="center">

Feito com 💙 por alunos da ETEC Prof. Camargo Aranha

</div>
