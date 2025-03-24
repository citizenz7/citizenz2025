<?php
// src/Command/ImportCommentsCommand.php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Comment;
use App\Entity\Article;

#[AsCommand(name: 'app:import-comments')]
class ImportCommentsCommand extends Command
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

        // Récupérer les commentaires de l'ancienne base de données
        $stmt = $oldDb->query('SELECT * FROM comment');
        $comments = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Importer les commentaires dans la nouvelle base de données
        foreach ($comments as $comment) {
            // Récupérer l'article associé au commentaire
            $article = $this->entityManager->getRepository(Article::class)->find($comment['article_id']);

            // Vérifier si l'article existe
            if ($article !== null) {
                $newComment = new Comment($article);
                $newComment->setContent($comment['content']);
                $newComment->setEmail($comment['email']);
                $newComment->setNickname($comment['nickname']);
                $newComment->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $comment['created_at']));
                $newComment->setRgpd($comment['rgpd']);
                $newComment->setIsActive($comment['is_active']);

                $this->entityManager->persist($newComment);
            } else {
                // L'article n'existe pas, on peut soit ignorer le commentaire, soit créer un nouvel article
                // Pour l'exemple, on va ignorer le commentaire
                $output->writeln("Article non trouvé pour le commentaire avec l'id " . $comment['id']);
            }
        }

        $this->entityManager->flush();

        $output->writeln('Importation des commentaires effectuée avec succès !');

        return Command::SUCCESS;
    }
}
