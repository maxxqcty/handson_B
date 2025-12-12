<?php

namespace App\Form;

use App\Entity\BurialPlot;
use App\Entity\BurialRecord;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BurialPlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plot_number')
            ->add('section')
            ->add('row_num')
            ->add('is_occupied')
            ->add('size')
            ->add('notes')
            ->add('burialRecord', EntityType::class, [
                'class' => BurialRecord::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BurialPlot::class,
        ]);
    }
}
