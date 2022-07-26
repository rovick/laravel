<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;


class CreateNewTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new POST test to https://atomic.incfile.com/fakepost';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Ask for the amount of tries
        $tries = $this->ask('How many tries?');
        $fails = 0;


        // Run the tries
        for ($i=1; $i <= $tries; $i++) {

            // We can add the timeout() method to specify the max number of seconds we wait for the response
            $response = Http::post('https://atomic.incfile.com/fakepost');

            /*
                QUESTION 4
                With the successful() method, we ensure the request reaches its detination
                In the case it reaches the destination, we print OK and the status code
                In the case it doesn't reach it, we print FAIL and the status code
            */

            if($response->successful())
                $this->info('Request ' . $i . ': OK (' . $response->status() . ')');
            else{
                $this->error('Request ' . $i . ': FAIL (' . $response->status() . ')');
                // At this point I'd store the failed tries in DB or file to retry later if needed
                $fails++;
            }

        }

        /*
            QUESTION 5
            At this point we'd have a DB table or file with the failed requests info and we can retry
        */

        $retry = $this->confirm('There were ' . $fails . ' fails, do you want to retry them?', true);
        if($retry)
            // After this conditional, we could have the sript to retry only the failed requests, stored in DB or file
            $this->info('Here goes the retry script');
    }
}
