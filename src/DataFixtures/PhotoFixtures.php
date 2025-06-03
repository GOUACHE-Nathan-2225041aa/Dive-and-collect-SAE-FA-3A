<?php

namespace App\DataFixtures;

use App\Entity\EspecePoisson;
use App\Entity\Photo;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class PhotoFixtures extends Fixture implements DependentFixtureInterface
{
    private string $photosDirectory;

    public function __construct(
        private ManagerRegistry $doctrine,
        ParameterBagInterface $params
    ) {
        $this->photosDirectory = $params->get('photos_directory');
    }

    public function load(ObjectManager $manager): void
    {
        $imageLinks = [
            'https://www.novethic.fr/wp-content/uploads/fileadmin/iStock-1208022389-646x407.jpg',
            'https://www.imagesdoc.com/wp-content/uploads/sites/33/2020/02/shutterstock_141051217.jpg',
            'https://images.ctfassets.net/b85ozb2q358o/2MK3njJKIDkOnT5cYlmMU1/945b152ec2df013b9edb79fbab816447/d__co_aquarium_poissons_rouges_1_76011308.jpeg',
            'https://ici.exploratv.ca/upload/site/post/picture/1857/64b57d3a40c36.1747325833.jpg',
            'https://m.media-amazon.com/images/I/41SpHh6mHYL._AC_UF1000,1000_QL80_.jpg',
            'https://upload.wikimedia.org/wikipedia/en/4/46/Mr_Blobby_%28fish%29%2C_2003.jpg',
            'https://i.ytimg.com/vi/US0MR07C7wA/maxresdefault.jpg'
        ];

        $especes = $this->doctrine->getRepository(EspecePoisson::class)->findAll();
        $users = $this->doctrine->getRepository(Utilisateur::class)->findAll();

        if (empty($especes) || empty($users)) {
            throw new \RuntimeException('Tu dois avoir des espèces et des utilisateurs en base pour lancer cette fixture.');
        }

        $photosDirectory = $this->photosDirectory;
        if (!is_dir($photosDirectory)) {
            mkdir($photosDirectory, 0777, true);
        }

        $slugger = new AsciiSlugger();

        foreach ($imageLinks as $index => $url) {
            $basename = basename(parse_url($url, PHP_URL_PATH));
            $extension = pathinfo($basename, PATHINFO_EXTENSION);
            $name = pathinfo($basename, PATHINFO_FILENAME);

            $safeName = $slugger->slug($name)->lower();
            $filename = $safeName . '-' . uniqid() . '.' . $extension;
            $destination = $photosDirectory . '/' . $filename;

            $imageData = @file_get_contents($url);
            if ($imageData === false) {
                echo "Échec du téléchargement de $url\n";
                continue;
            }

            file_put_contents($destination, $imageData);

            $photo = new Photo();
            $photo->setImageFileName($filename);
            $photo->setDateAdded(new \DateTime(sprintf('2025-05-%02d', 15 - $index)));
            $photo->setEspece($especes[array_rand($especes)]);
            $photo->setAuteur($users[array_rand($users)]);

            $manager->persist($photo);
        }

        $manager->flush();
        echo "Fixtures photos générées avec succès !\n";
    }

    public function getDependencies(): array
    {
        return [
            EspecePoissonFixtures::class,
            UtilisateurFixtures::class,
        ];
    }
}
