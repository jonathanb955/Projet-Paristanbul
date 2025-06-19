<?php

namespace App\Form;

use App\Entity\Magasins;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MagasinsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ville_magasin')
            ->add('rue')
            ->add('image')
            ->add('cp')
            ->add('num_tel')
            ->add('horaire_ouverture')
            ->add('horaire_fermeture')
            ->add('jours_ouverture')
            ->add('video_magasin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Magasins::class,
        ]);
    }
}
