<?php

namespace App\Services;

use App\Models\DocumentType;
use App\Models\User;
use Exception;

class UserService
{
    /**
     * Retorna a função do usuário, baseado em seu tipo de documento.
     *
     * @return string
     */
    public function getRole(User $user): string
    {
        switch ($user->document_type_id) {
            case DocumentType::CPF_ID:
                return User::CPF_NAME;
            case DocumentType::CNPJ_ID:
                return User::CNPJ_NAME;
            default:
                throw new Exception("There is no role assigned to the document type id {$user->document_type_id}.");
        }
    }
}
