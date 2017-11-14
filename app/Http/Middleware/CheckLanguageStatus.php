<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\Eloquent\LanguageRepositoryEloquent;

class CheckLanguageStatus
{

    public function __construct( LanguageRepositoryEloquent $languageRepository )
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $language_status = $this->languageRepository->getStatus( $request->id );
        /* 状态  0 lock, 1 open */
        if ( 0 == $language_status )
        {
            abort( 500, trans('admin/errors.language_lock') );
        }
        
        return $next($request);
    }
}
