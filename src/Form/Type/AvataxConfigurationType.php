<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class AvataxConfigurationType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('appName', TextType::class, [
                'label' => 'odiseo_sylius_avatax_plugin.form.avatax_configuration.app_name',
            ])
            ->add('appVersion', TextType::class, [
                'label' => 'odiseo_sylius_avatax_plugin.form.avatax_configuration.app_version',
            ])
            ->add('machineName', TextType::class, [
                'label' => 'odiseo_sylius_avatax_plugin.form.avatax_configuration.machine_name',
                'required' => false,
            ])
            ->add('sandbox', CheckboxType::class, [
                'label' => 'odiseo_sylius_avatax_plugin.form.avatax_configuration.sandbox',
            ])
            ->add('accountId', TextType::class, [
                'label' => 'odiseo_sylius_avatax_plugin.form.avatax_configuration.account_id',
            ])
            ->add('licenseKey', TextType::class, [
                'label' => 'odiseo_sylius_avatax_plugin.form.avatax_configuration.license_key',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'odiseo_avatax_configuration';
    }
}
