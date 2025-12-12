<?php

namespace App\Form;

use App\Entity\BurialRecord;
use App\Entity\Deceased;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BurialRecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('burial_date')
            ->add('funeral_home')
            ->add('record_created_at')
            ->add('record_updated_at')
            ->add('notes')
            ->add('deacesed', EntityType::class, [
                'class' => Deceased::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BurialRecord::class,
        ]);
    }
}
