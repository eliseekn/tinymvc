<?php

namespace Framework\Support;

class Email
{   
    /**
    * to addresses
    *
    * @var string
    */
    protected static $to = '';
    
    /**
    * from address
    *
    * @var string
    */
    protected static $from = '';

    /**
     * cc addresses
     *
     * @var string
     */
    protected static $cc = '';

    /**
     * bcc addresses
     *
     * @var string
     */
    protected static $bcc = '';

    /**
     * subject
     *
     * @var string
     */
    protected static $subject = '';

    /**
     * message
     *
     * @var string
     */
    protected static $message = '';

    /**
     * headers
     *
     * @var array
     */
    protected static $headers = [];
    
    /**
     * clear all fields
     *
     * @return void
     */
    private static function clearFields(): void
    {
        self::$to = '';
        self::$cc = '';
        self::$bcc = '';
        self::$from = '';
        self::$subject = '';
        self::$message = '';
        self::$headers = [];
    }
    
    /**
     * add email header
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    private static function addHeader(string $key, string $value): void
    {
        self::$headers[] = [
            $key => $value 
        ];
    }
    
    /**
     * set to addresses
     *
     * @param  mixed $to
     * @return mixed
     */
    public static function to(string ...$to)
    {
        foreach ($to as $t) {
            self::$to .= $t . ', ';
        }

        self::$to = rtrim(', ', self::$to);
        return new self();
    }
    
    /**
     * set email subject
     *
     * @param  mixed $subject
     * @return mixed
     */
    public function subject(string $subject)
    {
        self::$subject = $subject;
        return $this;
    }
    
    /**
     * set message content
     *
     * @param  mixed $from
     * @return mixed
     */
    public function message(string $message)
    {
        self::$message = $message;
        return $this;
    }
    
    /**
     * set from address
     *
     * @param  mixed $from
     * @return mixed
     */
    public function setFrom(string $from)
    {
        self::addHeader('From', $from);
        return $this;
    }
    
    /**
     * set reply-to address
     *
     * @param  mixed $reply_to
     * @return mixed
     */
    public function replyTo(string $reply_to)
    {
        self::addHeader('Reply-To', $reply_to);
        return $this;
    }
    
    /**
     * add cc field
     *
     * @param  mixed $cc Cc addresses
     * @return mixed
     */
    public function addCC(string ...$cc)
    {
        foreach ($cc as $t) {
            self::$cc .= $t . ', ';
        }

        self::$cc = 'Cc: ' . rtrim(', ', self::$cc);
        return $this;
    }
    
    /**
     * add bcc field
     *
     * @param  mixed $bcc Bcc addresses
     * @return mixed
     */
    public function addBCC(string ...$bcc)
    {
        foreach ($bcc as $t) {
            self::$bcc .= $t . ', ';
        }

        self::$bcc = 'Bcc: ' . rtrim(', ', self::$bcc);
        return $this;
    }
    
    /**
     * set content as plain text
     *
     * @return mixed
     */
    public function asPlainText()
    {
        self::addHeader('Content-Type', 'text/plain; charset=utf-8');
        return $this;
    }
    
    /**
     * set content as html
     *
     * @return mixed
     */
    public function asHTML()
    {
        self::addHeader('MIME-Version', '1.0');
        self::addHeader('Content-type', 'text/html; charset=iso-8859-1');
        return $this;
    }
    
    /**
     * send email
     *
     * @return bool
     */
    public function send(): bool
    {
        $result = mail(self::$to, self::$subject, self::$message, self::$headers);
        self::clearFields();

        return $result;
    }
}