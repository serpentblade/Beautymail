<?php

namespace Snowfire\Beautymail;

use Illuminate\Contracts\Mail\Mailer;

class Beautymail
{
    /**
     * Contains settings for emails processed by Beautymail.
     *
     * @var
     */
    private $settings;

    /**
     * The mailer contract depended upon.
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    private $mailer;

    /**
     * Initialise the settings and mailer.
     *
     * @param $settings
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->mailer = app()->make('Illuminate\Contracts\Mail\Mailer');
        $this->setLogoPath();
    }

    /**
     * Retrieve the settings.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->settings;
    }

    /**
     * Send a new message using a view.
     *
     * @param string|array    $view
     * @param array           $data
     * @param \Closure|string $callback
     *
     * @return void
     */
    public function send($view, array $data, $callback)
    {
        $data = array_merge($this->settings, $data);

        $this->mailer->send($view, $data, $callback);
    }

    /**
     * Send a new message using the a view via queue.
     *
     * @param string|array    $view
     * @param array           $data
     * @param \Closure|string $callback
     *
     * @return void
     */
    public function queue($view, array $data, $callback)
    {
        $data = array_merge($this->settings, $data);

        $this->mailer->queue($view, $data, $callback);
    }

    /**
     * @param $view
     * @param array $data
     * @return \Illuminate\View\View
     */
    public function view($view, array $data = [])
    {
        $data = array_merge($this->settings, $data);

        return view($view, $data);
    }

    /**
     * @return mixed
     */
    private function setLogoPath()
    {
        if (isset($this->settings['logo']['path']))
        {
            $this->settings['logo']['path'] = str_replace(
                '%PUBLIC%',
                \Request::getSchemeAndHttpHost(),
                $this->settings['logo']['path']
            );
        }
    }
}
