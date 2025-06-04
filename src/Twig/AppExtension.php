<?php

namespace App\Twig;

use App\Entity\Forfait;
use DateTimeInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class  AppExtension extends AbstractExtension
{

    public function __construct(private Environment $twig)
    {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('truncate', [$this, 'truncateText']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('calculate_days', [$this, 'calculateDaysBetweenDates']),
            new TwigFunction('render_block', [$this, 'renderBlock'], ['is_safe' => ['html']]),
            new TwigFunction('calculer_prix_forfait', [$this, 'calculerPrixForfait']),
        ];
    }

    public function truncateText(string $text, int $length = 100): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length - 2) . '...';
    }

    /**
     * Calcule le nombre de jours entre deux dates (inclusif).
     */
    public function calculateDaysBetweenDates(DateTimeInterface $startDate, DateTimeInterface $endDate): int
    {
        return $startDate->diff($endDate)->days + 1; // +1 pour inclure le dernier jour
    }

    public function renderBlock(string $templateName, string $blockName, array $context = [])
    {
        $template = $this->twig->load($templateName);
        $content = $template->renderBlock($blockName, $context);

        // Retourne un objet DOMDocument si besoin
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        return $content; // Retourne le HTML brut par défaut
    }

    public function calculerPrixForfait($forfait): float
    {
        $prix = 0.0;

        foreach ($forfait['lots'] as $lot) {
            $prix += $lot['prix'];
        }

        return $prix;
    }

}
