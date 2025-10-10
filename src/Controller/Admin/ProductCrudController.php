<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits');
    }

    public function configureFields(string $pageName): iterable
    {
        $reqired = true;
        if ($pageName === 'edit') {
            $reqired = false;
        }
        return [

            TextField::new('name')->setLabel('Produit')->setHelp('Nome de prdouit'),
            BooleanField::new('isHomePage')->setLabel('Produit à la une ')->setHelp('Vour permet d\'afficher un produit dans la homePage'),
            SlugField::new('slug')->setLabel('URL')->setTargetFieldName('name')->setHelp('URL de produit'),
            TextEditorField::new('description')->setLabel('Description')->setHelp('Description de produit'),
            ImageField::new('illustration')
                ->setLabel('Image')
                ->setHelp('Image de produit en 600x600px')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setUploadDir('/public/uploads')
                ->setBasePath('/uploads')
                ->setRequired($reqired),
            NumberField::new('price')->setLabel('Prix H.T')->setHelp('Le prix hors taxe de produit sans le sigle €'),
            ChoiceField::new('tva')->setLabel('Taux da TVA')->setChoices([
                '5,5%' => '5.5',
                '10%' => '10',
                "20%" => '20'
            ]),
            AssociationField::new('category', 'catégorie associé'),
            
        ];
    }
}
