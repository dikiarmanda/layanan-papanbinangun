<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'no-reply@papanbinangun.id';
    public string $fromName   = 'Papan Binangun';
    public string $recipients = '';

    public string $userAgent = 'CodeIgniter';

    public string $protocol = 'smtp';

    public string $mailPath = '/usr/sbin/sendmail';

    public string $SMTPHost = 'mail.papanbinangun.id';

    public string $SMTPAuthMethod = 'login';

    public string $SMTPUser = 'no-reply@papanbinangun.id';

    public string $SMTPPass = '';

    public int $SMTPPort = 465;

    public int $SMTPTimeout = 10;

    public bool $SMTPKeepAlive = false;

    public string $SMTPCrypto = 'ssl';

    public bool $wordWrap = true;

    public int $wrapChars = 76;

    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
