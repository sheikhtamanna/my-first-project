<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Catagory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'required' => false,
                'empty_data' => '',
                'constraints' =>[
                    new NotBlank([
                        'message' => 'please fill in the title field'
                    ]),
                    // new Length([
                    //     'min' => 10,
                    //     'minMessage' => 'title must be minimum 10 caracters'
                    // ])
                ]
            ])
            ->add('color', TextType::class,[
                'required' => false,
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Please fill in the color field'
                    ])
                ]
            ])
            ->add('size', TextType::class,[
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'please fill in the size field'
                    ])
                ]
            ])
            ->add('photoForm', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'please fill in the photo field'
                    ])
                ]
            ])
            ->add('price', NumberType::class,[
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'please fill in the price fild'
                    ])
                ]
            ])
            ->add('stock', IntegerType::class,[
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'please fill in the stock field'
                    ])
                ]
            ])
            ->add('description',TextType::class,[
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'please fill in the description field'
                    ])
                ]
            ])
            ->add('catagory', EntityType::class, [
                'class' => Catagory::class,
                'choice_label' => 'name',
                'label' => 'choice a catagory'
            ])
            ->add('send', SubmitType::class )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
