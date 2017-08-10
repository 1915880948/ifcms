<?php
/**
 * @category BladeView
 * @author   gouki <gouki.xiao@gmail.com>
 * @created 2017/5/3 23:48
 * @since
 */

namespace app\common\blade;

use Yii;
use yii\base\InvalidParamException;
use yii\base\ViewRenderer;
use yii\helpers\FileHelper;
use yii\web\View;

/**
 * Class BladeView
 * @package common\core\base
 */
class BladeView extends View
{
    /**
     * @var array the view files currently being rendered. There may be multiple view files being
     * rendered at a moment because one view may be rendered within another.
     */
    private $_viewFiles = [];

    /**
     * @param string $viewFile
     * @param array $params
     * @param null $context
     * @return string
     */
    public function renderFile($viewFile, $params = [], $context = null)
    {
        $viewFile = Yii::getAlias($viewFile);

        if($this->theme !== null){
            $viewFile = $this->theme->applyTo($viewFile);
        }
        if(is_file($viewFile)){
            $viewFile = FileHelper::localize($viewFile);
        } else{
            throw new InvalidParamException("The view file does not exist: $viewFile");
        }

        $oldContext = $this->context;
        if($context !== null){
            $this->context = $context;
        }
        if($this->context->hasProperty('layout')){
            $this->context->layout = false;
        }

        $output = '';
        $this->_viewFiles[] = $viewFile;

        if($this->beforeRender($viewFile, $params)){
            Yii::trace("Rendering view file: $viewFile", __METHOD__);
            $ext = 'blade';
            if(isset($this->renderers[$ext])){
                if(is_array($this->renderers[$ext]) || is_string($this->renderers[$ext])){
                    $this->renderers[$ext] = Yii::createObject($this->renderers[$ext]);
                }
                /* @var $renderer ViewRenderer */
                $renderer = $this->renderers[$ext];
                $output = $renderer->render($this, $viewFile, $params);
            } else{
                $output = $this->renderPhpFile($viewFile, $params);
            }
            $this->afterRender($viewFile, $params, $output);
        }

        array_pop($this->_viewFiles);
        $this->context = $oldContext;
        return $output;
    }
}
