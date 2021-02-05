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
use System\Http\Response;
use System\Http\ResponseInterface;
use System\View\Template;
use System\View\Exception\TemplateNotFound;
use Packages\Themes\Component\Themes;

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

    // Имя страницы
    public $title = false;

    // Описание страницы
    public $description = false;

    // Ключевые слова
    public $keywords = false;

    // Память и синхронизация
    public $memory, $timing = 0;

    // Авто-загрузчик
    public $autoload = [
        'counter' => 0,
        'timing' => 0
    ];

    // Конструктор
    public function __construct(ContainerInterface $container, Themes $themes)
    {
        // Сохранить контейнер
        $this->container = $container;

        // Сохранить тему
        $this->themes = $themes;

        // Установить путь к теме
        $this->themes->setPath();

        //$this->themes->verify();
    }

    protected function getPathPackage()
    {
        $system = $this->container->get('config')::get('system');

        return PACKS . $system['default_package']  . DS;
    }

    protected function load(string $file, bool $load)
    {
        // Установить путь от куда грузить
        $path = $load ? $this->themes->getPath() : $this->getPath();

        //dd($path . 'view/');
        //$path = $this->getPath() . $file . '.php';

        //if (!file_exists($path . $file . '.php')) {
            //throw new TemplateNotFound("Шаблон {$file} не найден");
        //}

        //extract(self::get());

        //ob_start();

        //loadFile($file, $path);

        // Очистим дату для экономии используемых данных
        //self::сlear();

        //return ob_get_clean();
        return '';
    }

    // Показать шаблон
    public function view(string $file, bool $load = false, bool $preend = false): void
    {
        $response = $this->container->get('response');

        $content = $this->load($file, $load);

        if ($preend) {
            $response->body($content);
        } else {
            $response->write($content);
        }
    }

    // При завершении скрипта показать ответ клиенту
    public function output()
    {
        // Получить зависимость response
        $response = $this->container->get('response');

        if ($this->showed) {
            return  $response->clear();
        }

        // Загрузить настройки seo
        $seo = $this->container->get('config')::pull('system/seo');

        $doc = (object) [
            'local_html'    => $seo['local_html'],
            'title'         => $this->title ? $this->title : $seo['title'],
            'description'   => $this->description ? $this->description : $seo['description'],
            'keywords'      => $this->keywords ?? $seo['keywords'],
            'content'       => $response->getContent(),
            'view'          => $this,
            'memory'        => $this->memory,
            'timing'        => $this->timing,
            'autoload'      => $this->autoload
        ];

        self::setObject('response', $doc);

        $this->view('basic', true, true);

        //return $response->send();
    }

}
