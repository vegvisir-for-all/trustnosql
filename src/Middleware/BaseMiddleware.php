<?php

namespace Vegvisir\TrustNoSql\Middleware;

class BaseMiddleware {

    const MIDDLEWARE_DELIMITER = '|';

    /**
     * The request is unauthorized, so it handles the aborting/redirecting.
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthorized()
    {
        $handling = Config::get('laratrust.middleware.handling');
        $handler = Config::get("laratrust.middleware.handlers.{$handling}", 'abort');

        if ($handling == 'abort') {
            return App::abort($handler['code']);
        }

        $redirect = Redirect::to($handler['url']);

        if (!empty($handler['message']['content'])) {
            $redirect->with($handler['message']['type'], $handler['message']['content']);
        }

        return $redirect;
    }

}
