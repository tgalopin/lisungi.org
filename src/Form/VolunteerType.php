<?php

namespace App\Form;

use App\Entity\Volunteer;
use App\MatchFinder\Locality;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class VolunteerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['required' => true])
            ->add('lastName', TextType::class, ['required' => true])
            ->add('email', EmailType::class, ['required' => true])
            ->add('phone', TextType::class, ['required' => false])
            ->add('locality', ChoiceType::class, ['required' => true, 'choices' => Locality::LOCALITIES['fr_CD']])
            ->add('confirm_legal', CheckboxType::class, ['required' => true, 'mapped' => false, 'constraints' => [
                new NotBlank(),
            ]])
            ->add('confirm', CheckboxType::class, ['required' => true, 'mapped' => false, 'constraints' => [
                new NotBlank(),
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Volunteer::class,
        ]);
    }
}
