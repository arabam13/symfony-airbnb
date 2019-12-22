<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends AbstractType
{

    /**
     * Permet d'obtenir la configuration d'un champ !
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options=[]){
        return array_merge([
            'label'=>$label,
            'attr'=>[
                'placeholder'=>$placeholder
            ]
        ], $options);

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre', 'Titre de l\'annonce'))
            ->add('slug', TextType::class, 
                    $this->getConfiguration('Adresse Web', "Adresse Web", ['required'=> false]))
            ->add('coverImage', UrlType::class, $this->getConfiguration('Url de l\'image principale', "Url"))
            ->add('introduction', TextType::class, $this->getConfiguration('Intro', "Introduction"))
            ->add('content', TextareaType::class, $this->getConfiguration('Contenu', "Contenu"))
            ->add('rooms', IntegerType::class, 
                    $this->getConfiguration('Nombre de chambres', "Taper le nombre de chambres dispo"))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix', "Prix"))
            ->add('images',  
                  CollectionType::class, [
                      'entry_type' => ImageType::class,
                      'allow_add' => True,
                      'allow_delete' => True
                  ]
                  
                 )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
