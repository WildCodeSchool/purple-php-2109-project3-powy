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
        //fixtures for students
        for ($i = 0; $i < self::LISTSOFTOPICS; $i++) {
            $topics = new Topic();
            $topics->setTopic1(1);
            $topics->setTopic2(2);
            $topics->setTopic3(3);
            $this->addReference('student_topic_' . $i, $topics);
            $manager->persist($topics);
        }
        //fixtures for mentors
        for ($i = 0; $i < self::LISTSOFTOPICS; $i++) {
            $topics = new Topic();
            $topics->setTopic1(4);
            $topics->setTopic2(7);
            $topics->setTopic3(3);
            $this->addReference('mentor_topic_' . $i, $topics);
            $manager->persist($topics);
        }

        //flush all lists
        $manager->flush();
    }
}
