<?php

namespace App\Form\write;

use App\Entity\HeadingPages;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddModifyHeadingPagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titlePage', TextType::class, [
                'label' => 'Titre de l\'article',
                'data' => $options['titleHeadingPage']
            ])
            ->add('contentPage', TextareaType::class, [
                'label' => "Contenu",
                'data' => $options['contentHeadingPage']
            ])
            ->add('idHeading', HiddenType::class, [
                'data' => $options['idHeading']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HeadingPages::class,
            'titleHeadingPage'=> '',
            'contentHeadingPage'=> '',
            'idHeading' => ''
        ]);
    }
}
