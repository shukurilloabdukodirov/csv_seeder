<?php
namespace App\Webhook;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable as SupportStringable;
use Stringable;

class TelegramHandler extends WebhookHandler
{
    protected function handleChatMessage(SupportStringable $text): void
    {
        if(!empty($this->message->contact())){
            $contactUserId = $this->message->contact()->userId();
            $senderId = $this->message->from()->id();
            
            if($contactUserId == $senderId){
            
                $text = "Thank you for you message. We send weather info soon!";

                $this->deleteKeyboard();

                $this->chat->message($text)->removeReplyKeyboard()->send();
                exit;
            }
            else{

                $this->chat->message("Phone number is wrong!")->send();
                exit;
            }
        }

        if($this->message->text()){
          
            $this->chat->message("Phone number is wrong!")->send();

        }

    }
    protected function handleUnknownCommand(SupportStringable $text) :void{
    }
    public function start()
    {
        $keyboard = ReplyKeyboard::make()->button('Yuborish')->width(1)->requestContact()->resize(true);
        $this->chat->message('Send your phone number')->replyKeyboard($keyboard)->send();
    }

    public static function deliverNotify($payload){
     
        $chat = TelegraphChat::query()->where(['chat_id'=>$payload['chat_id']])->firstOrFail();
        
        $text = $payload['text'];
 
        $chat->html($text)->send();
    }


}
