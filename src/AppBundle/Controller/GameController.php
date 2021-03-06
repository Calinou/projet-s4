<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Parties as Partie;
use AppBundle\Entity\Users as User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    /**
     * Internal function to check whether Joueur2 is the opponent of the user or not.
     *
     * @param Partie $partie The game object where the check should be performed.
     * @param User $user The user object to check against.
     * @return bool
     */
    private function isJoueur2Opponent(Partie $partie, User $user)
    {
        if ($user->getId() === $partie->getJoueur1()->getId()) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Internal function to get the player's own hand.
     *
     * @param Partie $partie
     * @param User $user
     * @return array
     */
    private function getOwnHand(Partie $partie, User $user)
    {
        if ($this->isJoueur2Opponent($partie, $user)) {
            return $partie->getMainJoueur1();
        } else {
            return $partie->getMainJoueur2();
        }
    }


    /**
     * Displays game creation form (where one player chooses another player to play against).
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createGameFormAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Users');
        $users = $repository->findAll();
        return $this->render('create_game.html.twig', ['users' => $users]);
    }


    /**
     * Spawns a new game.
     * @param $opponent
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createGameAction($opponent)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Users');

        $user = $this->getUser();
        $opponent = $repository->find($opponent);
        $partie = new Partie();
        $partie->setJoueur1($user);
        $partie->setJoueur2($opponent);

        $repository = $this->getDoctrine()->getRepository('AppBundle:Cartes');
        $cards = $repository->findAll();

        // Shuffle cards, give hands to each player, the remaining is the "pioche"
        shuffle($cards);
        $mainJoueur1 = [];
        $mainJoueur2 = [];
        $pioche = [];

        // Distribute 8 cards to each player
        for ($i = 0; $i < 8; $i++) {
            $mainJoueur1[] = array_shift($cards)->getId();
            $mainJoueur2[] = array_shift($cards)->getId();
        }

        // Iterate over the remaining cards, fill $pioche with card IDs
        foreach ($cards as $card) {
            $pioche[] = $card->getId();
        }

        $partie->setMainJoueur1($mainJoueur1);
        $partie->setMainJoueur2($mainJoueur2);
        $partie->setPioche($pioche); // The rest of the cards (that haven't been put in players' hands)

        $manager->persist($partie);
        $manager->flush();

        $gameId = $partie->getId();

        return $this->redirectToRoute('show_game', ['gameId' => $gameId]);
    }


    /**
     * Displays a game.
     * @param $gameId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showGameAction($gameId)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Parties');
        $partie = $repository->find($gameId);
        $opponent = $partie->getJoueur2()->getUsername();
        $user = $this->getUser();

        // Display the hand of the current user only
        // A player can't see the hand of their opponent
        if ($this->isJoueur2Opponent($partie, $user)) {
            $hand = $partie->getMainJoueur1();
        } else {
            $hand = $partie->getMainJoueur2();
        }

        // Get card objects that match the player's hand, for display
        $repository = $this->getDoctrine()->getRepository('AppBundle:Cartes');
        $query = $repository->createQueryBuilder('card')
            ->where('card.id IN (:hand)')->setParameter('hand', $hand)
            ->getQuery();

        $cards = $query->getResult();

        return $this->render('show_game.html.twig', [
            'opponent' => $opponent,
            'cards'    => $cards,
            'gameId'   => $gameId,
        ]);
    }


    /**
     * Places a card (if possible and allowed).
     *
     * @param $gameId int The game ID to place a card on.
     * @param $cardId int The ID of the card which should be placed.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function placeCardAction($gameId, $cardId)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Parties');
        $game = $repository->find($gameId);
        $hand = $this->getOwnHand($game, $this->getUser());

        if (in_array($cardId, $hand)) {
            $index = array_search($cardId, $hand);
            unset($hand[$index]);
            array_values($hand);
        }

        return new Response('Placed card.');
    }
}
