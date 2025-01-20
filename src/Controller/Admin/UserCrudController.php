<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Component\Form\FormEvents;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    
    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher)
    {
        
    }
    
    
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


      
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('civility')->setChoices([
                'Monsieur' => 'Mr',
                'Madame' => 'Mme',
                'Madmoiselle' => 'Mlle'
            ]),
            TextField::new('full_name'),
            EmailField::new('email'),
            TextField::new('password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Password',
                        'row_attr' => [
                            'class' => 'col-md-6 col-xxl-5'
                        ],
                    ],
                    'second_options' => [
                        'label' => 'ConfirmPassword',
                        'row_attr' => [
                            'class' => 'col-md-6 col-xxl-5'
                        ],
                    ],
                    'mapped' => false,
                ])
                ->setRequired($pageName  === Crud::PAGE_NEW)
            ->onlyOnForms(),
            // TextEditorField::new('description'),
        ];
    }
    
    // De que on detecte la création qui est intialisation de formBuilder
    public function createNewFormBuilder(
        EntityDto $entityDto, 
        KeyValueStore $formOptions, 
        AdminContext $context) : FormBuilderInterface
    {
         $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);

         // On fait sistematiquement apell a addPasswordEventListener qui lui s'occupe d'ajouter un evenement sur le formBuilder
         return $this->addPasswordEventListener($formBuilder);
    }

      
    // De que on detecte l'edition qui est intialisation de formBuilder
    public function createEditFormBuilder(
        EntityDto $entityDto, 
        KeyValueStore $formOptions, 
        AdminContext $context) : FormBuilderInterface
    {
         $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);

         // On fait sistematiquement apell a addPasswordEventListener qui lui s'occupe d'ajouter un evenement sur le formBuilder
         return $this->addPasswordEventListener($formBuilder);
    }
    
    public function addPasswordEventListener(FormBuilderInterface $formBuilder) {
        // On creée un evenment sur le formBouilder
        // quand le evenment sera declacher lors de sumetre les données on realise l'action de hashage de mod de passe
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    } 


    public function hashPassword() {
        // on returne l'evenement qui et declancher
        return function($event){
            // on recupere les form sur cette evenment
            $form = $event->getForm();
            // on verifi si le formulaire est valid
            if(!$form->isValid()){
                // si le form ne pas valide on fait un return 
                return;
            }
            // Si valide alors on recupere le mdp avec le getData pour récupere les données 
            $password = $form->get('password')->getData('password');

            // on regarde si diferent de null
            if ($password === null) {
                return;
            }

            // Encoder le mdp 
            $hash = $this->userPasswordHasher->hashPassword($this->getUser(), $password);
            // Et on stock les données
            $form->getData()->setPassword($hash);
        };
    }

}
