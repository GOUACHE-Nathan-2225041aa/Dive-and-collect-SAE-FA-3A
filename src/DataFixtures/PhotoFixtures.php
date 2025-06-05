<?php

namespace App\DataFixtures;

use App\Entity\Coordonnee;
use App\Entity\EspecePoisson;
use App\Entity\Photo;
use App\Entity\Utilisateur;
use DateTime;
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
        $imageLinksByEspece = [
            'Aloxotl' => [
                'https://www.aquarium-st-malo.com/fileadmin/user_upload/ga_saint_malo/Axolotl.jpg',
                'https://animals.sandiegozoo.org/sites/default/files/2019-09/hero-axolotl.jpg',
                'https://images2.minutemediacdn.com/image/upload/c_crop,x_0,y_217,w_2115,h_1189/c_fill,w_1440,ar_1440:810,f_auto,q_auto,g_auto/images/voltaxMediaLibrary/mmsport/mentalfloss/01gwscsvw2yrt73s9sqj.jpg',
                'https://t3.ftcdn.net/jpg/04/73/13/12/360_F_473131207_iX7QNLVevrJBreOCJMnmMTDJRkdjRL7I.jpg',
                'https://preview.redd.it/2vu4pyh2l0o61.jpg?width=640&crop=smart&auto=webp&s=a1332b7eb4e08799f1027e5aa215eefd694ecff8',
            ],
            'Méduse immortelle' => [
                'https://www.airzen.fr/wp-content/uploads/2022/10/La-meduse-pourrait-encore-livrer-des-secrets-a-la-science-e1664181179809.jpg',
                'https://trustmyscience.com/wp-content/uploads/2019/10/t-dohrnii-meduse-immortelle-couv.jpg',
                'https://cdn.futura-sciences.com/buildsv6/images/largeoriginal/4/7/e/47e2d04864_50205149_2-meduse-immortelle.jpg',
                'https://media.lematin.ch/4/image/2023/11/08/ddad7fe3-52d3-42b9-a976-4c5c6f55da92.jpeg?auto=format%2Ccompress%2Cenhance&fit=max&w=1200&h=1200&rect=0%2C0%2C538%2C523&fp-x=0.4739776951672863&fp-y=0.12619502868068833&s=de87c1c1f8dce3d600e27fe777c5aace',
                'https://static.nationalgeographic.fr/files/styles/image_3200/public/1turritopsis-dohrnii.webp?w=1600&h=900',
            ],
            'Poisson-lune' => [
                'https://indonesie-tourisme.fr/wp-content/uploads/poisson-lune-mola-mola-1024x683.jpeg',
                'https://parc-marin-golfe-lion.fr/sites/default/files/styles/pnm_paragraphe_wysiwyg_media_image/public/media/2020-07/Poisson-lune_2.jpg?h=0e20087c&itok=Dg8o-cbo',
                'https://97px.s3.amazonaws.com/photos/df4b22ce-90ac-42ae-a6b8-0838bb846c18/full.jpg',
                'https://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Sunfish2.jpg/1200px-Sunfish2.jpg',
                'https://plongeurbaroudeur.com/wp-content/uploads/2022/06/ac71c-mola-mola-sunfish0-min.jpg?w=616',
            ],
            'Crabe yeti' => [
                'https://static.projects.iq.harvard.edu/files/styles/os_files_medium/public/imls/files/yeti2.png',
                'https://img.lemde.fr/2015/06/24/0/200/2160/1440/1440/960/60/0/518b0dd_31518-1mnz7ew.png',
                'https://cherry.img.pmdstatic.net/fit/https.3A.2F.2Fimg.2Emaxisciences.2Ecom.2Farticle.2Fantarctique.2Fles-chercheurs-ont-egalement-decouvert-des-centaines-de-crabes-yeti-d-une-longueur-de-16-centimetres-credits-oxford-university-southampton-university-british-antarctic-survey_e80424300a638563aaad4f89983e7823f1909d80.2Ejpg/1200x675/quality/80/thumbnail.jpg',
                'https://static.nationalgeographic.fr/files/styles/image_3200/public/02yeticrab.jpg?w=1900&h=1387',
                'https://i.servimg.com/u/f44/14/54/43/82/crabe_10.jpg',
            ],
            'Hippocampe pygmée' => [
                'https://plongez.fr/wp-content/uploads/2020/07/hippocampe_pygme.jpg',
                'https://www.fishipedia.es/wp-content/uploads/2020/06/Hippocampus-colemani-Indonesia-South-Molucca-Saparua.jpg',
                'https://www.francebleu.fr/s3/cruiser-production-eu3/2025/03/e1758f4e-eb8c-4407-b8bf-7c2b9c9850b6/1200x680_sc_250401-fich-diff-hipoccampe-androz-zen-500px-getty.jpg',
                'https://i.ytimg.com/vi/uL6L_QSfxec/maxresdefault.jpg',
                'https://ultramarina.com/thumb/ar__x/f__jpg/h__288/q__60/w__420/zc__1/src/fichier/p_item/153237/item_img_fr_indonesie_plongee_tongian_walea_rich_smith_018.jpg',
            ],
            'Dauphin' => [
                'https://dolphinesse.fr/wp-content/uploads/2020/04/images-manquantes-articles-2.jpg',
                'https://www.calanques-parcnational.fr/sites/calanques-parcnational.fr/files/styles/slide_1500_1000/public/dauphin-bleu-et-blanc-f-larrey-regard-du-vivant.jpg?itok=60LlCSWw',
                'https://www.nicecotedazur.org/wp-content/uploads/2023/09/DAUPHIN_METROPOLE_1600x840.jpg',
                'https://static.nationalgeographic.fr/files/styles/image_3200/public/dq_camera-1.jpg',
                'https://www.francebleu.fr/s3/cruiser-production-eu3/2025/01/18bb3905-6f4d-4c64-ad21-41fd24ece9d3/1200x680_sc_dauphin-commun-corse.jpg',
            ],
            'Narval' => [
                'https://iconicanimals.wordpress.com/wp-content/uploads/2017/07/narval.jpg',
                'https://www.monde-animal.fr/wp-content/uploads/2020/04/narval-mer.jpg',
                'https://animauxmarins.fr/wp-content/uploads/2022/03/narval-5.jpg',
                'https://static.wixstatic.com/media/584692_bd252836e1ee433495665daf4953d68c~mv2.jpg/v1/fill/w_1000,h_493,al_c,q_85,usm_0.66_1.00_0.01/584692_bd252836e1ee433495665daf4953d68c~mv2.jpg',
                'https://igui-ecologia.s3.amazonaws.com/wp-content/uploads/2016/08/narval2.jpg',
            ],
        ];

        $users = $this->doctrine->getRepository(Utilisateur::class)->findAll();
        if (empty($users)) {
            throw new \RuntimeException('Users needed');
        }

        $photosDirectory = $this->photosDirectory;
        if (!is_dir($photosDirectory)) {
            mkdir($photosDirectory, 0777, true);
        }

        $slugger = new AsciiSlugger();
        $index = 0;

        foreach ($imageLinksByEspece as $nomEspece => $urls) {
            $espece = $this->doctrine->getRepository(EspecePoisson::class)->findOneBy(['nom' => $nomEspece]);
            if (!$espece) {
                echo "Specie not found $nomEspece\n";
                continue;
            }

            foreach ($urls as $url) {
                $basename = basename(parse_url($url, PHP_URL_PATH));
                $extension = pathinfo($basename, PATHINFO_EXTENSION);
                $name = pathinfo($basename, PATHINFO_FILENAME);

                $safeName = $slugger->slug($name)->lower();
                $filename = $safeName . '-' . uniqid() . '.' . $extension;
                $destination = $photosDirectory . '/' . $filename;

                $imageData = @file_get_contents($url);
                if ($imageData === false) {
                    echo "Download failed for $url\n";
                    continue;
                }

                file_put_contents($destination, $imageData);

                $photo = new Photo();
                $photo->setImageFileName($filename);

                $date = new DateTime();
                $date->setDate(2025, rand(1,12), rand(1,25));

                $photo->setDateAdded($date);
                $photo->setEspece($espece);
                $photo->setAuteur($users[array_rand($users)]);

                $coord = new Coordonnee();
                $coord->setLatitude(mt_rand(-9000, 9000) / 100);
                $coord->setLongitude(mt_rand(-18000, 18000) / 100);
                $manager->persist($coord);

                $photo->setCoordonnees($coord);

                $manager->persist($photo);
                $nom = $espece->getNom();
                $this->addReference("photo_$index", $photo);
                $index++;
            }
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            EspecePoissonFixtures::class,
            UtilisateurFixtures::class,
        ];
    }
}
