<?php

namespace App\DataFixtures;

use App\Entity\Tricks;
use App\Entity\TypesTricks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TricksFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Types Tricks Fixture
        $grabs = new TypesTricks();
        $grabs->setName('Grabs');

        $rotations = new TypesTricks();
        $rotations->setName('Rotations');

        $flips = new TypesTricks();
        $flips->setName('Flips');

        $rotationsDesaxees = new TypesTricks();
        $rotationsDesaxees->setName('Rotations désaxées');

        $slides = new TypesTricks();
        $slides->setName('Slides');

        $oneFootTricks = new TypesTricks();
        $oneFootTricks->setName('One foot tricks');

        $manager->persist($grabs);
        $manager->persist($rotations);
        $manager->persist($flips);
        $manager->persist($rotationsDesaxees);
        $manager->persist($slides);
        $manager->persist($oneFootTricks);
        $manager->flush();

        // Tricks Fixture
        $mute = new Tricks();
        $mute->setName('Mute');
        $mute->setType($grabs);
        $mute->setDescription("saisie de la carre frontside de la planche entre les deux pieds avec la main avant.");
        $manager->persist($mute);

        $indy = new Tricks();
        $indy->setName('Indy');
        $indy->setType($grabs);
        $indy->setDescription('Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.');
        $manager->persist($indy);

        $stalefish = new Tricks();
        $stalefish->setName('Stalefish');
        $stalefish->setType($grabs);
        $stalefish->setDescription('Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.');
        $manager->persist($stalefish);

        $tailGrab = new Tricks();
        $tailGrab->setName('Tail grab');
        $tailGrab->setType($grabs);
        $tailGrab->setDescription('Saisie de la partie arrière de la planche, avec la main arrière.');
        $manager->persist($tailGrab);

        $noseGrab = new Tricks();
        $noseGrab->setName('Tail grab');
        $noseGrab->setType($grabs);
        $noseGrab->setDescription('Saisie de la partie arrière de la planche, avec la main arrière.');
        $manager->persist($noseGrab);

        $japan = new Tricks();
        $japan->setName('Japan');
        $japan->setType($grabs);
        $japan->setDescription('Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.');
        $manager->persist($japan);

        $halfTurn = new Tricks();
        $halfTurn->setName('180');
        $halfTurn->setType($rotations);
        $halfTurn->setDescription('Désigne un demi-tour, soit 180 degrés d\'angle.');
        $manager->persist($halfTurn);

        $frontFlip = new Tricks();
        $frontFlip->setName('Front Flip');
        $frontFlip->setType($flips);
        $frontFlip->setDescription('Rotations en avant.');
        $manager->persist($frontFlip);

        $noseSlide = new Tricks();
        $noseSlide->setName('Nose Slide');
        $noseSlide->setType($slides);
        $noseSlide->setDescription('L\'avant de la planche sur la barre');
        $manager->persist($noseSlide);

        $tailSlide = new Tricks();
        $tailSlide->setName('Tail Slide');
        $tailSlide->setType($slides);
        $tailSlide->setDescription('L\'arriere de la planche sur la barre');
        $manager->persist($tailSlide);

        $manager->flush();
    }
}
