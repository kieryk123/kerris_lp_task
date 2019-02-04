<?php

$context = Timber::get_context();
$context['posts'] = new Timber\PostQuery();
$templates = array( 'index.twig' );

if ( is_home() ) {
	array_unshift( $templates, 'front-page.twig', 'home.twig' );
}

Timber::render( $templates, $context );
