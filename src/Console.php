<?php
namespace Luwake\Console;

class Console
{

    private static $count = [];

    private static $profile = 0;

    private static $timer = [];

    private static $commands = [];

    /**
     * 输出信息
     */
    public static function log($val = '')
    {
        self::make('log', [
            call_user_func_array('sprintf', func_get_args())
        ]);
    }

    /**
     * console.log 别名，输出信息
     */
    public static function info($val = '')
    {
        self::make('info', [
            call_user_func_array('sprintf', func_get_args())
        ]);
    }

    /**
     * 控制台打印一条 "debug" 级别的日志消息
     */
    public static function debug($val = '')
    {
        self::make('debug', [
            call_user_func_array('sprintf', func_get_args())
        ]);
    }

    /**
     * 输出警告信息
     */
    public static function warn($val = '')
    {
        self::make('warn', [
            call_user_func_array('sprintf', func_get_args())
        ]);
    }

    /**
     * 输出信息时，在最前面加一个红色的叉，表示出错，同时会显示错误发生的堆栈。
     */
    public static function error($val = '')
    {
        self::make('error', [
            call_user_func_array('sprintf', func_get_args())
        ]);
    }

    /**
     * error() 方法的别称
     */
    public static function exception($val = '')
    {
        self::make('exception', [
            call_user_func_array('sprintf', func_get_args())
        ]);
    }

    /**
     * 用于计数，输出它被调用了多少次。
     */
    public static function count($label = 'default')
    {
        if (! isset(self::$count[$label])) {
            self::$count[$label] = 0;
        }
        
        self::$count[$label] ++;
        
        self::make('log', [
            sprintf("%s: %d", $label, self::$count[$label])
        ]);
    }

    /**
     * 重置指定标签的计数器值
     */
    public static function countReset($label = 'default')
    {
        unset(self::$count[$label]);
    }

    /**
     * 用于将显示的信息分组，可以把信息进行折叠和展开。
     */
    public static function group($label = 'console.group')
    {
        self::make('group', [
            $label
        ]);
    }

    /**
     * 与console.group方法很类似，唯一的区别是该组的内容，在第一次显示时是收起的（collapsed），而不是展开的。
     */
    public static function groupCollapsed($label = 'console.group')
    {
        self::make('groupCollapsed', [
            $label
        ]);
    }

    /**
     * 结束内联分组
     */
    public static function groupEnd($label = 'console.group')
    {
        self::make('groupEnd', [
            $label
        ]);
    }

    /**
     * Starts the browser's built-in profiler (for example, the Firefox performance tool).
     * You can specify an optional name for the profile.
     */
    public static function profile($profileName = '')
    {
        self::$profile ++;
        
        self::make('log', [
            sprintf("Profile '%s' started.", $profileName ?: 'Profile ' . self::$profile)
        ]);
    }

    /**
     * Stops the profiler.
     * You can see the resulting profile in the browser's performance tool (for example, the Firefox performance tool).
     */
    public static function profileEnd($profileName = '')
    {
        if (self::$profile <= 0) {
            return false;
        }
        
        self::make('log', [
            sprintf("Profile '%s' finished.", $profileName ?: 'Profile ' . self::$profile)
        ]);
        
        self::$profile --;
    }

    /**
     * 计时开始
     */
    public static function time($timerName)
    {
        self::$timer[$timerName] = microtime(true);
    }

    /**
     * 输出计时但不停止
     */
    public static function timeLog($timerName)
    {
        $distance = microtime(true) - self::$timer[$timerName];
        
        self::make('log', [
            sprintf("%s: %f ms", $timerName, $distance * 1000)
        ]);
    }

    /**
     * 计时结束
     */
    public static function timeEnd($timerName)
    {
        $distance = microtime(true) - self::$timer[$timerName];
        
        self::make('log', [
            sprintf("%s: %f ms", $timerName, $distance * 1000)
        ]);
        
        unset(self::$timer[$timerName]);
    }

    /**
     * 添加一个标记到浏览器的 Timeline 或 Waterfall 工具。
     */
    public static function timeStamp($label)
    {
        list ($millisecond, $datetime) = explode(' ', microtime());
        
        self::make('log', [
            sprintf("%s: %s %f ms", $label, date('Y-m-d H:i:s', $datetime), $millisecond * 1000)
        ]);
    }

    /**
     * 将复合类型的数据转为表格显示。
     */
    public static function table($data = [], $columns = [])
    {
        self::make('table', [
            $data,
            $columns
        ]);
    }

    /**
     * 追踪函数的调用过程
     */
    public static function trace()
    {
        self::group('console.trace');
        
        $traces = debug_backtrace();
        
        foreach ($traces as $k => $trace) {
            if ($k == 0) {
                continue;
            }
            self::make('log', [
                sprintf('%s::%s@%s:%s', $trace['class'], $trace['function'], $trace['file'], $trace['line'])
            ]);
        }
        
        self::groupEnd('console.trace');
    }

    /**
     * 打印一条以三角形符号开头的语句，可以点击三角展开查看对象的属性。This listing lets you use disclosure triangles to examine the contents of child objects.
     */
    public static function dir($object)
    {
        //TODO
        self::make('log', [
            $object
        ]);
    }

    /**
     * 打印 XML/HTML 元素表示的指定对象，否则显示 JavaScript 对象视图
     */
    public static function dirxml($object)
    {
        //TODO
        self::make('log', [
            $object
        ]);
    }

    /**
     * assert方法接受两个参数，第一个参数是表达式，第二个参数是字符串。只有当第一个参数为false，才会输出第二个参数，否则不会有任何结果。
     */
    public static function assert($assertion, $description = null)
    {
        assert_options(ASSERT_CALLBACK, function ($file, $line, $code, $desc) use ($assertion, $description) {
            Console::make('error', [
                sprintf('Assertion failed: %s', ($description ?: $assertion))
            ]);
        });
        
        assert($assertion);
    }

    /**
     * 清除当前控制台的所有输出，将光标回置到第一行。
     */
    public static function clear()
    {
        self::$commands = [];
    }

    private static function make($method, $args = [])
    {
        self::$commands[] = sprintf('console.%s.apply(console, %s);', $method, json_encode($args));
    }

    public static function output()
    {
        return implode(PHP_EOL, self::$commands);
    }
}
