<?php
function includeDigit(mixed $ean): string
{
    while (strlen($ean) < 12) {
        $ean .= '0';
    }
    $digits = str_split($ean);
    $sum = 0;
    foreach ($digits as $i => $digito) {
        if (($i % 2) === 0) {
            $sum += $digito * 1;
            continue;
        }
        $sum += $digito * 3;
    }
    $result = floor($sum / 10) + 1;
    $result *= 10;
    $result -= $sum;
    if (($result % 10) === 0) {
        return $ean = $ean . '0';
    }
    return $ean = $ean . $result;
}

function codification(): array
{
    //L code
    $L = [
        '0001101',
        '0011001',
        '0010011',
        '0111101',
        '0100011',
        '0110001',
        '0101111',
        '0111011',
        '0110111',
        '0001011'
    ];
    //G code
    $G = [
        '0100111',
        '0110011',
        '0011011',
        '0100001',
        '0011101',
        '0111001',
        '0000101',
        '0010001',
        '0001001',
        '0010111'
    ];
    //R code
    $R = [
        '1110010',
        '1100110',
        '1101100',
        '1000010',
        '1011100',
        '1001110',
        '1010000',
        '1000100',
        '1001000',
        '1110100'
    ];

    $codification = [
        'L' => $L,
        'G' => $G,
        'R' => $R
    ];

    return $codification;
}

function defineStructure(string $number): string
{
    $structure = [
        'LLLLLLRRRRRR',
        'LLGLGGRRRRRR',
        'LLGGLGRRRRRR',
        'LLGGGLRRRRRR',
        'LGLLGGRRRRRR',
        'LGGLLGRRRRRR',
        'LGGGLLRRRRRR',
        'LGLGLGRRRRRR',
        'LGLGGLRRRRRR',
        'LGGLGLRRRRRR'
    ];
    //based on first digit of the EAN code, defines structure
    $definedStructure = $structure[$number[0]];
    $definedStructure = $number[0] . $definedStructure;
    return $definedStructure;
}


function generateKey(array $codification, string $definedStructure, string $number): string
{
    //fist 6 digits - variable struct
    $key = '';
    for ($i = 1; $i < 7; $i++) {
        if ($definedStructure[$i] == 'L') {
            $key .= $codification['L'][$number[$i]];
            continue;
        }
        $key .= $codification['G'][$number[$i]];
    }
    //last 6 digits - structure RRRRRR
    for ($i = 7; $i < 13; $i++) {
        $key .= $codification['R'][$number[$i]];
    }
    return $key;
}

function longBars(int $x, float $width, float $height): int
{
    $x += $width;
    echo '<rect x="' . $x . '" width="' . $width . '" height="' . (1.2 * $height) . '"/>';
    $x += 2 * $width;

    echo '<rect x="' . $x . '" width="' . $width . '" height="' . (1.2 * $height) . '"/>';
    $x += $width;

    return $x; //Returns the X position, modified
}

function drawBars(string $key, float $width = 1, float $height = 100): void
{
    echo '<svg width=100%>';
    $x = 0;
    //2 long bars
    $x = longBars($x, $width, $height);

    //primeiros 6 digits
    for ($i = 0; $i < 42; $i++) {
        if ($key[$i]) {
            echo '<rect x="' . $x . '" width="' . $width . '" height="' . $height . '"/>';
        }
        $x += $width;
    }

    //2 long bars
    $x = longBars($x, $width, $height) + $width;

    //ultimos digits
    for ($i = 42; $i < 84; $i++) {
        if ($key[$i]) {
            echo '<rect x="' . $x . '" width="' . $width . '" height="' . $height . '"/>';
        }
        $x += $width;
    }
    $x -= $width;

    //2 long bars
    longBars($x, $width, $height);
    echo '</svg>';
}

function generateBarcode(mixed $number): void
{
    $number = includeDigit($number);
    $definedStructure = defineStructure($number);
    $codification = codification();
    $key = generateKey($codification, $definedStructure, $number);
    drawBars($key, 3);
}
