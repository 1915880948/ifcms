<?php
/**
 * @category ViewRenderer
 * @author   gouki <gouki.xiao@gmail.com>
 * @created 2017/5/3 23:53
 * @since
 */

namespace app\common\blade;

use Yii;
use yii\base\InvalidParamException;
use yii\base\ViewRenderer as BaseViewRenderer;

include Yii::getAlias('@app/common/blade/functions.php');
/**
 * Class ViewRenderer
 * @package common\core\template\blade
 */
class ViewRenderer extends BaseViewRenderer
{
    /**
     * Extension the special view files will use
     * @var string
     */
    public $extension = 'blade';
    /**
     * Path where view cache will be located
     * Warning: this path must be writable by PHP.
     * @var  String
     */
    public $cachePath;
    /**
     * List of view paths
     * @var String[]
     */
    public $viewPaths = [];
    /**
     * Blade object used to make the dawgh
     * @var  Blade
     */
    public $blade;
    /**
     * Main view that will be used as layout for the rest of
     * views in this render.
     * If it's null the layout will be the view sent in the
     * render.
     * @var string
     */
    protected $layoutView;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if(is_null($this->cachePath) || !is_string($this->cachePath)){
            throw new InvalidParamException('Cache path must be a declared string');
        }
        // $this->viewPaths = ArrayHelper::merge($this->viewPaths, [Yii::$app->getViewPath()]);

        foreach($this->viewPaths as $key => $path){
            $this->viewPaths[$key] = Yii::getAlias($path);
        }
        $this->viewPaths[] = Yii::$app->getViewPath();
        $this->blade = new Blade($this->viewPaths, Yii::getAlias($this->cachePath));
        $this->blade->view()
                    ->addExtension($this->extension, $this->extension);
        $this->extend();
    }

    /**
     * @param \yii\base\View $view
     * @param string $viewFile
     * @param array $params
     * @return string
     */
    public function render($view, $viewFile, $params)
    {
        $viewFile = $this->normalizeView($viewFile);

        if(is_null($this->layoutView)){
            $base_view = $viewFile;
            $viewFile = null;
        } else{
            $base_view = $this->layoutView;
            $this->layoutView = null;
        }
        $base_view = pathinfo($base_view, PATHINFO_FILENAME);
        $viewObject = $this->blade->view()
                                  ->make($base_view, $params)
                                  ->with('view', $view);

        if(!is_null($viewFile)){
            $params['view'] = $view;
            $viewObject->nest($viewFile . '_view', $viewFile, $params);
        }
        return $viewObject->render();
        // ob_start();
        // ob_implicit_flush(false);
        // echo $viewObject->render();
        // return ob_get_clean();
    }

    /**
     * Returns View blade Object
     * @return \Illuminate\View\Factory
     */
    public function view()
    {
        return $this->blade->view();
    }

    /**
     * Adds a layout to the Blade System to use as a base view
     * @param $layout
     */
    public function addLayout($layout)
    {
        $this->layoutView = $this->normalizeView($layout);
    }

    /**
     * @param String $view
     * @return mixed
     */
    protected function normalizeView($view)
    {
        $directory = pathinfo($view, PATHINFO_DIRNAME);
        $viewFile = pathinfo($view, PATHINFO_FILENAME);

        $this->blade->view()
                    ->getFinder()
                    ->addLocation($directory . '/');

        return $viewFile;
    }

    /**
     *
     */
    protected function extend(){
        $this->blade->getCompiler()->extend(function($value) {
            return preg_replace('/\@define(.+)/', '<?php ${1}; ?>', $value);
        });
    }
}
