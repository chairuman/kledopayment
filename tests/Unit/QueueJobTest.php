<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\DeletePayment;
use Illuminate\Support\Facades\Bus;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Queue;

class QueueJobTest extends TestCase
{
    use DispatchesJobs;
    /**
     * function to test that job is dispatch successfully
     * @return void
     */
    public function test_job_dispatched()
    {
        Bus::fake();
        $job = new DeletePayment(47);
        Bus::dispatch($job);
        Bus::assertDispatched(DeletePayment::class);
    }

    /**
     * function to test that queue job is running
     * @return void
     */
    public function test_queue_job_pushed()
    {
        Queue::fake();
        $job = new DeletePayment(47);
        Queue::push($job);
        $this->dispatch($job);
        Queue::assertPushed(DeletePayment::class);
    }
}
