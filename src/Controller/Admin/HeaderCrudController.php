<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Header')
            ->setEntityLabelInPlural('Headers');
    }

    public function configureFields(string $pageName): iterable
    {
        $reqired = true;
        if ($pageName === 'edit') {
            $reqired = false;
        }
        return [

            TextField::new('title')->setLabel('Titre'),
            TextareaField::new('content')->setLabel('Contenu'),
            TextField::new('buttonTitle')->setLabel('Titre du button'),
            TextField::new('buttonLink')->setLabel('URL du button'),
            ImageField::new('illustration')
                ->setLabel('Image de fond')
                ->setHelp('Image de fond header')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setUploadDir('/public/uploads')
                ->setBasePath('/uploads')
                ->setRequired($reqired),
        ];
    }
}
