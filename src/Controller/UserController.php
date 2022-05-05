<?php

namespace App\Controller;

use App\Model\AdminManager;
use App\Model\UserManager;
use App\Service\UtilityService;
use App\Model\ContactManager;

class UserController extends AbstractController
{
    /**
     * Show list of comics sorted by title, description or author
     */
    public function list(): string
    {
        $userManager = new UserManager();
        $utilityService = new UtilityService();
        $keywords = $userManager->keywordsList();
        $completionList = $userManager->selectTwentyLastCompletions();

        $comicByAuthor = $userManager->listByAuthor();
        $comicByCategory = $userManager->listByCategory();
        $comicByTitleAndPitch = $userManager->listByKeywords();

        // Use this function to merge an array in an other one.
        $comicBooks = array_merge($comicByAuthor, $comicByCategory, $comicByTitleAndPitch);
        // Use this new method to sort comics by an attribute.
        $splitAuthorFirstName = $utilityService->sortByWords($keywords, $comicBooks, 'first_name');
        $splitAuthorLastName = $utilityService->sortByWords($keywords, $comicBooks, 'last_name');
        $plitCategory = $utilityService->sortByWords($keywords, $comicBooks, 'category');
        $splitTitle = $utilityService->sortByWords($keywords, $comicBooks, 'title');
        $splitKeywords = $utilityService->sortByWords($keywords, $comicBooks, 'keywords');
        $finalList = array_merge(
            $splitAuthorLastName,
            $splitAuthorFirstName,
            $plitCategory,
            $splitTitle,
            $splitKeywords
        );
        // Use this method to delete duplicates (for ex: One comic may have 2 or 3 authors).
        $finalList = $utilityService->arrayUnique($finalList, 'title');
        return $this->twig->render('User/list.html.twig', array('comicBooks' => $finalList,
                                                                    'completionList' => $completionList));
    }

    /**
     * Add a new message
     */
    public function add(): ?string
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $userMessages = array_map('trim', $_POST);

            // TODO validations (length, format...)

            if (strlen($userMessages['firstname']) > 80) {
                $errors[] = 'Le prénom renseigné ne doit pas dépasser 80 cractères.';
            }

            if (strlen($userMessages['lastname']) > 100) {
                $errors[] = 'Le nom de famille renseigné ne doit pas dépasser 100 cractères.';
            }

            if (!filter_var($userMessages['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Veuillez renseigner une adresse mail valide.';
            }

            if (empty($errors)) {
                // if validation is ok, insert and redirection
                $contactManager = new ContactManager();
                $contactManager->insert($userMessages);
            }
        }
        return $this->twig->render('User/contact.html.twig', ['errors' => $errors]);
    }

    public function details($id): string
    {
        $adminManager = new AdminManager();
        $userManager = new UserManager();
        $comics = $adminManager->selectOneById($id);
        $completionList = $userManager->selectTwentyLastCompletions();

        $authors = $adminManager-> selectAuthorInJunctionById($id);
        return $this->twig->render('User/details.html.twig', array(
            'comics' => $comics,
            'authors' => $authors,
            'completionList' => $completionList

        ));
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $credentials = array_map('trim', $_POST);
            //      @todo make some controls on email and password fields and if errors, send them to the view
            $userManager = new UserManager();
            $user = $userManager->selectOneByUser($credentials['user_name']);
            if ($user && ($credentials['password'] === $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: admin/list');
            }
        }
        return $this->twig->render('Home/index.html.twig');
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /');
    }
}
