<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Parties as Partie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * Displays home page.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('index.html.twig');
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

        $game_id = $partie->getId();

        return $this->redirectToRoute('show_game', ['game_id' => $game_id]);
    }


    /**
     * Displays a game.
     * @param $game_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showGameAction($game_id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Parties');
        $partie = $repository->find($game_id);
        $opponent = $partie->getJoueur2()->getUsername();

        return $this->render('show_game.html.twig', [
            'opponent' => $opponent,
        ]);
    }
}
