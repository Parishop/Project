<?php
namespace Project\App\HTTPProcessors;

/**
 * Class Auth
 * @package Parishop\App\HTTPProcessors
 */
class Auth extends \Parishop\Processors\AppProcessor
{
    /**
     * @param \PHPixie\HTTP\Request $request
     * @return \PHPixie\HTTP\Responses\Response
     * @throws \Exception
     */
    public function callbackAction(\PHPixie\HTTP\Request $request)
    {
        $id          = $request->attributes()->getRequired('id');
        $callbackUrl = $this->url('auth', 'callback', $id);
        /** @var \PHPixie\AuthSocial\Providers\OAuth $providerSocial */
        $providerSocial = $this->domain()->provider('social');
        /** @var \PHPixie\Social\OAuth\User $socialUser */
        $socialUser = $providerSocial->handleCallback($id, (string)$callbackUrl, $request->query()->get());
        if($socialUser === null && ($error_message = $request->query()->get('error_message'))) {
            $this->messages->alert($error_message);
        } else {
            if(!$this->user()) {
                /** @var \Project\App\ORMWrappers\User\Repository $repository */
                $repository = $this->domain()->repository();
                $user       = $repository->create([$socialUser->providerName() . 'Id' => $socialUser->id()]);
                $user->save();
                $providerSocial->setUser($user);
            }
        }

        return $this->redirect($this->url('account'));
    }

    /**
     * @return mixed
     */
    public function defaultAction()
    {
        return $this->redirect($this->url('auth', 'login'));
    }

    /**
     * @param \PHPixie\HTTP\Request $request
     * @return mixed
     */
    public function loginAction(\PHPixie\HTTP\Request $request)
    {
        if($this->user()) {
            $this->redirect($this->url());
        }
        if($id = $request->attributes()->get('id')) {
            $callbackUrl = $this->url('auth', 'callback', $id);
            /** @var \PHPixie\AuthSocial\Providers\OAuth $providerSocial */
            $providerSocial = $this->domain()->provider('social');
            $redirectUrl    = $providerSocial->loginUrl($id, (string)$callbackUrl);

            return $this->redirect(urldecode($redirectUrl));
        }
        if($request->method() === 'POST') {
            return $this->signIn(
                $request->data()->getRequired('email'),
                $request->data()->getRequired('password'),
                $request->data()->get('remember'),
                $request->server()->get('HTTP_REFERER', $this->url()) === $this->url('auth', 'login') ? $request->server()->get('HTTP_REFERER', $this->url()) : $this->url('account')
            );
        }

        return $this->container;
    }

    /**
     * @return \PHPixie\HTTP\Responses\Response
     */
    public function logoutAction()
    {
        $this->domain()->forgetUser();

        return $this->redirect($this->url());
    }

    /**
     * @param \PHPixie\HTTP\Request $request
     * @return \PHPixie\Template\Container
     */
    public function registerAction(\PHPixie\HTTP\Request $request)
    {
        if($this->user()) {
            $this->redirect($this->url());
        }
        if($request->method() === 'POST') {
            return $this->signUp($request->data()->get(null, []));
        }
        $this->container->set('values', $this->session()->get('register', []));

        return $this->container;
    }

    /**
     * @param \PHPixie\HTTP\Request $request
     * @return \PHPixie\Template\Container
     */
    public function rewindAction(\PHPixie\HTTP\Request $request)
    {
        if($this->user()) {
            $this->redirect($this->url());
        }
        if($request->method() === 'POST') {
            $email = $request->data()->getRequired('email');

            return $this->rewind($email);
        }

        return $this->container;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function decrypt($string)
    {
        $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
        $qDecoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");

        return ($qDecoded);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function encrypt($string)
    {
        $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
        $qEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $string, MCRYPT_MODE_CBC, md5(md5($cryptKey))));

        return ($qEncoded);
    }

    /**
     * @param \PHPixie\HTTP\Request $request
     * @return \PHPixie\HTTP\Responses\Response
     */
    protected function resetAction(\PHPixie\HTTP\Request $request)
    {
        try {
            $hash   = $request->query()->getRequired('hash');
            $email  = $request->query()->getRequired('email');
            $domain = $this->domain();
            /** @var \Project\App\ORMWrappers\User\Repository $repository */
            $repository = $domain->repository();
            if($user = $repository->getByLogin($email)) {
                if($user->resetPassword() === $hash) {
                    /** @var \PHPixie\AuthLogin\Providers\Password $providerPassword */
                    $providerPassword = $domain->provider('password');
                    if($providerPassword->verify($user->email() . $user->id(), $hash)) {
                        $user->resetPassword(null);
                        $domain->setUser($user, 'password');
                        $providerPassword->persist();

                        return $this->redirect($this->url('account'));
                    }
                }
            }
            $this->messages->alert('Некорректная ссылка');
        } catch(\PHPixie\Slice\Exception $e) {
            $this->messages->alert('Отсутствуют параметры');
        }

        return $this->redirect($this->url());
    }

    /**
     * @param string $email
     * @return \PHPixie\HTTP\Responses\Response
     */
    protected function rewind($email)
    {
        $domain = $this->domain();
        /** @var \Project\App\ORMWrappers\User\Repository $repository */
        $repository = $domain->repository();
        /** @var \Project\App\ORMWrappers\User\Entity $user */
        if($user = $repository->getByLogin($email)) {
            /** @var \PHPixie\AuthLogin\Providers\Password $providerPassword */
            $providerPassword = $domain->provider('password');
            $hash             = $providerPassword->hash($user->email() . $user->id());
            $user->resetPassword($hash);
            $result = $this->mailer()->sendTemplate([$user->email()], 'Восстановление пароля', 'app:emails/account/rewind', ['link' => $this->url('auth', 'reset') . '?hash=' . $hash . '&email=' . $user->email()]);
            if($result) {
                $this->messages->info('Письмо со ссылкой для автоматической авторизации, отправлено вам на E-mail');

                return $this->redirect($this->url('auth', 'login'));
            } else {
                $this->messages->alert('Не удалось отправить письмо. Обратитесь с описанием проблемы на горячую линию');
            }
        } else {
            $this->messages->alert('Данный e-mail "' . $email . '" не зарегистрирован');
        }

        return $this->redirect($this->url('auth', 'rewind'));
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool   $remember
     * @param string $url
     * @return \PHPixie\HTTP\Responses\Response
     */
    protected function signIn($email, $password, $remember, $url)
    {
        $domain = $this->domain();
        /** @var \PHPixie\AuthLogin\Providers\Password $providerPassword */
        $providerPassword = $domain->provider('password');
        $user             = $providerPassword->login($email, $password);
        if($user === null) {
            $this->messages->alert('Ошибка авторизации');

            return $this->redirect($this->url('auth', 'login'));
        }
        if($remember) {
            /** @var \PHPixie\AuthHTTP\Providers\Cookie $providerCookie */
            $providerCookie = $domain->provider('cookie');
            $providerCookie->persist();
        }

        return $this->redirect($url);
    }

    /**
     * @param array $data
     * @return \PHPixie\HTTP\Responses\Response
     */
    protected function signUp(array $data)
    {
        $domain = $this->domain();
        /** @var \Project\App\ORMWrappers\User\Repository $repository */
        $repository = $domain->repository();
        if($repository->isValid($data)) {
            $this->session()->remove('register');
            try {
                /** @var \Project\App\ORMWrappers\User\Entity $entity */
                $entity = $repository->create($repository->validateValue());
                $entity->save();
            } catch(\PDOException $e) {
                if($e->getCode() === '23000') {
                    $this->messages->alert('Данный пользователь уже зарегистрирован в системе');
                    $this->session()->set('register', $repository->validateValue());

                    return $this->redirect($this->url('auth', 'register'));
                } else {
                    $this->messages->alert($e->getMessage());
                }
            } catch(\Exception $e) {
                $this->messages->alert($e->getMessage());
            }

            return $this->redirect($this->url('account'));
        } else {
            $this->messages->alert('Не все поля заполнены корректно');
            $this->session()->set('register', $repository->validateValue());
        }

        return $this->redirect($this->url('auth', 'register'));
    }

}

