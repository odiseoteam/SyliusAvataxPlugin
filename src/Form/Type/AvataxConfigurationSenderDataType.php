<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Form\Type;

use Sylius\Bundle\AddressingBundle\Form\Type\CountryCodeChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class AvataxConfigurationSenderDataType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('provinceCode', TextType::class, [
                'label' => 'odiseo_sylius_avatax_plugin.form.avatax_configuration_sender_data.province_code',
                'required' => false,
            ])
            ->add('countryCode', CountryCodeChoiceType::class, [
                'label' => 'odiseo_sylius_avatax_plugin.form.avatax_configuration_sender_data.country',
                'enabled' => true,
                'required' => false,
            ])
            ->add('street', TextType::class, [
                'label' => 'odiseo_sylius_avatax_plugin.form.avatax_configuration_sender_data.street',
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => 'odiseo_sylius_avatax_plugin.form.avatax_configuration_sender_data.city',
                'required' => false,
            ])
            ->add('postcode', TextType::class, [
                'label' => 'odiseo_sylius_avatax_plugin.form.avatax_configuration_sender_data.postcode',
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_avatax_configuration_sender_data';
    }
}
