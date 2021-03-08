<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form\Type;

use Controller\MasterController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\Participation;
use App\Entity\User;

class ParticipantGradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $currentUser = $this->user;;
        if (!$currentUser instanceof User) {
            throw 'Authentication issue: no authorized user found';
        }
        
       
        $builder->add('receivedGrades', CollectionType::class,
            [
                'entry_type' => GradeType::class,
                'by_reference' => false,
                'label' => false,
            ]
        );
        

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'user_update.%name%',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light teal lighten-1 user-submit',
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',Participation::class);
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('stageMode', 1);
        $resolver->addAllowedTypes('standalone', 'bool');
        /*$resolver->setDefaults([
            'inherit_data' => true,
        ]);*/
    }

}
