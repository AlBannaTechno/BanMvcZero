<?php

// TODO may implement auto directory type resolver [eg, Asp.net core]
function register_domain_types(Container $container) {
    // it's very important to register controllers here if you will use dynamic operation
    // eg, Controller::link(view) => to get link for specific view of controller
    $container->provide(Home::class);
    $container->provide(Payment::class);
    $container->provide(Trades::class);
    $container->provide(PaymentModel::class);
    $container->provide(AboutModel::class);
}
