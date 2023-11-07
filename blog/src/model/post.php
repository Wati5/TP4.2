<?php

class Post
{
    public int $identifier;
    public string $title;
    public string $content;
    public string $frenchCreationDate;
}

class PostRepository
{
    public ?PDO $database = null;
}

function getPost(PostRepository $repository, int $postId)
{
    $database = $repository->database; // Accédez à la connexion à la base de données via l'objet $repository
    $statement = $database->prepare(
        "SELECT identifier, title, content, DATE_FORMAT(creation_date, '%d/%m/%Y à %Hh%imin%ss') AS french_creation_date FROM posts WHERE identifier = ?"
    );
    $statement->execute([$postId]);

    $post = $statement->fetch();

    if (!$post) {
        return null; // Le billet n'existe pas
    }

    $postObject = new Post();
    $postObject->identifier = $post['identifier'];
    $postObject->title = $post['title'];
    $postObject->content = $post['content'];
    $postObject->frenchCreationDate = $post['french_creation_date'];

    return $postObject;
}

function getPosts(PostRepository $repository)
{
    // Fonction getPosts
    // Ajoutez le paramètre $repository pour avoir la connexion à la base de données
    // ...
}

function dbConnect(PostRepository $repository)
{
    if ($repository->database === null) {
        $repository->database = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'blog', 'password');
    }
}
