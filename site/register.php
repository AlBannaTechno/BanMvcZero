<?php

// TODO may implement auto directory type resolver [eg, Asp.net core]
function register_domain_types(Container $container) {
    $container->provide(Home::class);
    $container->provide(Payment::class);
    $container->provide(PaymentModel::class);
    $container->provide(AboutModel::class);
}
