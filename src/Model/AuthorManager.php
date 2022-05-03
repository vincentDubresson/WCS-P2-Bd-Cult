<?php

namespace App\Model;

class AuthorManager extends AbstractManager
{
    public const TABLE = 'author';

    public function insertAuthor(array $comicAuthor): void
    {
            $query = 'INSERT INTO author (`first_name`, `first_name_keyword`, `last_name`, `last_name_keyword`,
                        `birth_date`, `editor`, `biography`)
                        VALUES (:first_name, :first_name_keyword, :last_name, :last_name_keyword,
                        :birth_date, :editor, :biography)';
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':first_name', $comicAuthor['first_name'], \PDO::PARAM_STR);
            $statement->bindValue(':first_name_keyword', $comicAuthor['first_name_keyword'], \PDO::PARAM_STR);
            $statement->bindValue(':last_name', $comicAuthor['last_name'], \PDO::PARAM_STR);
            $statement->bindValue(':last_name_keyword', $comicAuthor['last_name_keyword'], \PDO::PARAM_STR);
            $statement->bindValue(':birth_date', $comicAuthor['birth_date'], \PDO::PARAM_STR);
            $statement->bindValue(':editor', $comicAuthor['editor'], \PDO::PARAM_STR);
            $statement->bindValue(':biography', $comicAuthor['biography'], \PDO::PARAM_STR);
            $statement->execute();
    }

    public function updateAuthor(array $comicAuthor, int $id): void
    {
            $query = 'UPDATE author SET `first_name`=:first_name, `first_name_keyword`=:first_name_keyword,
                        `last_name`=:last_name, `last_name_keyword`=:last_name_keyword, `birth_date`=:birth_date,
                        `editor`=:editor, `biography`=:biography 
                        WHERE `id`=:id;';
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':id', $id, \PDO::PARAM_INT);
            $statement->bindValue(':first_name', $comicAuthor['first_name'], \PDO::PARAM_STR);
            $statement->bindValue(':first_name_keyword', $comicAuthor['first_name_keyword'], \PDO::PARAM_STR);
            $statement->bindValue(':last_name', $comicAuthor['last_name'], \PDO::PARAM_STR);
            $statement->bindValue(':last_name_keyword', $comicAuthor['last_name_keyword'], \PDO::PARAM_STR);
            $statement->bindValue(':birth_date', $comicAuthor['birth_date'], \PDO::PARAM_STR);
            $statement->bindValue(':editor', $comicAuthor['editor'], \PDO::PARAM_STR);
            $statement->bindValue(':biography', $comicAuthor['biography'], \PDO::PARAM_STR);
            $statement->execute();
    }
}
