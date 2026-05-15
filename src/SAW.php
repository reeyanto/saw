<?php

namespace Reeyanto\SAW;

class SAW
{
    private $criteria = [];
    private $alternatives = [];
    private $normalizedMatrix = [];

    /**
     * Menambahkan kriteria
     * @param string $name Nama kriteria
     * @param float $weight Bobot kriteria (0-1)
     * @param string $type Tipe kriteria ('benefit' atau 'cost')
     */
    public function addCriteria($name, $weight, $type = 'benefit')
    {
        if (!in_array($type, ['benefit', 'cost'])) {
            throw new \Exception("Tipe kriteria harus 'benefit' atau 'cost'");
        }

        $this->criteria[$name] = [
            'weight' => $weight,
            'type' => $type
        ];
    }

    /**
     * Menambahkan alternatif
     * @param string $name Nama alternatif
     * @param array $values Array dengan key berupa nama kriteria dan value berupa nilainya
     */
    public function addAlternative($name, $values)
    {
        $this->alternatives[$name] = $values;
    }

    /**
     * Menormalisasi matriks keputusan
     */
    private function normalizeMatrix()
    {
        $this->normalizedMatrix = [];

        foreach ($this->criteria as $criteriaName => $criteriaData) {
            $maxValue = 0;
            $minValue = PHP_INT_MAX;

            // Cari nilai max dan min untuk setiap kriteria
            foreach ($this->alternatives as $alternative) {
                $value = $alternative[$criteriaName] ?? 0;
                $maxValue = max($maxValue, $value);
                $minValue = min($minValue, $value);
            }

            // Normalisasi berdasarkan tipe kriteria
            foreach ($this->alternatives as $altName => $alternative) {
                $value = $alternative[$criteriaName] ?? 0;

                if ($criteriaData['type'] === 'benefit') {
                    // Untuk benefit: semakin besar semakin baik
                    $normalized = $maxValue > 0 ? $value / $maxValue : 0;
                } else {
                    // Untuk cost: semakin kecil semakin baik
                    $normalized = $minValue > 0 ? $minValue / $value : 0;
                }

                if (!isset($this->normalizedMatrix[$altName])) {
                    $this->normalizedMatrix[$altName] = [];
                }

                $this->normalizedMatrix[$altName][$criteriaName] = $normalized;
            }
        }
    }

    /**
     * Menghitung score preferensi dan mengembalikan ranking
     * @return array Array berisi ranking alternatif
     */
    public function calculate()
    {
        if (empty($this->criteria) || empty($this->alternatives)) {
            throw new \Exception("Kriteria dan alternatif harus ditambahkan terlebih dahulu");
        }

        $this->normalizeMatrix();

        $scores = [];

        // Hitung score untuk setiap alternatif
        foreach ($this->normalizedMatrix as $altName => $normalizedValues) {
            $score = 0;

            foreach ($this->criteria as $criteriaName => $criteriaData) {
                $normalized = $normalizedValues[$criteriaName] ?? 0;
                $score += $criteriaData['weight'] * $normalized;
            }

            $scores[$altName] = $score;
        }

        // Urutkan dari score tertinggi ke terendah
        arsort($scores);

        // Buat ranking dengan nomor urut
        $ranking = [];
        $rank = 1;

        foreach ($scores as $altName => $score) {
            $ranking[$rank] = [
                'alternative' => $altName,
                'score' => round($score, 4),
                'rank' => $rank
            ];
            $rank++;
        }

        return $ranking;
    }

    /**
     * Mendapatkan semua kriteria
     * @return array Daftar kriteria
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Mendapatkan semua alternatif
     * @return array Daftar alternatif
     */
    public function getAlternatives()
    {
        return $this->alternatives;
    }

    /**
     * Reset semua data
     */
    public function reset()
    {
        $this->criteria = [];
        $this->alternatives = [];
        $this->normalizedMatrix = [];
    }
}