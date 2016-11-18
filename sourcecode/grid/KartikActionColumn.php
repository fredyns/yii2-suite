<?php

namespace fredyns\suites\grid;

use Yii;

/**
 * Action Column Generator for Kartik-grid
 * Display Action button regarding model action control
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class KartikActionColumn extends \kartik\grid\ActionColumn
{
    /**
     * @inheritdoc
     */
    public $header = 'Action';

    /**
     * @inheritdoc
     */
    public $template = '{actionmenu}';

    /**
     * @inheritdoc
     */
    public $headerOptions = ['class' => 'skip-export'];

    /**
     * @inheritdoc
     */
    public $contentOptions = ['class' => 'skip-export'];

    /**
     * Action Control class name
     *
     * @var string
     */
    public $actionControl = 'fredyns\suites\libraries\ActionControl';

    /**
     * configuration for ActionMenu widget
     *
     * @var array
     */
    public $menuOptions = [];

    /**
     * @inheritdoc
     */
    protected function initDefaultButtons()
    {
        // siapa tau ada yg nekat mau ganti template output. wkwk
        parent::initDefaultButtons();

        if (!isset($this->buttons['actionmenu']))
        {
            $this->buttons['actionmenu'] = function ($url, $model, $key)
            {
                $actionControl = Yii::createObject([
                        'class' => $this->actionControl,
                        'model' => $model,
                ]);

                return $actionControl->dropdown($this->menuOptions);
            };
        }
    }

}