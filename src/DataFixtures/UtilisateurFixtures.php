<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use App\Entity\Utilisateur;
use App\Repository\BadgeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class UtilisateurFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private BadgeRepository $badgeRepository;
    private ManagerRegistry $doctrine;
    private string $logosDirectory;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        BadgeRepository $badgeRepository,
        ManagerRegistry $doctrine,
        ParameterBagInterface $params
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->badgeRepository = $badgeRepository;
        $this->doctrine = $doctrine;
        $this->logosDirectory = $params->get('logos_directory');
    }

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();

        $photosDirectory = $this->logosDirectory;
        if (!is_dir($photosDirectory)) {
            mkdir($photosDirectory, 0777, true);
        }

        $pfpUrls = [
            'https://randomuser.me/api/portraits/men/1.jpg',
            'https://randomuser.me/api/portraits/women/2.jpg',
            'https://randomuser.me/api/portraits/men/3.jpg',
            'https://randomuser.me/api/portraits/women/4.jpg'
        ];

        $ongData = [
            ['email' => 'oceansave@example.com', 'nomOng' => 'Ocean Save', 'username' => 'Marie', 'points' => 0, 'badgeNoms' => 'Badge 1', 'roles' => ['ROLE_ONG']],
            ['email' => 'forestguard@example.com', 'nomOng' => 'Forest Guard', 'username' => 'Lucas','points' => 20, 'badgeNoms' => 'Badge 2','roles' => ['ROLE_ONG']],
            ['email' => 'planetcare@example.com', 'nomOng' => '', 'username' => 'Sophie','points' => 150, 'badgeNoms' => 'Badge 3','roles' => ['ROLE_USER']],
            ['email' => 'admin@admin.com', 'nomOng' => '', 'username' => 'admin','points' => 10, 'badgeNoms' => 'Badge 3','roles' => ['ROLE_USER','ROLE_ADMIN']],
        ];

        foreach ($ongData as $index => $data) {
            $user = new Utilisateur();
            $user->setEmail($data['email']);
            $user->setNomOng($data['nomOng']);
            $user->setUsername($data['username']);
            $user->setRoles($data['roles']);
            $user->setPoints($data['points']);
            $user->setBadge($this->badgeRepository->findOneBy(['nom' => $data['badgeNoms']]));
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'password')
            );

            // ======== Traitement de la photo de profil ========
            $url = $pfpUrls[$index % count($pfpUrls)];
            $basename = basename(parse_url($url, PHP_URL_PATH));
            $extension = pathinfo($basename, PATHINFO_EXTENSION);
            $name = pathinfo($basename, PATHINFO_FILENAME);

            $safeName = $slugger->slug($name)->lower();
            $filename = $safeName . '-' . uniqid() . '.' . $extension;
            $destination = $photosDirectory . '/' . $filename;

            $imageData = @file_get_contents($url);
            if ($imageData !== false) {
                file_put_contents($destination, $imageData);
                $user->setLogoFileName($filename); // À adapter selon ton entité
            }

            $manager->persist($user);
            $this->addReference("utilisateur_$index", $user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BadgeFixtures::class
        ];
    }
}
