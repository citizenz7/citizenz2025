<?php

// src/Command/ImportArticleCommand.php

namespace App\Command;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:import-articles')]
class ImportArticleCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Connexion à l'ancienne base de données
        $oldDb = new \PDO('mysql:host=127.0.0.1:3306;dbname=citizenz2022', 'root', 'nnzb9x38');

        // Récupération des données de la table Article
        $stmt = $oldDb->query('SELECT * FROM article');
        $articles = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Définition des correspondances entre les champs
        $fieldMapping = [
            'author_id' => 'author',
            'title' => 'title',
            'slug' => 'slug',
            'resume' => 'intro',
            'content' => 'content',
            'postedAt' => 'createdAt',
            'updatedAt' => 'updatedAt',
            'image' => 'imageFeatured',
            'views' => 'views',
        ];

        // Importation des données dans la nouvelle base de données
        foreach ($articles as $article) {
            $newArticle = new Article();
            $newArticle->setTitle($article['title']);
            $newArticle->setSeoTitle($article['title']);
            $newArticle->setSeoDescription($article['title']);

            // Définir la catégorie 1 par défaut pour tous les articles
            $category = $this->entityManager->getRepository(Category::class)->find(1);
            $newArticle->setCategory($category);

            foreach ($fieldMapping as $oldField => $newField) {
                if ($newField === 'author') {
                    $author = $this->entityManager->getRepository(User::class)->find($article[$oldField]);
                    $newArticle->setAuthor($author);
                } elseif ($newField === 'createdAt' || $newField === 'updatedAt') {
                    $dateKey = $oldField === 'postedAt' ? 'posted_at' : ($oldField === 'updatedAt' ? 'updated_at' : $oldField);
                    $date = new \DateTimeImmutable($article[$dateKey]);
                    $setterMethod = 'set' . ucfirst($newField);
                    $newArticle->$setterMethod($date);
                } else {
                    if (isset($article[$oldField])) {
                        $setterMethod = 'set' . ucfirst($newField);
                        $newArticle->$setterMethod($article[$oldField]);
                    }
                }
            }
            $this->entityManager->persist($newArticle);
        }

        $this->entityManager->flush();

        $output->writeln('Importation des articles effectuée avec succès !');

        return Command::SUCCESS;
    }
}