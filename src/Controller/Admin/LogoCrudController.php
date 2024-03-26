<?php

namespace App\Controller\Admin;

use App\Entity\Logo;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LogoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Logo::class;
    }
    // public function configureActions(Actions $actions): Actions
    // {
    // return $actions
    //     ->remove(Crud::PAGE_INDEX, Action::NEW)
    //     ->remove(Crud::PAGE_INDEX, Action::DELETE);
    // }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ImageField::new('logo')
            ->setUploadDir('public/assets/logo/')
            ->setBasePath('assets/logo/')
            ->setRequired($pageName !== Crud::PAGE_EDIT),
        ];
    }

}
