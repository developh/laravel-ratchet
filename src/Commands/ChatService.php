<?php

namespace App\Console\Commands;

use App\WebSockets\Chats\Chat;
use App\WebSockets\Receiver;
use App\WebSockets\Routes\Routes;
use Illuminate\Console\Command;
use Ratchet\App;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;


/**
 * Class ChatService
 * @package App\Console\Commands
 */
class ChatService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:websocket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start websocket';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Receiver()
                )
            ),
            env('CHAT_PORT',8080)
        );

        $server->run();
    }
}
