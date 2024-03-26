<?php

namespace App\Controller\Admin;

use App\Entity\Logo;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderDetailsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/admin/order', name: 'admin_order')]
    public function manageOrders(OrderRepository $orderRepository, OrderDetailsRepository $orderDetailsRepository,
    PaginatorInterface $paginator, Request $request): Response
    {
        $orderDetails = $orderDetailsRepository->findAll();

        // On définit la pagination
        $orders = $paginator->paginate(
            $orderRepository->findAll(),
            $request->query->getInt('page', 1), //nombre de pages
            3 // limite par page
        );

        return $this->render('admin/orders.html.twig', [
            'orders' => $orders,
            'orderDetails' => $orderDetails
        ]);
    }

    // ID sera passé dans l'URL qui répond à cette méthode
    #[Route('/admin/order/{id}', name: 'admin_order_delete', methods: ['GET', 'POST'])]
    public function deleteOrder(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
            
        }
        return $this->redirectToRoute('admin_order', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/user', name: 'admin_user')]
    public function manageUsers(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    #[Route('/admin/category', name: 'admin_category')]
    public function manageCategories(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(CategoryCrudController::class)->generateUrl());
    }

    #[Route('/admin/product', name: 'admin_category')]
    public function manageProducts(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ProductCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Apple Store');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('All products', 'fas fa-list', Product::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-tags', Category::class);
        yield MenuItem::linkToUrl('Orders', 'fas fa-file-text', $this->generateUrl('admin_order'));
        yield MenuItem::linkToCrud('Logo', 'fas fa-tags', Logo::class);
        yield MenuItem::linkToRoute('Back to the website', 'fa fa-home', 'main');
        yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
    }
}
