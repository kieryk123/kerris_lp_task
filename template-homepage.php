<?php
/**
 * Template Name: Homepage
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

Timber::render(['template-homepage.twig'], $context);
