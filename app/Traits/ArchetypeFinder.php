<?php

namespace App\Traits;

trait ArchetypeFinder
{

    protected $archetypes = [
        "Advocate" => ["Social", "Investigative"],
        "Architect" => ["Investigative", "Realistic"],
        "Builder" => ["Realistic", "Conventional"],
        "Caregiver" => ["Social", "Artistic"],
        "Composer" => ["Artistic", "Conventional"],
        "Creator" => ["Realistic", "Artistic"],
        "Designer" => ["Artistic", "Realistic"],
        "Enthusiast" => ["Investigative", "Artistic"],
        "Explorer" => ["Investigative", "Social"],
        "Groundbreaker" => ["Investigative", "Enterprising"],
        "Guardian" => ["Social", "Conventional"],
        "Humanitarian" => ["Enterprising", "Social"],
        "Innovator" => ["Realistic", "Investigative"],
        "Inventor" => ["Realistic", "Enterprising"],
        "Kingpin" => ["Enterprising", "Conventional"],
        "Maverick" => ["Enterprising", "Artistic"],
        "Mentor" => ["Social", "Enterprising"],
        "Protector" => ["Social", "Realistic"],
        "Researcher" => ["Conventional", "Investigative"],
        "Scholar" => ["Investigative", "Conventional"],
        "Supporter" => ["Realistic", "Social"],
        "Visionary" => ["Artistic", "Enterprising"],
        "Luminary" => ["Artistic", "Social"],
        "Philosopher" => ["Artistic", "Investigative"],
        "Mastermind" => ["Enterprising", "Investigative"],
        "Captain" => ["Enterprising", "Realistic"],
        "Technician" => ["Conventional", "Realistic"],
        "Anchor" => ["Conventional", "Social"],
        "Producer" => ["Conventional", "Artistic"],
        "Strategist" => ["Conventional", "Enterprising"],
    ];

    public function getArchetypeAndTopScores($traits): array
    {
        // Sort the traits array by score in descending order
        arsort($traits);

        // Get the top two traits
        $topTraits = array_slice($traits, 0, 2, true);

        // Ensure we have at least two traits
        if (count($topTraits) < 2) {
            return [
                'archetypes' => [],
                'topTraits' => $topTraits
            ];
        }

        $topTraits = array_keys($topTraits);
        $firstTrait = $topTraits[0];
        $secondTrait = $topTraits[1];

        // Find the matching archetype(s)
        $matchingArchetypes = [];
        foreach ($this->archetypes as $archetype => $traitCombination) {
            // Check if the archetype's traits match the top two traits in order
            if ($traitCombination[0] === $firstTrait && $traitCombination[1] === $secondTrait) {
                $matchingArchetypes[] = $archetype;
            }
        }

        return [
            'archetypes' => $matchingArchetypes,
            'topTraits' => [
                'first_trait' => $firstTrait,
                'second_trait' => $secondTrait
            ]
        ];
    }
}
