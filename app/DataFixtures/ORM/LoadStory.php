<?php
// src/OC/UserBundle/DataFixtures/ORM/LoadUser.php

namespace OC\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PlatformBundle\Entity\Story;

class LoadStory implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNames = array('Sarah', 'Marine', 'John');

    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $story = new Story;

      // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
      $story->setAuthor($name);
      $story->setTitle('Article de ' . $name);

      // On ne se sert pas du sel pour l'instant
      $story->setContent("Contenu de l'article de " . $name);
      // On définit uniquement le role ROLE_USER qui est le role de base

      // On le persiste
      $manager->persist($story);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}