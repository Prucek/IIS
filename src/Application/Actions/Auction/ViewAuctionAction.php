<?php


namespace App\Application\Actions\Auction;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ViewAuctionAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        session_start();
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            $script = $_SERVER["PHP_SELF"];
            if (strpos($dest, '/') === 0) { // absolute path
                $path = $dest;
            } else {
                $path = substr($script, 0,
                strrPos($script, "/"))."/$dest";
            }
            $name = $_SERVER["SERVER_NAME"];
            $port = ':'.$_SERVER["SERVER_PORT"];
            header("Location: http://$name$port$path");
            exit();
        }

        $auctionId = (int) $this->resolveArg('id');
        $auction = $this->auctionRepository->findAuctionOfId($auctionId);
        $bids = $this->bidRepository->findAllAuctionBids($auction->getId());

        $this->logger->info("Auction of id `${auctionId}` was viewed.");

        $this->auctionViewRenderer->setLayout("index.php");
        $is_registred = $this->bidRepository->isRegistred($auctionId, $_SESSION['id']);
        $this->auctionViewRenderer->render($this->response, "show.php", ["auction" => $auction, "bids" => $bids, "is_registred" => $is_registred]);
        
        return $this->response;
    }
}