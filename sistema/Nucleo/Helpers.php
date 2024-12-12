<?php

namespace sistema\Nucleo;
use Exception;

class Helpers 
{

    public static function redirecionar(string $url = null): void
    {
        header('HTTP/1.1 302 Found');
        $local = ($url ? self::url($url) : self::url());
        header("Location: {$local} ");
        exit();
    }




public static function url(string $url = null): string
{
    $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');
    $ambiente = ($servidor == 'localhost' ? URL_DESENVOLVIMENTO : URL_PRODUCAO);

    if (str_starts_with($url, '/')) {
        return $ambiente . $url;
    }
    return $ambiente . '/' . $url;
}

/**
 * Checa se o servidor é localhost
 * @return bool
 */
public static function localhost(): bool
{
    $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');

    if ($servidor == 'localhost') {
        return true;
    }
    return false;
}


public static function validarUrl(string $url): bool
{
    if (mb_strlen($url) < 10) {
        return false;
    }
    if (!str_contains($url, '.')) {
        return false;
    }
    if (str_contains($url, 'http://') or str_contains($url, 'https://')) {
        return true;
    }
    return false;
}

public static function validarUrlComFiltro(string $url): bool
{
    return filter_var($url, FILTER_VALIDATE_URL);
}





}