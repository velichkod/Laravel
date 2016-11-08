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
<<<<<<< HEAD
    public function setAttachments($attachments)
    {
        $this->attachments[] = $attachments;
        return $this;
=======
    public function attach($attachments)
    {
        if (is_array($attachments)) {
            $this->attachments = array_merge($this->attachments, $attachments);
        } else {
            $this->attachments[] = $attachments;
        }

>>>>>>> 92e002be5d0082fb857814c5dedcb9621394b1cf
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
                $message->from($emailService->getFrom());
            }

            $message->to($emailService->getTo());
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
                    $message->bcc($b);
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
<<<<<<< HEAD
} 
=======

    public function refresh()
    {
        $this->attachments = [];
        $this->subject = '';
        $this->cc = [];
        $this->bcc = [];
        $this->to = '';
        $this->from = null;

        return $this;
    }
}
>>>>>>> 92e002be5d0082fb857814c5dedcb9621394b1cf
