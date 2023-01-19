<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    #[Route('/', name: 'SHOW_VALUE')]
    public function showValue(): Response
    {
        $value = $this->newStack();

        return $this->render('data.html.twig', [
            'value' => $value,
        ]);
    }

    public function getStack(): array
    {
        return [
            [1, '[D]', '[L]', '[J]', '[R]', '[V]', '[G]', '[F]'],
            [2, '[T]', '[P]', '[M]', '[B]', '[V]', '[H]', '[J]', '[S]'],
            [3, '[V]', '[H]', '[M]', '[F]', '[D]', '[G]', '[P]', '[C]'],
            [4, '[M]', '[D]', '[P]', '[N]', '[G]', '[Q]'],
            [5, '[J]', '[L]', '[H]', '[N]', '[F]'],
            [6, '[N]', '[F]', '[V]', '[Q]', '[D]', '[G]', '[T]', '[Z]'],
            [7, '[F]', '[D]', '[B]', '[L]'],
            [8, '[M]', '[J]', '[B]', '[S]', '[V]', '[D]', '[N]'],
            [9, '[G]', '[L]', '[D]']
        ];
    }

    public function getMovementFromFile(): array
    {
        $movement = file_get_contents('/home/noa/PhpstormProjects/AdventsOfCode/Day5/src/movement.txt');

        return explode("\n", $movement);
    }

    public function newStack()
    {
        $movement = $this->getMovementFromFile();

        $numberOfCrates = $this->numberOfCrates($movement);
        $firstStack = $this->firstStack($movement);
        $secondStack = $this->secondStack($movement);

        return $this->moveStack($numberOfCrates, $firstStack, $secondStack);
    }

    public function numberOfCrates(array $movement): array
    {
        $numberOfCrates = [];

        foreach ($movement as $key => $value) {
            preg_match_all('!\d+!', $value, $matches);
            $numberOfCrates[$key] = $matches[0][0];
        }

        return $numberOfCrates;
    }
    public function firstStack(array $movement): array
    {
        $firstCrate = [];

        foreach ($movement as $key => $value) {
            preg_match_all('!\d+!', $value, $matches);
            $firstCrate[$key] = $matches[0][1];
        }

        return $firstCrate;
    }

    public function secondStack(array $movement): array
    {
        $secondCrate = [];

        foreach ($movement as $key => $value) {
            preg_match_all('!\d+!', $value, $matches);
            $secondCrate[$key] = $matches[0][2];
        }

        return $secondCrate;
    }

    public function moveStack(array $number, array $firstStack, array $secondStack): array
    {
        $stack = $this->getStack();

        $sum = 0;

        foreach ($firstStack as $key => $value) {
            $firstPosition = $value - 1;
            $lastPosition = $secondStack[$key] - 1;

            for ($i = 1; $i <= $number[$key]; $i++) {
                $lastElement[$i] = end($stack[$firstPosition]);
                $newData = explode("[]", $lastElement[$i]);
                $stack[$lastPosition] = array_merge($stack[$lastPosition], $newData);
                array_pop($stack[$firstPosition]);
            }
        }

        return $stack;
    }
}