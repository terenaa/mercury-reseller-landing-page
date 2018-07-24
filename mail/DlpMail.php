<?php
/**
 * This file is part of DLP Mail Bundle.
 *
 * (c) Eternal Apps
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EternalApps\Sculpin\Bundle\DlpMailBundle;

use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Sculpin;
use Sculpin\Core\Source\FileSource;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * DLP Mail.
 *
 * @author Krzysztof Janda <k.janda@eternalapps.pl>
 */
final class DlpMail implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $settings;

    /**
     * @var string
     */
    private $outputDir;

    /**
     * DLP Mail constructor.
     *
     * @param string $settings DLP Mail bundle kernel settings
     * @param string $env Environment
     */
    public function __construct($settings, $env)
    {
        $this->settings = $settings;
        $this->outputDir = __DIR__ . '/../output_' . $env . '/';
    }

    /**
     * @param SourceSetEvent $sourceSetEvent
     */
    public function afterRun(SourceSetEvent $sourceSetEvent)
    {
        /** @var FileSource $source */
        foreach ($sourceSetEvent->updatedSources() as $source) {
            if ('form.php' !== $source->file()->getFilename()) {
                continue;
            }

            $sendFilePath = $this->outputDir . 'form.php';

            if (file_exists($sendFilePath)) {
                $settings = [
                    'subject' => $this->settings['subject'],
                    'recipient' => [
                        'name' => $this->settings['recipient']['name'],
                        'email' => $this->settings['recipient']['email'],
                    ],
                    'captcha' => [
                        'secret' => $this->settings['recaptcha']['secret']
                    ]
                ];

                $f = fopen($sendFilePath, 'r+');
                $content = fread($f, filesize($sendFilePath));
                $content = str_replace('//[DLP_MAIL_SETTINGS]', '$settings = ' . var_export($settings, true) . ';', $content);
                fseek($f, 0);
                fwrite($f, $content);
                fclose($f);

                break;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Sculpin::EVENT_AFTER_RUN => 'afterRun',
        ];
    }
}
