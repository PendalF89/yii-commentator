<?php
namespace pendalf89\comments\extensions\count_widget;
use pendalf89\comments\helpers\CHelper as CHelper;

class CountWidget extends \CWidget
{
    /**
     * @var bool использовать ссылку
     */
    public $withLink = true;

    /**
     * Запуск видежта
     */
    public function run()
    {
        $this->render('count', array(
            'count' => CHelper::getNewCommentsCount(),
            'url' => CHelper::getNewCommentsUrl(),
        ));
    }
}