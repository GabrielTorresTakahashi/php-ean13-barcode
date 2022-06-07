<?php
function includeDigit(string $ean):string
{
    while(strlen($ean)<12){
        $ean .= '0';
    }
    $digits = str_split($ean);
    $sum = 0;
    foreach ($digits as $i => $digito) {
        if (($i % 2) === 0) {
            $sum += $digito * 1;
        } else {
            $sum += $digito * 3;
        }
    }
    $result = floor($sum / 10) + 1;
    $result *= 10;
    $result -= $sum;
    if (($result % 10) === 0) {
        $ean = $ean . '0';
    } else {
        $ean = $ean . $result;
    }
    return $ean;
}

function codification():array
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
    '0001011'];
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
    '0010111'];
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
    '1110100'];

    $codification=[
        'L'=> $L, 
        'G'=> $G, 
        'R'=> $R];

    return $codification;
}

function defineStructure(string $number):string{
    $structure = [
        'LLLLLL',
        'LLGLGG',
        'LLGGLG',
        'LLGGGL',
        'LGLLGG',
        'LGGLLG',
        'LGGGLL',
        'LGLGLG',
        'LGLGGL',
        'LGGLGL'];
        //add RRRRRR at the end of each strucute
        foreach ($structure as $index){
            $index = $index . 'RRRRRR';
        }
        

        //based on first digit, defines structure
        $definedStructure = "";
        for ($i=0; $i<10; $i++){
            if ($i == $number[0]){
                $definedStructure = $structure[$i];
                break;
            }
        }

        $definedStructure = $number[0] . $definedStructure; 
        return $definedStructure;
}


function generateKey(array $codification,string $definedStructure,string $number):string{
    //fist 6 digits - variable struct
    //The $i stores a specific number from $number
    $key = '';
    for ($i=1; $i<7; $i++) {
        if ($definedStructure[$i]=='L'){
            //$indice = $number[$i];
            $key .= $codification['L'][$number[$i]];
        }
        else {
            $key .= $codification['G'][$number[$i]];
        }
    }

    //last 6 digits - structure RRRRRR
    for ($i=7; $i<13; $i++) {
        $key .= $codification['R'][$number[$i]];
    }

    return $key;
}

function longBars(int $x, float $width, float $height):int{
    $x+=$width;
    echo '<rect x="'.$x.'" width="'.$width.'" height="'.(1.2*$height).'"/>';
    $x+=2*$width;

    echo '<rect x="'.$x.'" width="'.$width.'" height="'.(1.2*$height).'"/>';
    $x+=$width;

    return $x;
}

function drawBars(string $key, float $width=1, float $height=100):void {
    echo '<svg width=100%>';
    $x = 0;

    //2 long bars
    $x = longBars($x,$width,$height);

    //primeiros 6 digits
    for ($i=0; $i<42; $i++){
        if ($key[$i]=='1'){
            echo '<rect fill = "black" x="'.$x.'" width="'.$width.'" height="'.$height.'"/>';
        }
        $x+=$width;
    }

    //2 long bars
    $x = longBars($x,$width,$height) + $width;
    
    //ultimos digits
    for ($i=42; $i<84; $i++){
        if ($key[$i]=='1'){
            echo '<rect fill = "black" x="'.$x.'" width="'.$width.'" height="'.$height.'"/>';
        }
        $x+=$width;
    }
    $x-=$width;

    //2 long bars
    longBars($x, $width, $height);
}

function generateBarcode (mixed $number):void{
    
    $number = includeDigit($number);
    $definedStructure = defineStructure($number);
    $codification = codification();
    $key = generateKey($codification, $definedStructure, $number);
    drawBars($key, 3);
}

