<?php

namespace App\Form;

use App\Entity\OffresEmplois;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffresEmploisForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('secteur_activite')
            ->add('titre_poste')
            ->add('ville')
            ->add('departement')
            ->add('type_contrat')
            ->add('detail_poste')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffresEmplois::class,
        ]);
    }
}
