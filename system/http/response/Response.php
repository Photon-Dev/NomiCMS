<?php declare(strict_types=1);
/**
 * NomiCMS - Content Management System
 *
 * @author Tosyk, Photon
 * @package nomicms/NomiCMS
 * @link   http://nomicms.ru
 */

namespace System\Http\Response;

// Использовать
use System\Http\Response\ResponseInterface;
use System\Http\Response\ResponseCodes;

use InvalidArgumentException;
use Exception;

/**
 * Класс Response
 */
class Response extends ResponseCodes implements ResponseInterface
{
    // Код ответа сервера
    protected $status;

    // Http заголовки
    public $headers = [];

    // Тип содержимого
    protected $contentType;

    // Кеширование
    protected $cache = false;

    // Тело
    protected $body = '';

    // Контент
    protected $content = '';

    // Ответ отправлен
    protected $sent = false;

    // Конструктор
    public function __construct(array $options = [])
    {
        $this->status = $this->invalidStatus(200);
    }

    // Установить тело
    public function body(string $body): void
    {
        $this->body = $body;
    }

    // Записать в содержимое
    public function write(string $str): void
    {
        $this->content .= $str;
    }

    // Отправить содержимое
    public function send()
    {
        if ($this->sent === false) {
            $this->sent = true;

            echo $this->body;
        }
    }

    public function sendHeaders()
    {
        if (! headers_sent()) {

            return true;
        }

        return false;
    }

    public function header(string $name, $value)
    {
        if ($this->sent === false) {

            foreach ($this->headers as $header) {
                header($header);
            }

        }
    }

    // Установить заголовок
    public function setHeader(string $name, string $value, $preend = true): self
    {
        if ($preend)
        {
            $this->headers[$name] = $value;

            return $this;
        }


        $this->headers[] = [$name, $value];
        return $this;
    }

    // Установить заголовоки
    public function setHeaders(array $headers): void
    {
        foreach ($headers as $header)
		{
			$this->setHeader($header['name'], $header['value'], $header['replace']);
		}
    }

    // Получить статус
    public function getStatus(): int
    {
        return $this->status;
    }

    // Получить тело
    public function getBody(): string
    {
        return $this->body;
    }

    // Получить содержимое
    public function getContent(): string
    {
        return $this->content;
    }

    // Очистить все
    public function clear(): self
    {
        $this->status = 200;
        $this->headers = [];
        $this->body = '';

        return $this;
    }


    public function invalidStatus($status)
    {
        if (!is_integer($status) || $status < 100 || $status > 599) {
            throw new InvalidArgumentException("Недопустимый код состояния HTTP: {$status}");
        }

        return $status;
    }

    // Получить код статуса
    public function getStatusCode($id = false)
    {
        if ($id === false) {
            //var_dump($id);
            return $this->status;
        }

        if (array_key_exists($id, self::$codes)) {
            return $id;
        } else {
            throw new Exception("Неверный код состояния HTTP: {$id}");
        }

        return $this;
    }


}