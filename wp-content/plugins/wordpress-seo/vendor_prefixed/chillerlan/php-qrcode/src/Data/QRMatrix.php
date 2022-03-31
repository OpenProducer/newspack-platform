<?php

/**
 * Class QRMatrix
 *
 * @filesource   QRMatrix.php
 * @created      15.11.2017
 * @package      chillerlan\QRCode\Data
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */
namespace YoastSEO_Vendor\chillerlan\QRCode\Data;

use YoastSEO_Vendor\chillerlan\QRCode\QRCode;
/**
 * @link http://www.thonky.com/qr-code-tutorial/format-version-information
 */
class QRMatrix
{
    const M_NULL = 0x0;
    const M_DARKMODULE = 0x2;
    const M_DATA = 0x4;
    const M_FINDER = 0x6;
    const M_SEPARATOR = 0x8;
    const M_ALIGNMENT = 0xa;
    const M_TIMING = 0xc;
    const M_FORMAT = 0xe;
    const M_VERSION = 0x10;
    const M_QUIETZONE = 0x12;
    const M_LOGO = 0x14;
    // @todo
    const M_TEST = 0xff;
    /**
     * @link http://www.thonky.com/qr-code-tutorial/alignment-pattern-locations
     */
    const alignmentPattern = [1 => [], [6, 18], [6, 22], [6, 26], [6, 30], [6, 34], [6, 22, 38], [6, 24, 42], [6, 26, 46], [6, 28, 50], [6, 30, 54], [6, 32, 58], [6, 34, 62], [6, 26, 46, 66], [6, 26, 48, 70], [6, 26, 50, 74], [6, 30, 54, 78], [6, 30, 56, 82], [6, 30, 58, 86], [6, 34, 62, 90], [6, 28, 50, 72, 94], [6, 26, 50, 74, 98], [6, 30, 54, 78, 102], [6, 28, 54, 80, 106], [6, 32, 58, 84, 110], [6, 30, 58, 86, 114], [6, 34, 62, 90, 118], [6, 26, 50, 74, 98, 122], [6, 30, 54, 78, 102, 126], [6, 26, 52, 78, 104, 130], [6, 30, 56, 82, 108, 134], [6, 34, 60, 86, 112, 138], [6, 30, 58, 86, 114, 142], [6, 34, 62, 90, 118, 146], [6, 30, 54, 78, 102, 126, 150], [6, 24, 50, 76, 102, 128, 154], [6, 28, 54, 80, 106, 132, 158], [6, 32, 58, 84, 110, 136, 162], [6, 26, 54, 82, 110, 138, 166], [6, 30, 58, 86, 114, 142, 170]];
    /**
     * @link http://www.thonky.com/qr-code-tutorial/format-version-tables
     */
    const versionPattern = [
        7 => 0x7c94,
        0x85bc,
        0x9a99,
        0xa4d3,
        // 7-10
        0xbbf6,
        0xc762,
        0xd847,
        0xe60d,
        0xf928,
        0x10b78,
        0x1145d,
        0x12a17,
        0x13532,
        0x149a6,
        0x15683,
        0x168c9,
        0x177ec,
        0x18ec4,
        0x191e1,
        0x1afab,
        0x1b08e,
        0x1cc1a,
        0x1d33f,
        0x1ed75,
        0x1f250,
        0x209d5,
        0x216f0,
        0x228ba,
        0x2379f,
        0x24b0b,
        0x2542e,
        0x26a64,
        0x27541,
        0x28c69,
    ];
    const formatPattern = [
        [0x77c4, 0x72f3, 0x7daa, 0x789d, 0x662f, 0x6318, 0x6c41, 0x6976],
        // L
        [0x5412, 0x5125, 0x5e7c, 0x5b4b, 0x45f9, 0x40ce, 0x4f97, 0x4aa0],
        // M
        [0x355f, 0x3068, 0x3f31, 0x3a06, 0x24b4, 0x2183, 0x2eda, 0x2bed],
        // Q
        [0x1689, 0x13be, 0x1ce7, 0x19d0, 0x762, 0x255, 0xd0c, 0x83b],
    ];
    /**
     * @var int
     */
    protected $version;
    /**
     * @var int
     */
    protected $eclevel;
    /**
     * @var int
     */
    protected $maskPattern = -1;
    /**
     * @var int
     */
    protected $moduleCount;
    /**
     * @var mixed[]
     */
    protected $matrix;
    /**
     * QRMatrix constructor.
     *
     * @param int $version
     * @param int $eclevel
     *
     * @throws \chillerlan\QRCode\Data\QRCodeDataException
     */
    public function __construct($version, $eclevel)
    {
        if (!\in_array($version, \range(1, 40), \true)) {
            throw new \YoastSEO_Vendor\chillerlan\QRCode\Data\QRCodeDataException('invalid QR Code version');
        }
        if (!\array_key_exists($eclevel, \YoastSEO_Vendor\chillerlan\QRCode\QRCode::ECC_MODES)) {
            throw new \YoastSEO_Vendor\chillerlan\QRCode\Data\QRCodeDataException('invalid ecc level');
        }
        $this->version = $version;
        $this->eclevel = $eclevel;
        $this->moduleCount = $this->version * 4 + 17;
        $this->matrix = \array_fill(0, $this->moduleCount, \array_fill(0, $this->moduleCount, $this::M_NULL));
    }
    /**
     * Returns the data matrix, returns a pure boolean representation if $boolean is set to true
     *
     * @param bool $boolean
     *
     * @return int[][]|bool[][]
     */
    public function matrix($boolean = \false)
    {
        if (!$boolean) {
            return $this->matrix;
        }
        $matrix = [];
        foreach ($this->matrix as $y => $row) {
            $matrix[$y] = [];
            foreach ($row as $x => $val) {
                $matrix[$y][$x] = $val >> 8 > 0;
            }
        }
        return $matrix;
    }
    /**
     * @return int
     */
    public function version()
    {
        return $this->version;
    }
    /**
     * @return int
     */
    public function eccLevel()
    {
        return $this->eclevel;
    }
    /**
     * @return int
     */
    public function maskPattern()
    {
        return $this->maskPattern;
    }
    /**
     * Returns the absoulute size of the matrix, including quiet zone (after setting it).
     *
     * size = version * 4 + 17 [ + 2 * quietzone size]
     *
     * @return int
     */
    public function size()
    {
        return $this->moduleCount;
    }
    /**
     * Returns the value of the module at position [$x, $y]
     *
     * @param int $x
     * @param int $y
     *
     * @return int
     */
    public function get($x, $y)
    {
        return $this->matrix[$y][$x];
    }
    /**
     * Sets the $M_TYPE value for the module at position [$x, $y]
     *
     *   true  => $M_TYPE << 8
     *   false => $M_TYPE
     *
     * @param int  $x
     * @param int  $y
     * @param int  $M_TYPE
     * @param bool $value
     *
     * @return \chillerlan\QRCode\Data\QRMatrix
     */
    public function set($x, $y, $value, $M_TYPE)
    {
        $this->matrix[$y][$x] = $M_TYPE << ($value ? 8 : 0);
        return $this;
    }
    /**
     * Checks whether a module is true (dark) or false (light)
     *
     *   true  => $value >> 8 === $M_TYPE
     *            $value >> 8 > 0
     *
     *   false => $value === $M_TYPE
     *            $value >> 8 === 0
     *
     * @param int $x
     * @param int $y
     *
     * @return bool
     */
    public function check($x, $y)
    {
        return $this->matrix[$y][$x] >> 8 > 0;
    }
    /**
     * Sets the "dark module", that is always on the same position 1x1px away from the bottom left finder
     *
     * @return \chillerlan\QRCode\Data\QRMatrix
     */
    public function setDarkModule()
    {
        $this->set(8, 4 * $this->version + 9, \true, $this::M_DARKMODULE);
        return $this;
    }
    /**
     * Draws the 7x7 finder patterns in the corners top left/right and bottom left
     *
     * @return \chillerlan\QRCode\Data\QRMatrix
     */
    public function setFinderPattern()
    {
        $pos = [
            [0, 0],
            // top left
            [$this->moduleCount - 7, 0],
            // bottom left
            [0, $this->moduleCount - 7],
        ];
        foreach ($pos as $c) {
            for ($y = 0; $y < 7; $y++) {
                for ($x = 0; $x < 7; $x++) {
                    $this->set($c[0] + $y, $c[1] + $x, !($x > 0 && $x < 6 && ($y === 1 || $y === 5) || $y > 0 && $y < 6 && ($x === 1 || $x === 5)), $this::M_FINDER);
                }
            }
        }
        return $this;
    }
    /**
     * Draws the separator lines around the finder patterns
     *
     * @return \chillerlan\QRCode\Data\QRMatrix
     */
    public function setSeparators()
    {
        $h = [[7, 0], [$this->moduleCount - 8, 0], [7, $this->moduleCount - 8]];
        $v = [[7, 7], [$this->moduleCount - 1, 7], [7, $this->moduleCount - 8]];
        $t = $this::M_SEPARATOR;
        for ($c = 0; $c < 3; $c++) {
            for ($i = 0; $i < 8; $i++) {
                $this->set($h[$c][0], $h[$c][1] + $i, \false, $t);
                $this->set($v[$c][0] - $i, $v[$c][1], \false, $t);
            }
        }
        return $this;
    }
    /**
     * Draws the 5x5 alignment patterns
     *
     * @return \chillerlan\QRCode\Data\QRMatrix
     */
    public function setAlignmentPattern()
    {
        $pattern = $this::alignmentPattern[$this->version];
        foreach ($pattern as $y) {
            foreach ($pattern as $x) {
                // skip existing patterns
                if ($this->matrix[$y][$x] !== $this::M_NULL) {
                    continue;
                }
                for ($ry = -2; $ry <= 2; $ry++) {
                    for ($rx = -2; $rx <= 2; $rx++) {
                        $v = $ry === 0 && $rx === 0 || $ry === 2 || $ry === -2 || $rx === 2 || $rx === -2;
                        $this->set($x + $rx, $y + $ry, $v, $this::M_ALIGNMENT);
                    }
                }
            }
        }
        return $this;
    }
    /**
     * Draws the timing pattern (h/v checkered line between the finder patterns)
     *
     * @return \chillerlan\QRCode\Data\QRMatrix
     */
    public function setTimingPattern()
    {
        foreach (\range(8, $this->moduleCount - 8 - 1) as $i) {
            if ($this->matrix[6][$i] !== $this::M_NULL || $this->matrix[$i][6] !== $this::M_NULL) {
                continue;
            }
            $v = $i % 2 === 0;
            $t = $this::M_TIMING;
            $this->set($i, 6, $v, $t);
            // h
            $this->set(6, $i, $v, $t);
            // v
        }
        return $this;
    }
    /**
     * Draws the version information, 2x 3x6 pixel
     *
     * @param bool|null  $test
     *
     * @return \chillerlan\QRCode\Data\QRMatrix
     */
    public function setVersionNumber($test = null)
    {
        $test = $test !== null ? $test : \false;
        $bits = \array_key_exists($this->version, self::versionPattern) ? self::versionPattern[$this->version] : \false;
        if ($bits !== \false) {
            for ($i = 0; $i < 18; $i++) {
                $a = (int) \floor($i / 3);
                $b = $i % 3 + $this->moduleCount - 8 - 3;
                $v = !$test && ($bits >> $i & 1) === 1;
                $t = $this::M_VERSION;
                $this->set($b, $a, $v, $t);
                // ne
                $this->set($a, $b, $v, $t);
                // sw
            }
        }
        return $this;
    }
    /**
     * Draws the format info along the finder patterns
     *
     * @param int        $maskPattern
     * @param bool|null  $test
     *
     * @return \chillerlan\QRCode\Data\QRMatrix
     */
    public function setFormatInfo($maskPattern, $test = null)
    {
        $test = $test !== null ? $test : \false;
        $bits = \array_key_exists($this->eclevel, \YoastSEO_Vendor\chillerlan\QRCode\QRCode::ECC_MODES) && \array_key_exists($maskPattern, self::formatPattern[\YoastSEO_Vendor\chillerlan\QRCode\QRCode::ECC_MODES[$this->eclevel]]) ? self::formatPattern[\YoastSEO_Vendor\chillerlan\QRCode\QRCode::ECC_MODES[$this->eclevel]][$maskPattern] : 0;
        $t = $this::M_FORMAT;
        for ($i = 0; $i < 15; $i++) {
            $v = !$test && ($bits >> $i & 1) === 1;
            if ($i < 6) {
                $this->set(8, $i, $v, $t);
            } elseif ($i < 8) {
                $this->set(8, $i + 1, $v, $t);
            } else {
                $this->set(8, $this->moduleCount - 15 + $i, $v, $t);
            }
            if ($i < 8) {
                $this->set($this->moduleCount - $i - 1, 8, $v, $t);
            } elseif ($i < 9) {
                $this->set(15 - $i, 8, $v, $t);
            } else {
                $this->set(15 - $i - 1, 8, $v, $t);
            }
        }
        $this->set(8, $this->moduleCount - 8, !$test, $t);
        return $this;
    }
    /**
     * Draws the "quiet zone" of $size around the matrix
     *
     * @param int|null $size
     *
     * @return \chillerlan\QRCode\Data\QRMatrix
     * @throws \chillerlan\QRCode\Data\QRCodeDataException
     */
    public function setQuietZone($size = null)
    {
        if ($this->matrix[$this->moduleCount - 1][$this->moduleCount - 1] === $this::M_NULL) {
            throw new \YoastSEO_Vendor\chillerlan\QRCode\Data\QRCodeDataException('use only after writing data');
        }
        $size = $size !== null ? \max(0, \min($size, \floor($this->moduleCount / 2))) : 4;
        $t = $this::M_QUIETZONE;
        for ($y = 0; $y < $this->moduleCount; $y++) {
            for ($i = 0; $i < $size; $i++) {
                \array_unshift($this->matrix[$y], $t);
                \array_push($this->matrix[$y], $t);
            }
        }
        $this->moduleCount += $size * 2;
        $r = \array_fill(0, $this->moduleCount, $t);
        for ($i = 0; $i < $size; $i++) {
            \array_unshift($this->matrix, $r);
            \array_push($this->matrix, $r);
        }
        return $this;
    }
    /**
     * Maps the binary $data array from QRDataInterface::maskECC() on the matrix, using $maskPattern
     *
     * @see \chillerlan\QRCode\Data\QRDataAbstract::maskECC()
     *
     * @param int[] $data
     * @param int   $maskPattern
     *
     * @return \chillerlan\QRCode\Data\QRMatrix
     */
    public function mapData(array $data, $maskPattern)
    {
        $this->maskPattern = $maskPattern;
        $byteCount = \count($data);
        $size = $this->moduleCount - 1;
        $mask = $this->getMask($this->maskPattern);
        for ($i = $size, $y = $size, $inc = -1, $byteIndex = 0, $bitIndex = 7; $i > 0; $i -= 2) {
            if ($i === 6) {
                $i--;
            }
            while (\true) {
                for ($c = 0; $c < 2; $c++) {
                    $x = $i - $c;
                    if ($this->matrix[$y][$x] === $this::M_NULL) {
                        $v = \false;
                        if ($byteIndex < $byteCount) {
                            $v = ($data[$byteIndex] >> $bitIndex & 1) === 1;
                        }
                        if ($mask($x, $y) === 0) {
                            $v = !$v;
                        }
                        $this->matrix[$y][$x] = $this::M_DATA << ($v ? 8 : 0);
                        $bitIndex--;
                        if ($bitIndex === -1) {
                            $byteIndex++;
                            $bitIndex = 7;
                        }
                    }
                }
                $y += $inc;
                if ($y < 0 || $this->moduleCount <= $y) {
                    $y -= $inc;
                    $inc = -$inc;
                    break;
                }
            }
        }
        return $this;
    }
    /**
     * ISO/IEC 18004:2000 Section 8.8.1
     *
     * Note that some versions of the QR code standard have had errors in the section about mask patterns.
     * The information below has been corrected. (https://www.thonky.com/qr-code-tutorial/mask-patterns)
     *
     * @see \chillerlan\QRCode\QRMatrix::mapData()
     *
     * @internal
     *
     * @param int $maskPattern
     *
     * @return \Closure
     * @throws \chillerlan\QRCode\Data\QRCodeDataException
     */
    protected function getMask($maskPattern)
    {
        if ((0b111 & $maskPattern) !== $maskPattern) {
            throw new \YoastSEO_Vendor\chillerlan\QRCode\Data\QRCodeDataException('invalid mask pattern');
            // @codeCoverageIgnore
        }
        return [0b0 => function ($x, $y) {
            return ($x + $y) % 2;
        }, 0b1 => function ($x, $y) {
            return $y % 2;
        }, 0b10 => function ($x, $y) {
            return $x % 3;
        }, 0b11 => function ($x, $y) {
            return ($x + $y) % 3;
        }, 0b100 => function ($x, $y) {
            return ((int) ($y / 2) + (int) ($x / 3)) % 2;
        }, 0b101 => function ($x, $y) {
            return $x * $y % 2 + $x * $y % 3;
        }, 0b110 => function ($x, $y) {
            return ($x * $y % 2 + $x * $y % 3) % 2;
        }, 0b111 => function ($x, $y) {
            return ($x * $y % 3 + ($x + $y) % 2) % 2;
        }][$maskPattern];
    }
}
