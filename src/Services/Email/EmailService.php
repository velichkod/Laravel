<?php
/**
 * Created by PhpStorm.
 * User: themightysapien
 * Date: 11/23/14
 * Time: 11:48 AM
 */

namespace Optimait\Laravel\Services\Email;


use Closure;
use Mail;

class EmailService
{
    private $attachments = array();
    private $to;
    private $from = null;
    private $cc;
    private $bcc;
    private $subject;
    private $sendAs;
    private $fromAs;

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param mixed $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments[] = $attachments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param mixed $bcc
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }

    public function sendAs($name)
    {
        $this->sendAs = $name;
        return $this;
    }

    public function fromAs($name)
    {
        $this->fromAs = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCc()
    {
        return $this->cc;

    }

    /**
     * @param mixed $cc
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function sendEmail($view, $data, Closure $closure = null)
    {
        if (!is_null($closure) && is_callable($closure)) {
            $closure($this);
        }
        $emailService = $this;
        Mail::send($view, $data, function ($message) use ($emailService) {
            if (!is_null($emailService->getFrom())) {
                if (!is_null($this->fromAs)) {
                    $message->from($emailService->getFrom(), $this->fromAs);
                } else {
                    $message->from($emailService->getFrom(), $this->fromAs);
                }
            }

            if (!is_null($this->sendAs)) {
                $message->to($emailService->getTo(), $this->sendAs);
            } else {
                $message->to($emailService->getTo());
            }

            $message->subject($emailService->getSubject());

            $bcc = $emailService->getBcc();
            if (!empty($bcc)) {
                foreach ($bcc as $b) {
                    $message->bcc($b);
                }
            }
            $cc = $emailService->getCc();
            if (!empty($cc)) {
                foreach ($cc as $b) {
                    $message->cc($b);
                }
            }
            $attachments = $emailService->getAttachments();
            if (!empty($attachments)) {
                foreach ($attachments as $file) {
                    $message->attach($file);
                }
            }
        });
    }
} 
