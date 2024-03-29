<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\User\User;

class DeleteUserAction extends UserAction {

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        if (!isset($_SESSION)) session_start();
        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];
        
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            header("Location: http://$name$port$dest");
            exit();
        }

        $userId = (int) $this->resolveArg('id');

        // only logged user and admin can delete user from URL 
        if ($userId !== $_SESSION['id'] && $_SESSION['role'] !== "Admin")
        {
            $dest = "/unauthorized_access_error";
            header("Location: http://$name$port$dest");
            exit();
        }

        if ($_SESSION['role'] !== "Admin")
        {
            if (isset($_SESSION['email'])) unset($_SESSION['email']);
            if (isset($_SESSION['password'])) unset($_SESSION['password']);
            if (isset($_SESSION['role'])) unset($_SESSION['role']);
            if (isset($_SESSION['id']))   unset($_SESSION['id']);
        }

        $this->userRepository->delete($userId);
        $this->logger->info("User of id `${userId}` was deleted.");

        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];

        header("Location: http://$name$port");
        exit(); 

        return $this->response;
    }
}