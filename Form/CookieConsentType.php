<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Form;

use ConnectHolland\CookieConsentBundle\Cookie\CookieHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CookieConsentType extends AbstractType
{
    /**
     * @var CookieHandler
     */
    protected $cookieHandler;

    /**
     * @var array
     */
    protected $cookieCategories;

    public function __construct(CookieHandler $cookieHandler, array $cookieCategories)
    {
        $this->cookieHandler    = $cookieHandler;
        $this->cookieCategories = $cookieCategories;
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
                'data'     => $this->cookieHandler->isCategoryPermitted($category) ? 'true' : 'false',
                'choices'  => [
                    ['ch_cookie_consent.no' => 'false'],
                    ['ch_cookie_consent.yes' => 'true'],
                ],
            ]);
        }
    }

    /**
     * Default options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'CHCookieConsentBundle',
        ]);
    }
}
