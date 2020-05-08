<?php

namespace App\Form;

use App\Entity\Helper;
use App\MatchFinder\Locality;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class HelperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['required' => true])
            ->add('lastName', TextType::class, ['required' => true])
            ->add('email', EmailType::class, ['required' => true])
            ->add('locality', ChoiceType::class, ['required' => true, 'choices' => Locality::LOCALITIES['fr_CD']])
            ->add('company', TextType::class, ['required' => false])
            ->add('phone', TextType::class, ['required' => false])
            ->add('masks', NumberType::class, ['required' => false])
            ->add('glasses', NumberType::class, ['required' => false])
            ->add('blouses', NumberType::class, ['required' => false])
            ->add('gel', NumberType::class, ['required' => false])
            ->add('gloves', NumberType::class, ['required' => false])
            ->add('paracetamol', NumberType::class, ['required' => false])
            ->add('soap', NumberType::class, ['required' => false])
            ->add('food', TextType::class, ['required' => false])
            ->add('other', TextType::class, ['required' => false])
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
            'data_class' => Helper::class,
        ]);
    }
}
