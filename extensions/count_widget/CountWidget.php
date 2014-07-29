<?php
namespace pendalf89\yii_commentator\extensions\count_widget;
use pendalf89\yii_commentator\helpers\CHelper as CHelper;

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