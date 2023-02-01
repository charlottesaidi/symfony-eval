<?php

namespace App\DataFixtures;

use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    private $fakerFactory;

    public function __construct() {
        $this->fakerFactory = \Faker\Factory::create('fr_FR');
    }

    public static function getVideoReference(string $key): string
    {
        return Video::class . '_' . $key;
    }

    public function load(ObjectManager $manager): void
    {
        // 200 random video(s)
        $i = 0;
        foreach ($this->getData() as $data) {
            $entity = $this->createVideo($data);
            $manager->persist($entity);
            $this->addReference(self::getVideoReference((string) $i), $entity);
            ++$i;
        }

        $manager->flush();
    }

    private function createVideo(array $data): Video
    {
        $entity = new Video();

        $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
            ->disableExceptionOnInvalidPropertyPath()
            ->getPropertyAccessor();

        foreach ($data as $key => $value) {
            if ($propertyAccessor->isWritable($entity, $key)) {
                $propertyAccessor->setValue($entity, $key, $value);
            }
        }

        return $entity;
    }

    private function getData(): iterable
    {
        $faker = $this->fakerFactory;

        for ($i = 0; $i < 100; ++$i) {
            $author = $faker->firstName() . ' ' . $faker->lastName();
            $name = $faker->words(4, true);
            $synopsis = $faker->paragraph(2);

            switch($i % 5) {
                case 0:
                    $type = 'série';
                    $genre = 'science-fiction';
                    break;
                case 3:
                    $type = 'série';
                    $genre = 'humour';
                default:
                    $type = 'film';
                    $genre = 'histoire';
            }
            $release = $faker->dateTimeBetween('-365');

            $data = [
                'author' => $author,
                'name' => $name,
                'synopsis' => $synopsis,
                'type' => $type,
                'genre' => $genre,
                'releaseDate' => $release
            ];
            yield $data;
        }
    }
}
