<?php

namespace App\Controller\Admin;

use App\Entity\Dive;
use App\Entity\Gallery;
use App\Entity\Oceanarium;
use App\Entity\Question;
use App\Entity\Species;
use App\Entity\Spot;
use App\Entity\Subscription;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dive & Collect');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Back to the website', 'fas fa-home', 'homepage');
        yield MenuItem::linkToCrud('Users', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('Galleries', 'fa-solid fa-image', Gallery::class);
        yield MenuItem::linkToCrud('Species', 'fa-solid fa-fish', Species::class);
        yield MenuItem::linkToCrud('Oceanariums', 'fa-solid fa-book', Oceanarium::class);
        yield MenuItem::linkToCrud('Spots', 'fa-solid fa-location-dot', Spot::class);
        yield MenuItem::linkToCrud('Subscriptions', 'fa-solid fa-money-bill', Subscription::class);
        yield MenuItem::linkToCrud('Dives', 'fa-solid fa-camera', Dive::class);
        yield MenuItem::linkToCrud('Questions', 'fa-solid fa-user', Question::class);
    }
}
