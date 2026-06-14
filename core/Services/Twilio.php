<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Services;

use Core\Notification\Sms\SmsInterface;
use Core\Support\Logger;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client as TwilioClient;

class Twilio implements SmsInterface
{
    public TwilioClient $twilioClient;

    public string $to;

    public string $from;

    public function __construct()
    {
        $this->twilioClient = new TwilioClient(
            config('services.twilio.account_sid'),
            config('services.twilio.auth_token')
        );
    }

    public function to(string $recipient)
    {
        $this->to = $recipient;

        return $this;
    }

    public function from(string $sender)
    {
        $this->from = $sender;

        return $this;
    }

    public function send(string $message)
    {
        try {
            $this->twilioClient->messages->create($this->to, [
                'body' => $message,
                'from' => $this->from,
            ]);

            return true;
        } catch (TwilioException $e) {
            Logger::exception($e);

            return false;
        }
    }
}
