<?php

namespace App\DataFixtures;

use App\Entity\Topic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TopicFixtures extends Fixture
{
    public const LISTSOFTOPICS = 10;
    /**
     * 10 lists of topics with random choices of topic
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::LISTSOFTOPICS; $i++) {
            $topics = new Topic();
            $topics->setTopic1(rand(1, 9));
            $topics->setTopic2(rand(1, 9));
            $topics->setTopic3(rand(1, 9));
            $this->addReference('topics_' . $i, $topics);
            $manager->persist($topics);
        }
        //flush all lists
        $manager->flush();
    }
}
