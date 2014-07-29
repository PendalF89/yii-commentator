<?php
namespace modules\comments\extensions\count_widget;
use modules\comments\helpers\CHelper as CHelper;

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