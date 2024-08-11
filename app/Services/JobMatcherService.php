<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;

class JobMatcherService
{
    public function loadData($filePath)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            throw new \Exception('Error loading file: ' . $e->getMessage());
        }

        $headers = array_shift($sheetData);
        return [$headers, $sheetData];
    }

    public function findClosestJobs($data, $inputValues, $topN = 5)
    {
        list($headers, $data) = $data;

        $scaleNameIndex = array_search('Scale Name', $headers);
        $elementNameIndex = array_search('Element Name', $headers);
        $titleIndex = array_search('Title', $headers);
        $dataValueIndex = array_search('Data Value', $headers);

        $scales = [];
        foreach ($data as $row) {
            if (isset($row[$scaleNameIndex]) && $row[$scaleNameIndex] === 'Occupational Interests') {
                $scales[] = $row[$elementNameIndex];
            }
        }
        $scales = array_unique($scales);

        $filteredData = [];
        foreach ($data as $row) {
            if (in_array($row[$elementNameIndex], $scales)) {
                $filteredData[] = $row;
            }
        }

        $uniqueTitles = [];
        foreach ($filteredData as $row) {
            if (isset($row[$titleIndex])) {
                $uniqueTitles[] = $row[$titleIndex];
            }
        }
        $uniqueTitles = array_unique($uniqueTitles);

        $elements = [];
        foreach ($uniqueTitles as $title) {
            $element = [];
            foreach ($filteredData as $row) {
                if (isset($row[$titleIndex]) && $row[$titleIndex] === $title && $row[$scaleNameIndex] === 'Occupational Interests') {
                    $element[$row[$elementNameIndex]] = $row[$dataValueIndex];
                }
            }
            $elementValues = [];
            foreach ($scales as $scale) {
                $elementValues[] = $element[$scale] ?? 0; // Use 0 if value is missing
            }
            $elements[$title] = $elementValues;
        }

        $distances = [];
        foreach ($elements as $title => $elementValues) {
            if (count($elementValues) == count($inputValues)) {
                $distance = $this->euclideanDistance($inputValues, $elementValues);
                $distances[$title] = $distance;
            } else {
                $distances[$title] = PHP_INT_MAX;
            }
        }

        asort($distances);
        $closestJobs = array_keys(array_slice($distances, 0, $topN, true));

        return $closestJobs;
    }

    public function filterJobsByZone($data, $closestJobs, $jobZone)
    {
        list($headers, $data) = $data;

        $titleIndex = array_search('Title', $headers);
        $jobZoneIndex = array_search('Job Zone', $headers);

        $filteredJobs = [];
        foreach ($closestJobs as $jobTitle) {
            foreach ($data as $row) {
                if (
                    isset($row[$titleIndex]) &&
                    isset($row[$jobZoneIndex]) &&
                    $row[$titleIndex] == $jobTitle &&
                    $row[$jobZoneIndex] == $jobZone
                ) {
                    $filteredJobs[] = $jobTitle;
                    break;
                }
            }
            if (count($filteredJobs) >= 3) {
                break;
            }
        }
        return $filteredJobs;
    }

    private function euclideanDistance($inputValues, $elementValues)
    {
        $sum = 0;
        foreach ($inputValues as $key => $value) {
            $sum += ($value - $elementValues[$key]) ** 2;
        }
        return sqrt($sum);
    }
}
