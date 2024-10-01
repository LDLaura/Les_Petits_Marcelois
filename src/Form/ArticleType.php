<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'rows' => 10,
                ],
            ])

            ->add('enable', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
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

            ->add('categories', EntityType::class, [
                'label' => 'Catégories', 
                'class' => Categorie::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => true,
                // 'autocomplete' => true,
                //To display only active categories and sort them alphabetically
                'query_builder' => function(CategorieRepository $repo): QueryBuilder{
                    return $repo->createQueryBuilder('c')
                    ->andwhere('c.enable = true')
                    ->orderBy('c.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
