<?php declare(strict_types=1);
/**
 * NomiCMS - Content Management System
 *
 * @author Tosyk, Photon
 * @package nomicms/NomiCMS
 * @link   http://nomicms.ru
 */

namespace System\View;

// Использовать
use System\Container\ContainerInterface;
use System\View\Template;
use System\View\Exception\TemplateNotFound;
use Packages\Themes\Component\Themes;
use System\Http\ResponseInterface;

/**
 * Класс View
 */
class View extends Template
{
    // Контейнер зависимостей
    protected $container;

    // Хранилище
    protected $themes;

    // Скрыть контент
    public $showed = false;

    // Статус
    public $status = false;

    // Конструктор
    public function __construct(ContainerInterface $container, Themes $themes)
    {
        // Сохранить контейнер
        $this->container = $container;

        // Сохранить тему
        $this->themes = $themes;
    }

    // Получить путь к шаблонам
    protected function getPath(bool $priority)
    {
        if ($priority) {
            return $this->themes->getPath();
        }

        $route = $this->container->get('config')::get('route');

        return PACKS . $route['package']  . '/view/';
    }

    // Загрузить шаблон
    protected function load(string $file, bool $priority = true)
    {    
        // Получить путь к шаблону
        $path = $this->getPath($priority);

        if (is_dir($path) === false) {
            throw new TemplateNotFound("Папка с шаблонами не найдена, проверьте ее по адресу: {$path}");
        }

        // Если шаблон не найден сообщить об этом
        if (file_exists($path . $file . '.php') === false) {
            throw new TemplateNotFound("Шаблон {$file} $path не найден");
        }

        extract($this::get($file));

        ob_start();

        require_once $path . $file . '.php';

        return ob_get_clean();
    }

    protected function template(string $file)
    {
        echo $this->load($file);
    }

    // Render
    public function render(string $template, bool $priority = false, bool $write = false): void
    {
        if ($this->status) {
            return ;
        }

        if ($template == 'layout') {
            $this->status = true;
        }

        // Подключить response
        $response = $this->container->get('response');

        // Загрузить файл
        $content = $this->load($template, $priority);

        // Сохранить в тело ответа
        if ($write) {
            $response->body($content);
        // Либо в контент
        } else {
            $response->write($content);
        }
    }

    // Вывести на экран все содержимое
    //public function __destruct()
    public function put()
    {
        // Получить зависимость response
        $response = $this->container->get('response');

        // Загрузить настройки seo
        $seo = $this->container->get('config')::pull('system/seo');

        // Параметры для всех страниц
        $all = [
            'title' => $this->title ?? $seo['title']
        ];

        // Параметры для макета
        $layout = [
            'lang'      => $seo['local_html'],
            'desc'      => $this->description ?? $seo['description'],
            'keywords'  => $this->keywords ?? $seo['keywords'],
            'content'   => $response->getContent()
        ];

        $footer = [
            'memory' => round((memory_get_usage() - NOMI_MEMORY) / 1024),
            'timing' => round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 6)
        ];

        // Установить настройки указанные выше
        $this->setAll($all);
        $this->set($layout, 'layout');
        $this->set($footer, 'footer');

        // Рендерить макет
        $this->render('layout', true, true);

        //return $response;
    }

}
