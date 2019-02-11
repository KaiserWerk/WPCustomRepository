<?php

class StopwatchHelper
{
    /** @var float */
    private $start, $stop;
    
    public function start()
    {
        $this->start = self::microtime_float();
    }
    
    public function stop()
    {
        $this->stop = self::microtime_float();
        return $this;
    }
    
    public function getIntervalSeconds() : float
    {
        // NOT STARTED
        if (empty($this->start)) {
            return 0;
        }
        // NOT STOPPED
        if (empty($this->stop)) {
            return ($this->stop - self::microtime_float());
        }
        
        return $this->stop - $this->start;
    }
    
    /**
     * FOR MORE INFO SEE http://us.php.net/microtime
     *
     * @return float
     */
    private static function microtime_float() : float
    {
        list($usec, $sec) = explode(' ', microtime());
        
        return ((float)$usec + (float)$sec);
    }
}