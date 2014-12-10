<?php

namespace ExchangeRates;

use ExchangeRates\Component\HttpKernel\Controller\ControllerResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Translation\Translator;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class App
{

    /**
     * Инициализация приложения
     *
     * @return void
     */
    public function init()
    {
        $request = Request::createFromGlobals();

        $matcher = new UrlMatcher($this->getRoutes(), new RequestContext());

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher));

        $resolver = new ControllerResolver();
        $resolver->setTwig($this->getTwig());
        $resolver->setEntityManager($this->getEntityManager());
        $resolver->setTranslator($this->getTranslator());
        $kernel = new HttpKernel($dispatcher, $resolver);

        $response = $kernel->handle($request);
        $response->send();

        $kernel->terminate($request, $response);
    }

    /**
     * Возвращает маршрутов uri -> контроллер
     *
     * @return RouteCollection
     */
    protected function getRoutes()
    {
        $routes = new RouteCollection();
        $loader = new YamlFileLoader(new FileLocator($this->getConfigPath()));
        $routes->addCollection($loader->load("routes.yml"));

        return $routes;
    }

    /**
     * Возвращает объект шаблонизатора
     *
     * @return \Twig_Environment
     */
    protected function getTwig()
    {
        $loader = new \Twig_Loader_Filesystem($this->getBasePath() . "/views");
        return new \Twig_Environment($loader, array(
            "cache" => $this->getBasePath() . "/cache",
        ));
    }

    /**
     * Возвращает менеджер сущьностей doctrine
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        $yamlParser = new Parser();

        return EntityManager::create(
            $yamlParser->parse(file_get_contents($this->getConfigPath() . "/database.yml")),
            Setup::createAnnotationMetadataConfiguration(
                array($this->getBasePath() . "/src/ExchangeRates/Entity"),
                false
            )
        );
    }

    /**
     * Возвращает объект переводчика
     *
     * @return Translator
     */
    protected function getTranslator()
    {
        $lang = "ru_RU";

        $translator = new Translator($lang);
        $translator->addLoader("yaml", new \Symfony\Component\Translation\Loader\YamlFileLoader());
        $translator->addResource('yaml', $this->getBasePath() . "/locales/" . $lang . ".yml", $lang);

        return $translator;
    }

    /**
     * Возвращает путь к корню проекта
     *
     * @return string
     */
    protected function getBasePath()
    {
        return __DIR__ . "/../..";
    }

    /**
     * Возвращает путь к каталогу с конфигами
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return $this->getBasePath() . "/config";
    }
}
