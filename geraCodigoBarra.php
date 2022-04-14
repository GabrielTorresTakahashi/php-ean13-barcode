<?php
function IncluiDigito(string $ean):string
{
    while(strlen($ean)<12){
        $ean .= '0';
    }
    $digitos = str_split($ean);
    $soma = 0;
    foreach ($digitos as $i => $digito) {
        if (($i % 2) === 0) {
            $soma += $digito * 1;
        } else {
            $soma += $digito * 3;
        }
    }
    $resultado = floor($soma / 10) + 1;
    $resultado *= 10;
    $resultado -= $soma;
    if (($resultado % 10) === 0) {
        $ean = $ean . '0';
    } else {
        $ean = $ean . $resultado;
    }
    return $ean;
}

function codificacao():array
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

    $codificacao=[
        'L'=> $L, 
        'G'=> $G, 
        'R'=> $R];

    return $codificacao;
}

function defineEstrutura(string $numero):string{
    $estrutura = [
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
        //adiciona RRRRRR ao final
        for ($i=0; $i<10; $i++){
            $estrutura[$i] = $estrutura[$i] . 'RRRRRR';
        }

        //define com base no primeiro digito
        $estruturaDefinida = "";
        for ($i=0; $i<10; $i++){
            if ($i == $numero[0]){
                $estruturaDefinida = $estrutura[$i];
                break;
            }
        }

        $estruturaDefinida = $numero[0] . $estruturaDefinida; 
        return $estruturaDefinida;
}


function geraChave(array $codificacao,string $estruturaDefinida,string $numero):string{
    //primeiros 6 digitos - estrutura variavel
    //O indice armazena o número específico de $numero
    $chave = '';
    for ($i=1; $i<7; $i++) {
        if ($estruturaDefinida[$i]=='L'){
            //$indice = $numero[$i];
            $chave .= $codificacao['L'][$numero[$i]];
        }
        else {
            $chave .= $codificacao['G'][$numero[$i]];
        }
    }

    //ultimos 6 digitos - estrutura RRRRRR
    for ($i=7; $i<13; $i++) {
        $chave .= $codificacao['R'][$numero[$i]];
    }

    return $chave;
}

function barrasLongas(int $x, float $largura, float $altura):int{
    $x+=$largura;
    echo '<rect x="'.$x.'" width="'.$largura.'" height="'.(1.2*$altura).'"/>';
    $x+=2*$largura;

    echo '<rect x="'.$x.'" width="'.$largura.'" height="'.(1.2*$altura).'"/>';
    $x+=$largura;

    return $x;
}

function desenhaBarras(string $chave, float $largura=1, float $altura=100):void {
    echo '<svg width=100%>';
    $x = 0;

    //2 barras longas
    $x = barrasLongas($x,$largura,$altura);

    //primeiros 6 digitos
    for ($i=0; $i<42; $i++){
        if ($chave[$i]=='1'){
            echo '<rect fill = "black" x="'.$x.'" width="'.$largura.'" height="'.$altura.'"/>';
        }
        $x+=$largura;
    }

    //2 barras longas
    $x = barrasLongas($x,$largura,$altura) + $largura;
    
    //ultimos digitos
    for ($i=42; $i<84; $i++){
        if ($chave[$i]=='1'){
            echo '<rect fill = "black" x="'.$x.'" width="'.$largura.'" height="'.$altura.'"/>';
        }
        $x+=$largura;
    }
    $x-=$largura;

    //2 barras longas
    barrasLongas($x, $largura, $altura);
}

function geraCodigoBarra ($numero):void{
    
    $numero = IncluiDigito($numero);
    $estruturaDefinida = defineEstrutura($numero);
    $codificacao = codificacao();
    $chave = geraChave($codificacao, $estruturaDefinida, $numero);
    desenhaBarras($chave, 3);
}

