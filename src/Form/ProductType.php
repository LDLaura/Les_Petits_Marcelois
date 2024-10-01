<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'required' => true 
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu'
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image', 
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image actuelle',
                'download_uri' => false,
                'download_label' => false,
                'image_uri' => true,
                'asset_helper' => true,
                
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
