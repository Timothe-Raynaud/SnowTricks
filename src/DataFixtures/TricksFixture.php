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

        $indy = new Tricks();
        $indy->setName('Indy');
        $indy->setType($grabs);
        $indy->setDescription('Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.');

        $stalefish = new Tricks();
        $stalefish->setName('Stalefish');
        $stalefish->setType($grabs);
        $stalefish->setDescription('Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.');

        $tailGrab = new Tricks();
        $tailGrab->setName('Tail grab');
        $tailGrab->setType($grabs);
        $tailGrab->setDescription('Saisie de la partie arrière de la planche, avec la main arrière.');

        $noseGrab = new Tricks();
        $noseGrab->setName('Tail grab');
        $noseGrab->setType($grabs);
        $noseGrab->setDescription('Saisie de la partie arrière de la planche, avec la main arrière.');

        $manager->persist($mute);
        $manager->persist($indy);
        $manager->flush();
    }
}
