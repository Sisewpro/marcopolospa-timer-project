<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TimerCard extends Component
{
    public $cardName;
    public $therapistName;
    public $time;
    public $status;
    public $id;

    public function __construct($cardName, $therapistName, $time, $status, $id)
    {
        $this->cardName = $cardName;
        $this->therapistName = $therapistName;
        $this->time = $time;
        $this->status = $status;
        $this->id = $id;
    }

    public function render()
    {
        return view('components.timer-card');
    }
}
