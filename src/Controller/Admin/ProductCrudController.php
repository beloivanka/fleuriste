<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            AssociationField::new('category'),
            BooleanField::new('favorite'),
            IntegerField::new('price', 'Price €'),
            IntegerField::new('stock'),
            TextareaField::new('description'),
            DateTimeField::new('createdAt', 'Created at')->hideOnForm(),
            ImageField::new('image')
                ->setUploadDir('public/assets/uploads/') // le répertoire de téléchargement
                ->setBasePath('assets/uploads/') // le chemin de base pour l'affichage de l'image
                ->setRequired($pageName !== Crud::PAGE_EDIT),//le champ requis lors de la création, 
                                                            //mais facultatif lors de la modification
        ];
    }
}
