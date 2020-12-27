<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Form\Extension;

use Sylius\Bundle\ProductBundle\Form\Type\ProductType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ProductTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('avataxCode', TextType::class, [
            'label' => 'odiseo_sylius_avatax_plugin.form.product.avatax_code',
            'required' => false,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function getExtendedTypes(): iterable
    {
        return [ProductType::class];
    }
}
