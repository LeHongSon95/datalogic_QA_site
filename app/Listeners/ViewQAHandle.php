<?php

namespace App\Listeners;

use App\Events\ViewQAEvent;
use App\Repositories\Question\QuestionRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Session\Store;

class ViewQAHandle
{
    /**
     * @var $questionRepository
     */
    protected $questionRepository;
    private $session;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Store $session, QuestionRepository  $questionRepository)
    {
        $this->session = $session;
        $this->questionRepository = $questionRepository;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ViewQAEvent  $event
     * @return void
     */
    public function handle(ViewQAEvent $event)
    {
        if (!$this->isQaViewed($event->id))
	    {
	        $this->questionRepository->updateView($event->id);
	        $this->storeQa($event->id);
	    }
    }

    /** 
     * Check key exists
     * 
     * @param int $id
     * @return bool
     */ 
    private function isQaViewed($id)
	{
        $viewed = $this->session->get(config('constants.nameSessionViewCount'), []);
	    return array_key_exists($id, $viewed);
	}

    /** 
     * Store qa sesstion
     * 
     * @param mixed $qa
     * @return void
     */ 
    private function storeQa($id)
	{
	    $key = config('constants.nameSessionViewCount').'.' . $id;
	    $this->session->put($key, time());
	}
}
