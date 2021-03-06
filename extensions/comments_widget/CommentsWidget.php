<?php
namespace pendalf89\yii_commentator\extensions\comments_widget;
use Yii;
use pendalf89\yii_commentator\models\Comment as Comment;

class CommentsWidget extends \CWidget
{
	/**
	 * @var boolean разрешить ли использовать микроразметку. Для корректной работы виджет комментариев должен
	 * вызываться внутри статьи (внутри тега с атрибутами 'itemtype="http://schema.org/Article"')
	 * @see http://ruschema.org/Article
	 * @see http://ruschema.org/Comment
	 */
	public $enableMicrodata = false;

    /**
     * @var array массив с комментариями
     */
    public $models = array();

    /**
     * @var object экземпляр класса Comment
     */

    protected $model;
    /**
     * @var array массив с комментариями для дерева
     */

	protected $commentsArray = array();

    /**
     * Инициализация виджета
     */
    public function init()
    {
        parent::init();

        $this->model = new Comment();
        $this->models = !empty($this->models)
            ? $this->models
            : Comment::model()->page()->approved()->findAll();

        $this->publishAssets();
        $this->buildCommentsArray();
    }

    /**
     * Запуск видежта
     */
    public function run()
    {
        $this->render('comments', array(
            'model' => $this->model,
            'count' => count($this->models),
        ));
    }

    /**
     * Выводит рекурсивно дерево комментариев
     * @param int $parent_id
     * @param int $level
     */
    public function renderTree($parent_id=0, $level=0)
    {
        // Условие выхода из рекурсии
        if ( !isset($this->commentsArray[$parent_id]) )
            return;

        foreach($this->commentsArray[$parent_id] as $comment)
        {
            $this->render('comment', array(
                'comment' => $comment['model'],
                'level' => $level,
                'margin' => self::calculateMargin($level),
            ));

            $level++;
            $this->renderTree($comment['model']->id, $level);
            $level--;
        }
    }

    /**
     * Записывает и возвращает результат работы метода renderTree()
     * @return string дерево комментариев
     */
    public function getTree()
    {
        ob_start();
        $this->renderTree();
        $tree = ob_get_contents();
        ob_end_clean();
        return $tree;
    }

    /**
     * Публикует ресурсы
     */
    public function publishAssets()
    {
        $url = Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('comments.extensions.comments_widget.assets'), false, -1, true
            ) . '/';

        Yii::app()->clientScript->registerCssFile($url . 'css/styles.min.css');
        Yii::app()->clientScript->registerScriptFile($url . 'js/script.min.js');
    }

    /**
     * Рассчитывает отсутпы по уровню
     * @param $level уровень, для которого требуется рассчитать отсутп
     * @return string|int отсутп по уровню
     */
    private static function calculateMargin($level)
    {
        $margin = Yii::app()->getModule('comments')->margin;
        $maxLevel = Yii::app()->getModule('comments')->levels;

        if ($level > $maxLevel)
            $level = $maxLevel;

        return ($level != 0) ? $level * $margin : 0;
    }

    /**
     * Строит массив для дерева комментариев
     */
    private function buildCommentsArray()
    {
        $arr = array();
        foreach($this->models as $comment)
        {
            $temp['model'] = $comment;
            $arr[$comment->parent_id][] = $temp;
        }

        $this->commentsArray = $arr;
    }
}