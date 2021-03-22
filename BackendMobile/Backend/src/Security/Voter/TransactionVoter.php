<?php

namespace App\Security\Voter;

use App\Entity\Transaction;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TransactionVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'VIEW'])
            && $subject instanceof \App\Entity\Transaction;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                return $user-> getRoles()[0] === "ROLE_ADMIN" || $user-> getRoles()[0] === "ROLE_ADMIN_AGENCE" || $user-> getRoles()[0] === "ROLE_CLIENT" || $user-> getRoles()[0] === "ROLE_CAISSIER";
                break;
            case 'VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                return $user -> getRoles()[0] === "ROLE_ADMIN" || $user-> getRoles()[0] === "ROLE_ADMIN_AGENCE" || $user-> getRoles()[0] === "ROLE_CLIENT" || $user-> getRoles()[0] === "ROLE_CAISSIER";
                break;
        }

        return false;
    }
}
