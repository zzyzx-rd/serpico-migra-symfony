<?php
/**
 * Created by IntelliJ IDEA.
 * User: lawre
 * Date: 14/05/2018
 * Time: 10:06
 */

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class AddPictureForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('pictureFile', FileType::class,
            [
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'image/png',
                            'image/bmp',
                            'image/jpeg'
                        ]
                    ]),
                    new Assert\NotBlank
                ],
                'mapped' => false
            ]);
    }

}
