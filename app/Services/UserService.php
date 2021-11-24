<?php

namespace App\Services;

use App\Models\DocumentType;
use App\Models\User;
use Exception;

class UserService
{
    /**
     * Loga o usuário e retorna o seu token, com seus dados.
     *
     * @param array $userInformation
     * @return array
     */
    public function login(array $userInformation): array
    {
        if (! auth()->attempt($userInformation)) {
            return [
                'success' => false,
                'message' => "E-mail or password are incorrect.",
            ];
        }

        $user = User::where('email', '=', $userInformation['email'])
            ->firstOrFail();

        return [
            'success' => true,
            'message' => [
                'token' => "Bearer {$user->createToken('auth_token')->plainTextToken}",
            ],
        ];
    }

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
