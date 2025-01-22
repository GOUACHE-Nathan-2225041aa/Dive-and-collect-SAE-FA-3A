<?php
// src/Trait/FormProfilTrait.php
namespace App\Trait;

use App\Form\ChangeAvatarType;
use App\Form\ChangeProfileMailType;
use App\Form\ChangeProfilPassWordType;
use App\Form\ProfileModificationType;
use App\Form\SendMailAdminNewSpotType;
use App\Form\UploadCertificatType;
use Symfony\Component\Security\Core\User\UserInterface;

trait FormProfilTrait
{
protected function createForms(UserInterface $user): array
    {
        return [
            'formMailSpot' => $this->createForm(SendMailAdminNewSpotType::class),
            'formemail' => $this->createForm(ChangeProfileMailType::class, $user),
            'formprof' => $this->createForm(ProfileModificationType::class, $user),
            'formPassword' => $this->createForm(ChangeProfilPassWordType::class),
            'formcertificat' => $this->createForm(UploadCertificatType::class, $user),
            'formavatar' => $this->createForm(ChangeAvatarType::class, $user),
        ];
    }
}
