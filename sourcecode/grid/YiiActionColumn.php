<?php

namespace fredyns\suite\grid;

/**
 * Action Column Generator for Yii-grid
 * Display Action button regarding model action control
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class YiiActionColumn extends \yii\grid\ActionColumn
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
     * Action Control class name
     *
     * @var string
     */
    public $actionControl = 'fredyns\suite\libraries\ActionControl';

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