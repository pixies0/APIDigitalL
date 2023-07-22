<?php

use App\Models\Configuracoes;
use App\Models\ErroSistema;
use App\Models\Orgao;
use Illuminate\Support\Facades\Auth;

function Erro($mensagem = '', $erro = ''): int
{
    $salvar = ErroSistema::create([
        'mensagem' => $mensagem,
        'erro' => $erro,
        'pessoa_id' => Auth()->id(),
    ]);

    return $salvar->id;
}

function regexNumeros(string $value = null): string
{
    return preg_replace('/[^0-9]/', '', $value);
}

function regexCpfCnpj(string $cpf_cpnj = null): string
{
    $formatado = strlen($cpf_cpnj) === 11 ?
        preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf_cpnj) :
        preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cpf_cpnj);

    return $formatado;
}

function regexCep(string $cep = null): string
{
    return preg_replace("/^([\d]{2})([\d]{3})([\d]{3})/", "\$1\$2-\$3", $cep);
}

function regexTelefone(string $fone = null): string|null
{
    if (!$fone) return $fone;

    $tam = strlen(preg_replace("/[^0-9]/", "", $fone));
    if ($tam == 13) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS e 9 dígitos
        return "+" . substr($fone, 0, $tam - 11) . "(" . substr($fone, $tam - 11, 2) . ")" . substr($fone, $tam - 9, 5) . "-" . substr($fone, -4);
    }
    if ($tam == 12) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS
        return "+" . substr($fone, 0, $tam - 10) . "(" . substr($fone, $tam - 10, 2) . ")" . substr($fone, $tam - 8, 4) . "-" . substr($fone, -4);
    }
    if ($tam == 11) { // COM CÓDIGO DE ÁREA NACIONAL e 9 dígitos
        return "(" . substr($fone, 0, 2) . ")" . substr($fone, 2, 5) . "-" . substr($fone, 7, 11);
    }
    if ($tam == 10) { // COM CÓDIGO DE ÁREA NACIONAL
        return "(" . substr($fone, 0, 2) . ")" . substr($fone, 2, 4) . "-" . substr($fone, 6, 10);
    }
    if ($tam <= 9) { // SEM CÓDIGO DE ÁREA
        return substr($fone, 0, $fone - 4) . "-" . substr($fone, -4);
    }
}

function mask($val, $mask)
{
    $val = (string) $val;
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
        } else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

function gerarSigla(string $nome)
{
    $nome = str_replace(['...', '---'], '', $nome);
    $nome = str_replace(['Ã', 'Ç', ' E ', 'DA', 'DE', 'DO'], ['A', 'C', ' ', ''], $nome);
    $nome = explode('-', $nome);

    if (isset($nome[1]) && count($nome) === 2) {
        return trim($nome[1]);
    }

    $nome = $nome[0];
    $nome = trim($nome);
    if (count(explode(' ', $nome)) === 1) {
        return substr($nome, 0, 4);
    }
    $palavras = explode(' ', $nome);
    if (count($palavras) > 2) {
        return preg_replace('/\b(\w)|./', '$1', $nome);
    }

    $sigla = substr($palavras[0], 0, 3) . '. ';
    $sigla .= substr($palavras[1], 0, 3);

    return $sigla;
}

function floatNumber($value): float
{
    return floatval(sprintf("%.2f", $value));
}

function valorPorExtenso($valor = 0, $maiusculas = true, $moeda = true, $np = false)
//$maiusculas true para definir o primeiro caracter
//$moeda true para definir se escreve Reais / Centavos para usar com numerais simples ou monetarios
{
    $rt = '';
    // verifica se tem virgula decimal
    if (strpos($valor, ",") > 0) {
        // retira o ponto de milhar, se tiver
        $valor = str_replace(".", "", $valor);

        // troca a virgula decimal por ponto decimal
        $valor = str_replace(",", ".", $valor);
    }

    if (!$moeda) {
        $singular  = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
    } else {
        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
    }

    $c = array(
        "", "cem", "duzentos", "trezentos", "quatrocentos",
        "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"
    );
    $d = array(
        "", "dez", "vinte", "trinta", "quarenta", "cinquenta",
        "sessenta", "setenta", "oitenta", "noventa"
    );
    $d10 = array(
        "dez", "onze", "doze", "treze", "quatorze", "quinze",
        "dezesseis", "dezesete", "dezoito", "dezenove"
    );

    if (!$moeda) // se for usado apenas para numerais
    {
        if ($np)
            $u = array("", "uma", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
        else
            $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
    } else {
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
    }
    $z = 0;

    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);
    for ($i = 0; $i < count($inteiro); $i++)
        for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
            $inteiro[$i] = "0" . $inteiro[$i];

    $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
    for ($i = 0; $i < count($inteiro); $i++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd &&
            $ru) ? " e " : "") . $ru;
        $t = count($inteiro) - 1 - $i;
        $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($valor == "000") $z++;
        elseif ($z > 0) $z--;
        if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) $r .= (($z > 1) ? " de " : "") . $plural[$t];
        if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
            ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    if (!$maiusculas) {
        return ($rt ? $rt : "zero");
    } else {
        return (ucwords($rt) ? ucwords($rt) : "Zero");
    }
}

function getCliente()
{
    $cliente = Configuracoes::select('chave', 'valor')->where('chave', 'cliente_id')->firstOrFail();
    $cliente = Orgao::where('id', $cliente->valor)->with('pessoa:id,nome,cpf_cnpj,foto,cidade,fone')->firstOrFail();

    return $cliente;
}

function dinheiro($value)
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}
