<?php

namespace App\Exceptions;

use Exception;
use Throwable; // Importe Throwable para a interface de exceção

class AppError extends Exception
{
    /**
     * O código HTTP da exceção.
     * Geralmente mapeado para o status HTTP da resposta.
     *
     * @var int
     */
    protected $code;

    /**
     * Construtor da exceção personalizada AppError.
     *
     * @param string $message A mensagem de erro.
     * @param int $code O código da exceção (geralmente um código HTTP como 400, 404, 500).
     * @param ?Throwable $previous A exceção anterior usada para o encadeamento de exceções.
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {

        parent::__construct($message, $code, $previous);
        $this->code = $code;
    }

    /**
     * Retorna o código HTTP associado a esta exceção.
     * Embora getCode() já exista na classe pai, pode ser útil ter um nome mais descritivo
     * se você estiver tratando-o especificamente como um código HTTP.
     *
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->code;
    }
}
