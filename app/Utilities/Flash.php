<?php

namespace App\Utilities;

class Flash
{

    /**
     * Store a flash message with properties to session
     * @param $message
     * @param $type
     * @param string $key
     */
    public function create($message, $type, $key = 'flash_message')
    {
        session()->flash($key, [
            'message' => $message,
            'type'  => $type
        ]);
    }

    // Child Methods

    /**
     * An info info
     * @param
     * @param $message
     */
    public function info($message)
    {
        $this->create($message, 'information');
    }

    /**
     * Flash warning message
     * @param $message
     */
    public function warning($message)
    {
        $this->create($message, 'warning');
    }

    /**
     * Flash error message
     * @param $message
     */
    public function error($message)
    {
        $this->create($message, 'error');
    }

    /**
     * Flash success message
     * @param $message
     */
    public function success($message)
    {
        $this->create($message, 'success');
    }


}