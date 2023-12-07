<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\Form;

use huppys\CookieConsentBundle\Cookie\CookieChecker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CookieConsentType extends AbstractType
{
    protected CookieChecker $cookieChecker;
    protected array $cookieCategories;
    protected bool $csrfProtection;

    public function __construct(
        CookieChecker $cookieChecker,
        array         $cookieCategories,
        bool          $csrfProtection = true
    )
    {
        $this->cookieChecker = $cookieChecker;
        $this->cookieCategories = $cookieCategories;
        $this->csrfProtection = $csrfProtection;
    }

    /**
     * Build the cookie consent form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->cookieCategories as $category) {
            $builder->add($category, ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'data' => $this->cookieChecker->isCategoryAllowedByUser($category) ? 'true' : 'false',
                'choices' => [
                    ['cookie_consent.yes' => 'true'],
                    ['cookie_consent.no' => 'false'],
                ],
            ]);
        }

        $builder->add('use_only_functional_cookies', SubmitType::class, ['label' => 'cookie_consent.use_only_functional_cookies', 'attr' => ['class' => 'btn cookie-consent__btn']]);
        $builder->add('use_all_cookies', SubmitType::class, ['label' => 'cookie_consent.use_all_cookies', 'attr' => ['class' => 'btn cookie-consent__btn cookie-consent__btn--secondary']]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            foreach ($this->cookieCategories as $category) {
                $data[$category] = isset($data['use_all_cookies']) ? 'true' : 'false';
            }

            $event->setData($data);
        });
    }

    /**
     * Default options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'CookieConsentBundle',
            'csrf_protection' => $this->csrfProtection,
        ]);
    }
}
