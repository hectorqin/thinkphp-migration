<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think\migration;

use think\console\Output As BaseOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;

/**
 * Class Output
 * @package think\migration
 *
 * @see     \think\console\output\driver\Console::setDecorated
 * @method void setDecorated($decorated)
 *
 * @see     \think\console\output\driver\Buffer::fetch
 * @method string fetch()
 *
 * @method void info($message)
 * @method void error($message)
 * @method void comment($message)
 * @method void warning($message)
 * @method void highlight($message)
 * @method void question($message)
 */
class Output implements OutputInterface
{
    protected $output;

    public function __construct(BaseOutput $output)
    {
        $this->output = $output;
    }

   /**
     * Writes a message to the output.
     *
     * @param string|iterable $messages The message as an iterable of strings or a single string
     * @param bool            $newline  Whether to add a newline
     * @param int             $options  A bitmask of options (one of the OUTPUT or VERBOSITY constants), 0 is considered the same as self::OUTPUT_NORMAL | self::VERBOSITY_NORMAL
     */
    public function write($messages, $newline = false, $options = 0)
    {
        // OutputInterface:
        // const OUTPUT_NORMAL = 1;
        // const OUTPUT_RAW = 2;
        // const OUTPUT_PLAIN = 4;

        // BaseOutput:
        // const OUTPUT_NORMAL = 0;
        // const OUTPUT_RAW    = 1;
        // const OUTPUT_PLAIN  = 2;

        $options = $options ? log($options, 2) : 0;
        if (!in_array($options, [BaseOutput::OUTPUT_NORMAL, BaseOutput::OUTPUT_RAW, BaseOutput::OUTPUT_PLAIN])) {
            throw new \RuntimeException("\$options only support OUTPUT constants");
        }
        return $this->output->write($messages, $newline, $options);
    }

    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param string|iterable $messages The message as an iterable of strings or a single string
     * @param int             $options  A bitmask of options (one of the OUTPUT or VERBOSITY constants), 0 is considered the same as self::OUTPUT_NORMAL | self::VERBOSITY_NORMAL
     */
    public function writeln($messages, $options = 0)
    {
        // OutputInterface:
        // const OUTPUT_NORMAL = 1;
        // const OUTPUT_RAW = 2;
        // const OUTPUT_PLAIN = 4;

        // BaseOutput:
        // const OUTPUT_NORMAL = 0;
        // const OUTPUT_RAW    = 1;
        // const OUTPUT_PLAIN  = 2;

        $options = $options ? log($options, 2) : 0;
        if (!in_array($options, [BaseOutput::OUTPUT_NORMAL, BaseOutput::OUTPUT_RAW, BaseOutput::OUTPUT_PLAIN])) {
            throw new \RuntimeException("\$options only support OUTPUT constants");
        }
        return $this->output->writeln($messages, $options);
    }

    /**
     * Sets the verbosity of the output.
     *
     * @param int $level The level of verbosity (one of the VERBOSITY constants)
     */
    public function setVerbosity($level)
    {
        // OutputInterface:
        // const VERBOSITY_QUIET = 16;
        // const VERBOSITY_NORMAL = 32;
        // const VERBOSITY_VERBOSE = 64;
        // const VERBOSITY_VERY_VERBOSE = 128;
        // const VERBOSITY_DEBUG = 256;

        // BaseOutput:
        // const VERBOSITY_QUIET        = 0;
        // const VERBOSITY_NORMAL       = 1;
        // const VERBOSITY_VERBOSE      = 2;
        // const VERBOSITY_VERY_VERBOSE = 3;
        // const VERBOSITY_DEBUG        = 4;

        return $this->output->setVerbosity(log($level, 2) - 4);
    }

    /**
     * Gets the current verbosity of the output.
     *
     * @return int The current level of verbosity (one of the VERBOSITY constants)
     */
    public function getVerbosity()
    {
        return pow(2, $this->output->getVerbosity() + 4);
    }

    /**
     * Returns whether verbosity is quiet (-q).
     *
     * @return bool true if verbosity is set to VERBOSITY_QUIET, false otherwise
     */
    public function isQuiet()
    {
        return $this->output->isQuiet();
    }

    /**
     * Returns whether verbosity is verbose (-v).
     *
     * @return bool true if verbosity is set to VERBOSITY_VERBOSE, false otherwise
     */
    public function isVerbose()
    {
        return $this->output->isVerbose();
    }

    /**
     * Returns whether verbosity is very verbose (-vv).
     *
     * @return bool true if verbosity is set to VERBOSITY_VERY_VERBOSE, false otherwise
     */
    public function isVeryVerbose()
    {
        return $this->output->isVeryVerbose();
    }

    /**
     * Returns whether verbosity is debug (-vvv).
     *
     * @return bool true if verbosity is set to VERBOSITY_DEBUG, false otherwise
     */
    public function isDebug()
    {
        return $this->output->isDebug();
    }

    /**
     * Sets the decorated flag.
     *
     * @param bool $decorated Whether to decorate the messages
     */
    public function setDecorated($decorated)
    {
        return $this->output->setDecorated($decorated);
    }

    /**
     * Gets the decorated flag.
     *
     * @return bool true if the output will decorate messages, false otherwise
     */
    public function isDecorated()
    {
        return $this->output->isDecorated();
    }

    public function setFormatter(OutputFormatterInterface $formatter)
    {
        throw new \RuntimeException("Not support");
    }

    /**
     * Returns current output formatter instance.
     *
     * @return OutputFormatterInterface
     */
    public function getFormatter()
    {
        throw new \RuntimeException("Not support");
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->output, $method], $args);
    }
}
